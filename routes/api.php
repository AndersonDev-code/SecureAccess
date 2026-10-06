<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BadgeController; // Correction : Http au lieu de Htpp
use App\Http\Controllers\PointageController;

// Route de test ESP32
Route::get('/esp32/test', function(){
    return response()->json([
        'success' => true,
        'message' => 'ESP32-CAM connecte a laravel'
    ]);
});

// Route pour l'enregistrement du scan RFID
Route::post('/esp32/scan', [BadgeController::class, 'storeScan']);

// === ROUTE PROXY AJOUTÉE POUR CONTOURNER LE CORS ESP32-CAM ===
Route::get('/esp32/capture', function() {
    try {
        // Remplacez l'IP si l'ESP32-CAM change d'adresse sur le réseau
        $esp32Url = 'http://192.168.1.156/capture'; 

        $response = Http::timeout(5)->get($esp32Url);

        if ($response->successful()) {
            return response($response->body(), 200)
                ->header('Content-Type', 'image/jpeg');
        }

        return response()->json(['error' => 'Échec de la capture depuis l ESP32'], 500);
    } catch (\Exception $e) {
        return response()->json(['error' => 'ESP32 non joignable: ' . $e->getMessage()], 504);
    }
});


Route::post('/test-pointage', [PointageController::class, 'enregistrer']);


Route::post(
    '/esp32/registration/start',
    [BadgeController::class, 'startRegistrationScan']
);

Route::get(
    '/esp32/registration/latest',
    [BadgeController::class, 'getRegistrationScan']
);