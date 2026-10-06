<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\AutomaticRecognitionService;
use App\Models\FraudLog;

class AutomaticRecognition extends Command
{
    protected $signature = 'recognition:auto';

    protected $description = 'Surveille automatiquement la caméra et effectue la reconnaissance faciale';

    public function handle(AutomaticRecognitionService $service): int
    {
        $this->info('==========================================');
        $this->info('SURVEILLANCE FACIALE AUTOMATIQUE');
        $this->info('==========================================');
        $this->info('La caméra est maintenant surveillée.');
        $this->info('Appuyez sur CTRL+C pour arrêter.');
        $this->info('');

        while (true) {

            $heure = now()->format('H:i:s');

            $this->line("[$heure] Capture en cours...");

            try {

                $result = $service->process();

                // -------------------------------------------------
                // VISAGE RECONNU
                // -------------------------------------------------
                if (($result['recognized'] ?? false) === true) {

                    $pointage = $result['pointage']['pointage'] ?? null;

                    if ($pointage) {

                        $this->info(
                            "[$heure] ✓ Visage reconnu : "
                            . $pointage['prenom'] . ' '
                            . $pointage['nom']
                        );

                        $this->info(
                            "[$heure] ✓ Pointage : "
                            . $pointage['type']
                            . ' - '
                            . $pointage['status']
                        );

                    } else {

                        $this->warn(
                            "[$heure] Visage reconnu mais aucun nouveau pointage."
                        );
                    }

                }

                // -------------------------------------------------
                // VISAGE NON RECONNU
                // -------------------------------------------------
                elseif (
                    ($result['recognized'] ?? false) === false
                    && ($result['success'] ?? false) === true
                ) {

                    $this->comment(
                        "[$heure] Aucun visage reconnu."
                    );

                    // Enregistrement de la tentative comme fraude
                    FraudLog::create([
                        'user_id' => null,
                        'rfid_uid' => null,
                        'photo_capture' => $result['capture'] ?? null,
                        'ip_station' => '192.168.1.156',
                        'type' => 'VISAGE_INVALIDE',
                        'description' => 'Visage détecté mais aucune correspondance avec les références faciales enregistrées.',
                        'heure_fraude' => now(),
                    ]);

                    $this->warn(
                        "[$heure] ⚠ Tentative enregistrée dans le journal des fraudes."
                    );
                }

                // -------------------------------------------------
                // ERREUR TECHNIQUE
                // -------------------------------------------------
                else {

                    $this->error(
                        "[$heure] Problème : "
                        . ($result['message'] ?? 'Erreur inconnue.')
                    );
                }

            } catch (\Throwable $e) {

                $this->error(
                    "[$heure] Erreur : " . $e->getMessage()
                );
            }

            // -------------------------------------------------
            // ATTENTE AVANT LA PROCHAINE CAPTURE
            // -------------------------------------------------

            $this->line(
                "[$heure] Prochaine vérification dans 10 secondes..."
            );

            sleep(10);

            $this->line('');
        }

        return self::SUCCESS;
    }
}

