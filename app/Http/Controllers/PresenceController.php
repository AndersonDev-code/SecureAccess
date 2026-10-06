<?php

namespace App\Http\Controllers;

use App\Models\Pointage;
use Illuminate\Http\Request;

class PresenceController extends Controller
{
    /**
     * Affiche la page des présences.
     */
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | FILTRES
        |--------------------------------------------------------------------------
        */

        $periode = $request->get('periode', 'toutes');
        $recherche = trim($request->get('recherche', ''));
        $type = $request->get('type', 'tous');


        /*
        |--------------------------------------------------------------------------
        | REQUÊTE PRINCIPALE
        |--------------------------------------------------------------------------
        */

        $query = Pointage::with('user');


        /*
        |--------------------------------------------------------------------------
        | FILTRE PAR PÉRIODE
        |--------------------------------------------------------------------------
        */

        switch ($periode) {

            case 'aujourd_hui':

                $query->whereDate('date', today());

                break;


            case 'hier':

                $query->whereDate(
                    'date',
                    today()->subDay()
                );

                break;


            case 'semaine':

                $query->whereBetween('date', [
                    today()->startOfWeek(),
                    today()->endOfWeek()
                ]);

                break;


            case 'mois':

                $query->whereMonth('date', today()->month)
                      ->whereYear('date', today()->year);

                break;


            case 'toutes':

            default:

                // Aucun filtre de date.

                break;
        }


        /*
        |--------------------------------------------------------------------------
        | FILTRE PAR TYPE
        |--------------------------------------------------------------------------
        */

        if ($type === 'entrees') {

            $query->where('type', 'ENTREE');

        } elseif ($type === 'sorties') {

            $query->where('type', 'SORTIE');

        } elseif ($type === 'retards') {

            $query->where('status', 'RETARD');
        }


        /*
        |--------------------------------------------------------------------------
        | RECHERCHE EMPLOYÉ
        |--------------------------------------------------------------------------
        */

        if ($recherche !== '') {

            $query->whereHas('user', function ($userQuery) use ($recherche) {

                $userQuery
                    ->where('nom', 'like', '%' . $recherche . '%')
                    ->orWhere('prenom', 'like', '%' . $recherche . '%')
                    ->orWhere('matricule', 'like', '%' . $recherche . '%');

            });
        }


        /*
        |--------------------------------------------------------------------------
        | RÉSULTATS
        |--------------------------------------------------------------------------
        */

        $pointages = $query
            ->orderByDesc('date')
            ->orderByDesc('heure')
            ->paginate(3)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | STATISTIQUES
        |--------------------------------------------------------------------------
        |
        | On utilise la même période que le filtre.
        |
        */

        $statsQuery = Pointage::query();


        switch ($periode) {

            case 'aujourd_hui':

                $statsQuery->whereDate('date', today());

                break;


            case 'hier':

                $statsQuery->whereDate(
                    'date',
                    today()->subDay()
                );

                break;


            case 'semaine':

                $statsQuery->whereBetween('date', [
                    today()->startOfWeek(),
                    today()->endOfWeek()
                ]);

                break;


            case 'mois':

                $statsQuery->whereMonth('date', today()->month)
                           ->whereYear('date', today()->year);

                break;


            case 'toutes':

            default:

                // Toutes les dates.

                break;
        }


        /*
        |--------------------------------------------------------------------------
        | STATISTIQUES
        |--------------------------------------------------------------------------
        */

        $totalEntrees = (clone $statsQuery)
            ->where('type', 'ENTREE')
            ->count();


        $totalSorties = (clone $statsQuery)
            ->where('type', 'SORTIE')
            ->count();


        $totalRetards = (clone $statsQuery)
            ->where('status', 'RETARD')
            ->count();


        /*
        |--------------------------------------------------------------------------
        | PERSONNES PRÉSENTES
        |--------------------------------------------------------------------------
        |
        | Pour "toutes les dates", on regarde le dernier pointage
        | connu de chaque utilisateur.
        |
        | Pour une période précise, on regarde le dernier pointage
        | de chaque utilisateur dans cette période.
        |
        */

        $totalPresents = $this->calculerPresents($periode);


        /*
        |--------------------------------------------------------------------------
        | VUE
        |--------------------------------------------------------------------------
        */

        return view('presences.index', compact(
            'pointages',
            'totalEntrees',
            'totalSorties',
            'totalRetards',
            'totalPresents',
            'periode',
            'recherche',
            'type'
        ));
    }


    /**
     * Calcule le nombre de personnes actuellement présentes.
     */
    private function calculerPresents($periode)
    {
        $users = \App\Models\User::all();

        $presents = 0;


        foreach ($users as $user) {

            $query = Pointage::where('user_id', $user->id);


            /*
            |--------------------------------------------------------------------------
            | PÉRIODE
            |--------------------------------------------------------------------------
            */

            switch ($periode) {

                case 'aujourd_hui':

                    $query->whereDate('date', today());

                    break;


                case 'hier':

                    $query->whereDate(
                        'date',
                        today()->subDay()
                    );

                    break;


                case 'semaine':

                    $query->whereBetween('date', [
                        today()->startOfWeek(),
                        today()->endOfWeek()
                    ]);

                    break;


                case 'mois':

                    $query->whereMonth('date', today()->month)
                          ->whereYear('date', today()->year);

                    break;


                case 'toutes':

                default:

                    break;
            }


            /*
            |--------------------------------------------------------------------------
            | DERNIER POINTAGE
            |--------------------------------------------------------------------------
            */

            $dernierPointage = $query
                ->orderByDesc('date')
                ->orderByDesc('heure')
                ->first();


            /*
            |--------------------------------------------------------------------------
            | PRÉSENT
            |--------------------------------------------------------------------------
            */

            if (
                $dernierPointage &&
                $dernierPointage->type === 'ENTREE'
            ) {

                $presents++;
            }
        }


        return $presents;
    }
}