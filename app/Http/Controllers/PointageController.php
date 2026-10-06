<?php

namespace App\Http\Controllers;

use App\Models\Pointage;
use App\Models\User;
use App\Events\PointageEnregistre;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class PointageController extends Controller
{
    /**
     * Pointage appelé directement avec un user_id.
     * Utilisé pour les tests.
     */
    public function enregistrer(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer|exists:users,id',
        ]);

        $user = User::findOrFail($request->user_id);

        return $this->creerPointage($user);
    }

    /**
     * Crée réellement le pointage d'un employé.
     *
     * Cette méthode pourra être appelée directement
     * après une reconnaissance faciale réussie.
     */
    

    public function creerPointage(User $user)
{
    // Vérifier que l'employé est actif
    if (!$user->is_active) {
        return response()->json([
            'success' => false,
            'message' => 'Cet employé est désactivé.'
        ], 403);
    }

    // Date et heure actuelles
    $now = Carbon::now();

    $date = $now->toDateString();
    $heure = $now->format('H:i:s');

    /*
     * Récupérer le dernier pointage de l'employé aujourd'hui.
     *
     * IMPORTANT :
     * On ne supprime aucun ancien pointage.
     * On regarde simplement le dernier pour savoir
     * s'il faut enregistrer une ENTREE ou une SORTIE.
     */
    $dernierPointage = Pointage::where('user_id', $user->id)
        ->where('date', $date)
        ->latest('id')
        ->first();

        // ---------------------------------------------------------
        // ANTI-DOUBLE-SCAN
        // ---------------------------------------------------------

        // Si un pointage existe depuis moins de 60 secondes,
        // on considère qu'il s'agit probablement du même scan.
    if ($dernierPointage) {

        $heureDernierPointage = Carbon::parse(
            $date . ' ' . $dernierPointage->heure
        );

        $secondesEcoulees = $heureDernierPointage->diffInSeconds($now);

        if ($secondesEcoulees < 60) {
            return response()->json([
                'success' => false,
                    'message' => 'Scan ignoré : un pointage vient déjà d’être enregistré il y a moins de 60 secondes.',
                'anti_double_scan' => true,
                'dernier_pointage' => [
                'id' => $dernierPointage->id,
                'type' => $dernierPointage->type,
                'heure' => $dernierPointage->heure,
                ],
            ], 429);
        }
    }

    /*
     * Logique :
     *
     * Aucun pointage aujourd'hui → ENTREE
     * Dernier pointage = SORTIE → ENTREE
     * Dernier pointage = ENTREE → SORTIE
     */
    if (!$dernierPointage || $dernierPointage->type === 'SORTIE') {
        $type = 'ENTREE';
    } else {
        $type = 'SORTIE';
    }

    /*
     * Par défaut, le statut est NORMAL.
     *
     * Le RETARD ne concerne que les ENTREE.
     * Une SORTIE sera toujours NORMAL.
     */
    $status = 'NORMAL';

    if ($type === 'ENTREE') {

        // Récupérer l'horaire de l'employé
        $schedule = $user->schedules;

        if ($schedule) {

            /*
             * Heure limite =
             * heure d'entrée prévue + tolérance
             *
             * Exemple :
             * entrée = 08:00
             * tolérance = 10 minutes
             * limite = 08:10
             */
            $heureLimite = Carbon::parse($schedule->heure_entree)
                ->addMinutes((int) $schedule->tolerance);

            /*
             * Si l'employé arrive après l'heure limite,
             * son pointage est RETARD.
             */
            if ($now->format('H:i:s') > $heureLimite->format('H:i:s')) {
                $status = 'RETARD';
            }
        }
    }

    // Enregistrer le nouveau pointage
    $pointage = Pointage::create([
        'user_id' => $user->id,
        'date' => $date,
        'heure' => $heure,
        'type' => $type,
        'status' => $status,
    ]);


    // ========================================================
// DONNÉES DU GRAPHIQUE POUR LE TEMPS RÉEL
// ========================================================
//
// Le graphique représente le nombre de pointages
// pour chaque jour de la semaine courante.
//
// Lundi → Dimanche
// ========================================================

$debutSemaine = $now->copy()->startOfWeek();

$chartRealtimeData = [];

$chartRealtimeLabels = [];

$nomsJours = [
    'Lun',
    'Mar',
    'Mer',
    'Jeu',
    'Ven',
    'Sam',
    'Dim'
];

for ($i = 0; $i < 7; $i++) {

    $dateJour = $debutSemaine
        ->copy()
        ->addDays($i);

    $chartRealtimeLabels[] =
        $nomsJours[$i];

    $chartRealtimeData[] =
        Pointage::whereDate(
            'date',
            $dateJour->toDateString()
        )->count();
}


    // Envoyer le nouveau pointage au dashboard en temps réel
    event(new PointageEnregistre([
        'id' => $pointage->id,
        'user_id' => $user->id,
        'photo' => $user->photo,
        'nom' => $user->nom,
        'prenom' => $user->prenom,
        'matricule' => $user->matricule,
        'service'=> $user->service,
        /*
        * URL complète de la photo.
        *
        * Le navigateur n'a donc plus besoin de deviner
        * comment construire le chemin.
        */
        'photo_url' => $user->photo
        ? asset('storage/' . $user->photo)
        : null,

        'date' => $date,
        'heure' => $heure,
        'type' => $type,
        'status' => $status,

        
    // ==========================================
    // GRAPHIQUE TEMPS RÉEL
    // ==========================================

    'chart_labels' =>
        $chartRealtimeLabels,

    'chart_data' =>
        $chartRealtimeData,
    ]));

    // Retourner le résultat
    return response()->json([
        'success' => true,
        'message' => 'Pointage enregistré avec succès.',
        'pointage' => [
            'id' => $pointage->id,
            'user_id' => $user->id,
            'nom' => $user->nom,
            'prenom' => $user->prenom,
            'date' => $date,
            'heure' => $heure,
            'type' => $type,
            'status' => $status,
        ],
    ]);
}
}