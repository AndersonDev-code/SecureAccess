<?php

namespace App\Http\Controllers;

use App\Models\FraudLog;
use Illuminate\Http\Request;

class FraudController extends Controller
{
    /**
     * Afficher le journal des fraudes.
     */
    public function index(Request $request)
    {
        $query = FraudLog::with('user')
            ->orderByDesc('heure_fraude');

        /*
        |--------------------------------------------------------------------------
        | RECHERCHE
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('rfid_uid', 'like', '%' . $search . '%')
                  ->orWhere('ip_station', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');

            });
        }

        /*
        |--------------------------------------------------------------------------
        | FILTRE PAR TYPE
        |--------------------------------------------------------------------------
        */

        if ($request->filled('type')) {

            $query->where('type', $request->type);

        }

        /*
        |--------------------------------------------------------------------------
        | PAGINATION
        |--------------------------------------------------------------------------
        */

        $frauds = $query
            ->paginate(5)
            ->withQueryString();

        return view('frauds.index', compact('frauds'));
    }
}