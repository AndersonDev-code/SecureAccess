<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Pointage;
use App\Models\FraudLog;

class DashboardController extends Controller
{   
    public function index()
    {
        // Nombre total d'employés
        $totalEmployees = User::count();

        // Nombre d'employés ayant effectué au moins un pointage aujourd'hui
        $presentEmployees = Pointage::whereDate('date', today())
            ->distinct('user_id')
            ->count('user_id');

        // Nombre de retards aujourd'hui
        $lateToday = Pointage::whereDate('date', today())
            ->where('status', 'RETARD')
            ->count();

        // 5 dernières alertes de fraude
        $frauds = FraudLog::latest()
            ->take(5)
            ->get();

        // Derniers pointages enregistrés aujourd'hui
        $recentPointages = Pointage::with('user')
            ->whereDate('date', today())
            ->latest('id')
            ->take(10)
            ->get();

            // Nombre de pointages par jour pour les 6 derniers jours
        $pointagesParJour = Pointage::selectRaw('DATE(date) as jour, COUNT(*) as total')
            ->whereBetween('date', [
            today()->subDays(5),
            today()
        ])
        ->groupBy('jour')
        ->orderBy('jour')
        ->get()
        ->keyBy('jour');

        // On construit toujours exactement 6 jours
        $chartLabels = [];
        $chartData = [];

        for ($i = 5; $i >= 0; $i--) {

            $date = today()->subDays($i);
            $jour = $date->toDateString();

            $chartLabels[] = $date->locale('fr')->isoFormat('ddd');

            $chartData[] = $pointagesParJour->get($jour)->total ?? 0;
        }

        // Envoi des données vers le dashboard
        return view('dashboard.index', compact(
            'totalEmployees',
            'presentEmployees',
            'lateToday',
            'frauds',
            'recentPointages',
            'chartLabels',
            'chartData'
        ));
    }
}