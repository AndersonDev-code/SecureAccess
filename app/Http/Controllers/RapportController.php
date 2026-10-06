<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pointage;
use App\Models\Rapport;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class RapportController extends Controller
{
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | EMPLOYÉS
        |--------------------------------------------------------------------------
        */

        $users = User::orderBy('nom')
            ->orderBy('prenom')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | FILTRES
        |--------------------------------------------------------------------------
        */

        $periode = $request->get('periode', 'aujourd_hui');

        $dateDebut = $request->get('date_debut');
        $dateFin = $request->get('date_fin');

        $employe = $request->get('employe', 'tous');

        $typeRapport = $request->get(
            'type_rapport',
            'pointage'
        );


        /*
        |--------------------------------------------------------------------------
        | DÉTERMINATION DES DATES
        |--------------------------------------------------------------------------
        */

        if (!$dateDebut || !$dateFin) {

            switch ($periode) {

                case 'hier':

                    $dateDebut = today()
                        ->subDay()
                        ->format('Y-m-d');

                    $dateFin = $dateDebut;

                    break;


                case 'semaine':

                    $dateDebut = today()
                        ->startOfWeek()
                        ->format('Y-m-d');

                    $dateFin = today()
                        ->endOfWeek()
                        ->format('Y-m-d');

                    break;


                case 'mois':

                    $dateDebut = today()
                        ->startOfMonth()
                        ->format('Y-m-d');

                    $dateFin = today()
                        ->endOfMonth()
                        ->format('Y-m-d');

                    break;


                case 'personnalisee':

                    // Les dates sont saisies manuellement.
                    break;


                case 'aujourd_hui':
                default:

                    $dateDebut = today()->format('Y-m-d');
                    $dateFin = $dateDebut;

                    break;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | REQUÊTE
        |--------------------------------------------------------------------------
        */

        $query = Pointage::with('user');


        /*
        |--------------------------------------------------------------------------
        | PÉRIODE
        |--------------------------------------------------------------------------
        */

        if ($dateDebut && $dateFin) {

            $query->whereBetween('date', [
                $dateDebut,
                $dateFin
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | EMPLOYÉ
        |--------------------------------------------------------------------------
        */

        if (
            $employe !== 'tous'
            && !empty($employe)
        ) {

            $query->where(
                'user_id',
                $employe
            );
        }


        /*
        |--------------------------------------------------------------------------
        | TYPE DE RAPPORT
        |--------------------------------------------------------------------------
        */

        switch ($typeRapport) {

            case 'retard':

                // Un retard est déterminé par le système
                // au moment du scan d'entrée.
                $query->where(
                    'status',
                    'RETARD'
                );

                break;


            case 'entrees_sortie':

                // On conserve les deux types de pointage.
                $query->whereIn(
                    'type',
                    ['ENTREE', 'SORTIE']
                );

                break;


            case 'personnel':
            case 'pointage':
            default:

                // Aucun filtre supplémentaire.
                break;
        }


        /*
        |--------------------------------------------------------------------------
        | RÉSULTATS
        |--------------------------------------------------------------------------
        */

        $pointages = $query
            ->orderByDesc('date')
            ->orderByDesc('heure')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | STATISTIQUES
        |--------------------------------------------------------------------------
        */

        $totalPointages = $pointages->count();

        $totalEntrees = $pointages
            ->where('type', 'ENTREE')
            ->count();

        $totalSorties = $pointages
            ->where('type', 'SORTIE')
            ->count();

        $totalRetards = $pointages
            ->where('status', 'RETARD')
            ->count();

        $totalPresents = $pointages
            ->where('type', 'ENTREE')
            ->where('status', 'NORMAL')
            ->count();


        /*
        |--------------------------------------------------------------------------
        | PRÉVISUALISATION
        |--------------------------------------------------------------------------
        */

        $previsualisation = $request->has(
            'previsualiser'
        );


        /*
        |--------------------------------------------------------------------------
        | VUE
        |--------------------------------------------------------------------------
        */

        // Historique des rapports générés
        $rapports = Rapport::with('user')
            ->latest()
            ->get();

        return view(
            'rapports.index',
            compact(
                'users',
                'pointages',
                'periode',
                'dateDebut',
                'dateFin',
                'employe',
                'typeRapport',
                'totalPointages',
                'totalEntrees',
                'totalSorties',
                'totalRetards',
                'totalPresents',
                'previsualisation',
                'rapports'
            )
        );
    }


    public function pdf(Request $request)
{
    $employe = $request->get('employe', 'tous');
    $typeRapport = $request->get('type_rapport', 'pointage');
    $periode = $request->get('periode', 'aujourd_hui');

    $dateDebut = $request->get('date_debut');
    $dateFin = $request->get('date_fin');

    /*
    |--------------------------------------------------------------------------
    | Détermination automatique des dates
    |--------------------------------------------------------------------------
    */

    if (!$dateDebut || !$dateFin) {

        switch ($periode) {

            case 'hier':
                $dateDebut = today()->subDay()->format('Y-m-d');
                $dateFin = $dateDebut;
                break;

            case 'semaine':
                $dateDebut = today()->startOfWeek()->format('Y-m-d');
                $dateFin = today()->endOfWeek()->format('Y-m-d');
                break;

            case 'mois':
                $dateDebut = today()->startOfMonth()->format('Y-m-d');
                $dateFin = today()->endOfMonth()->format('Y-m-d');
                break;

            case 'personnalisee':
                break;

            case 'aujourd_hui':
            default:
                $dateDebut = today()->format('Y-m-d');
                $dateFin = $dateDebut;
                break;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Recherche des pointages
    |--------------------------------------------------------------------------
    */

    $query = Pointage::with('user');

    if ($dateDebut && $dateFin) {
        $query->whereBetween('date', [$dateDebut, $dateFin]);
    }

    /*
    |--------------------------------------------------------------------------
    | Filtre employé
    |--------------------------------------------------------------------------
    */

    if ($employe !== 'tous' && !empty($employe)) {
        $query->where('user_id', $employe);
    }

    /*
    |--------------------------------------------------------------------------
    | Type de rapport
    |--------------------------------------------------------------------------
    */

    switch ($typeRapport) {

        case 'retard':
            $query->where('status', 'RETARD');
            break;

        case 'entrees_sortie':
            $query->whereIn('type', ['ENTREE', 'SORTIE']);
            break;

        case 'personnel':
        case 'pointage':
        default:
            break;
    }

    $pointages = $query
        ->orderByDesc('date')
        ->orderByDesc('heure')
        ->get();

    /*
    |--------------------------------------------------------------------------
    | Informations de l'employé sélectionné
    |--------------------------------------------------------------------------
    */

    $userSelectionne = null;

    if ($employe !== 'tous' && !empty($employe)) {
        $userSelectionne = User::find($employe);
    }

    /*
    |--------------------------------------------------------------------------
    | Statistiques
    |--------------------------------------------------------------------------
    */

    $totalPointages = $pointages->count();

    $totalEntrees = $pointages
        ->where('type', 'ENTREE')
        ->count();

    $totalSorties = $pointages
        ->where('type', 'SORTIE')
        ->count();

    $totalRetards = $pointages
        ->where('status', 'RETARD')
        ->count();

    $totalPresents = $pointages
        ->where('type', 'ENTREE')
        ->where('status', 'NORMAL')
        ->count();

    /*
    |--------------------------------------------------------------------------
    | Génération du PDF
    |--------------------------------------------------------------------------
    */

    $pdf = Pdf::loadView('rapports.pdf', compact(
        'pointages',
        'periode',
        'dateDebut',
        'dateFin',
        'employe',
        'typeRapport',
        'userSelectionne',
        'totalPointages',
        'totalEntrees',
        'totalSorties',
        'totalRetards',
        'totalPresents'
    ));

   $pdf->setPaper('A4', 'portrait');

/*
|--------------------------------------------------------------------------
| Enregistrement dans l'historique
|--------------------------------------------------------------------------
*/

// Nom du fichier PDF
$nomFichier = 'rapport-secureaccess-' . now()->format('Y-m-d-His') . '.pdf';

// Téléchargement du PDF
return $pdf->download($nomFichier);
}


public function enregistrer(Request $request)
{
    $employe = $request->get('employe', 'tous');
    $typeRapport = $request->get('type_rapport', 'pointage');
    $periode = $request->get('periode', 'aujourd_hui');

    $dateDebut = $request->get('date_debut');
    $dateFin = $request->get('date_fin');

    /*
    |--------------------------------------------------------------------------
    | Détermination automatique des dates
    |--------------------------------------------------------------------------
    */

    if (!$dateDebut || !$dateFin) {

        switch ($periode) {

            case 'hier':

                $dateDebut = today()
                    ->subDay()
                    ->format('Y-m-d');

                $dateFin = $dateDebut;

                break;

            case 'semaine':

                $dateDebut = today()
                    ->startOfWeek()
                    ->format('Y-m-d');

                $dateFin = today()
                    ->endOfWeek()
                    ->format('Y-m-d');

                break;

            case 'mois':

                $dateDebut = today()
                    ->startOfMonth()
                    ->format('Y-m-d');

                $dateFin = today()
                    ->endOfMonth()
                    ->format('Y-m-d');

                break;

            case 'personnalisee':

                break;

            case 'aujourd_hui':
            default:

                $dateDebut = today()->format('Y-m-d');
                $dateFin = $dateDebut;

                break;
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Recherche des pointages
    |--------------------------------------------------------------------------
    */

    $query = Pointage::query();


    if ($dateDebut && $dateFin) {

        $query->whereBetween('date', [
            $dateDebut,
            $dateFin
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Filtre employé
    |--------------------------------------------------------------------------
    */

    if (
        $employe !== 'tous'
        && !empty($employe)
    ) {

        $query->where(
            'user_id',
            $employe
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Type de rapport
    |--------------------------------------------------------------------------
    */

    switch ($typeRapport) {

        case 'retard':

            $query->where(
                'status',
                'RETARD'
            );

            break;

        case 'entrees_sortie':

            $query->whereIn(
                'type',
                ['ENTREE', 'SORTIE']
            );

            break;

        case 'personnel':
        case 'pointage':
        default:

            break;
    }


    /*
    |--------------------------------------------------------------------------
    | Nombre de pointages
    |--------------------------------------------------------------------------
    */

    $totalPointages = $query->count();


    /*
    |--------------------------------------------------------------------------
    | Nom du fichier
    |--------------------------------------------------------------------------
    */

    $nomFichier =
        'rapport-secureaccess-' .
        now()->format('Y-m-d-His') .
        '.pdf';


    /*
    |--------------------------------------------------------------------------
    | Enregistrement
    |--------------------------------------------------------------------------
    */

    Rapport::create([

        'type_rapport' => $typeRapport,

        'user_id' => (
            $employe !== 'tous'
            && !empty($employe)
        )
            ? $employe
            : null,

        'date_debut' => $dateDebut,

        'date_fin' => $dateFin,

        'nom_fichier' => $nomFichier,

        'nombre_pointages' => $totalPointages,

    ]);


    return redirect()
        ->route('rapports.index')
        ->with(
            'success',
            'Le rapport a été enregistré dans l’historique.'
        );
}

}