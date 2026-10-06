<?php

namespace App\Services;

use App\Models\User;
use App\Services\FaceRecognitionService;
use App\Http\Controllers\PointageController;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class AutomaticRecognitionService
{
    protected FaceRecognitionService $faceService;

    public function __construct(FaceRecognitionService $faceService)
    {
        $this->faceService = $faceService;
    }

    /**
     * Effectue une capture + reconnaissance + pointage.
     *
     * Cette méthode sera appelée automatiquement plus tard.
     */
    public function process(): array
    {
        try {

            // -------------------------------------------------
            // 1. CAPTURER UNE IMAGE DEPUIS L'ESP32-CAM
            // -------------------------------------------------

            $esp32Url = 'http://192.168.1.156/capture';

            $response = Http::timeout(5)->get($esp32Url);

            if (!$response->successful()) {

                return [
                    'success' => false,
                    'recognized' => false,
                    'message' => 'Impossible de contacter l’ESP32-CAM.',
                ];
            }

            // -------------------------------------------------
            // 2. SAUVEGARDER LA CAPTURE
            // -------------------------------------------------

            $filename = 'auto-' . time() . '.jpg';

            Storage::disk('local')->put(
                'face-tests/' . $filename,
                $response->body()
            );

            $imagePath = storage_path(
                'app/private/face-tests/' . $filename
            );

            // -------------------------------------------------
            // 3. RÉCUPÉRER LES RÉFÉRENCES FACIALES
            // -------------------------------------------------

            $references = $this->faceService->getAllReferences();

            if (empty($references)) {

                return [
                    'success' => false,
                    'recognized' => false,
                    'message' => 'Aucune référence faciale disponible.',
                    'capture' => $filename,
                ];
            }

            // -------------------------------------------------
            // 4. PRÉPARER LES RÉFÉRENCES POUR PYTHON
            // -------------------------------------------------

            $referencesPath = storage_path(
                'app/private/face-tests/references.json'
            );

            file_put_contents(
                $referencesPath,
                json_encode($references, JSON_PRETTY_PRINT)
            );

            // -------------------------------------------------
            // 5. LANCER LA RECONNAISSANCE
            // -------------------------------------------------

            $python = 'C:\\xampp\\htdocs\\secureaccess-face\\venv\\Scripts\\python.exe';

            $script = 'C:\\xampp\\htdocs\\secureaccess-face\\reconnaissance.py';

            $command = '"' . $python . '" "' . $script . '" "'
                . $imagePath . '" "'
                . $referencesPath . '"';

            $output = shell_exec($command);

            $data = json_decode($output, true);

            if (!is_array($data)) {

                Log::error('Réponse Python invalide', [
                    'output' => $output
                ]);

                return [
                    'success' => false,
                    'recognized' => false,
                    'message' => 'Réponse invalide du système de reconnaissance.',
                    'capture' => $filename,
                ];
            }

            // -------------------------------------------------
            // 6. SI VISAGE RECONNU → POINTAGE
            // -------------------------------------------------

            $pointage = null;

            if (
                ($data['recognized'] ?? false) === true &&
                !empty($data['user_id'])
            ) {

                $user = User::find($data['user_id']);

                if ($user) {

                    $pointageResponse = app(
                        PointageController::class
                    )->creerPointage($user);

                    $pointage = $pointageResponse->getData(true);
                }
            }

            // -------------------------------------------------
            // 7. RETOURNER LE RÉSULTAT
            // -------------------------------------------------

            return [
                'success' => $data['success'] ?? false,
                'recognized' => $data['recognized'] ?? false,
                'user_id' => $data['user_id'] ?? null,
                'score' => $data['score'] ?? null,
                'threshold' => $data['threshold'] ?? null,
                'reference' => $data['reference'] ?? null,
                'face_confidence' => $data['face_confidence'] ?? null,
                'message' => $data['message'] ?? null,
                'pointage' => $pointage,
                'capture' => $filename,
            ];

        } catch (\Throwable $e) {

            Log::error(
                'Erreur reconnaissance automatique',
                [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ]
            );

            return [
                'success' => false,
                'recognized' => false,
                'message' => 'Erreur lors de la reconnaissance automatique.',
                'error' => $e->getMessage(),
            ];
        }
    }
}