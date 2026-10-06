<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\EmployeesControler;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PointageController;
use App\Http\Controllers\PresenceController;
use App\Http\Controllers\RapportController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FraudController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\BadgeController;

use App\Services\FaceRecognitionService;
use App\Services\AutomaticRecognitionService;

use App\Models\User;
use App\Models\FaceEncoding;


/*
|--------------------------------------------------------------------------
| PAGE D'ACCUEIL
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});


/*
|--------------------------------------------------------------------------
| AUTHENTIFICATION
|--------------------------------------------------------------------------
|
| La page de connexion reste accessible aux utilisateurs non connectés.
|
*/

Route::get('/login', [AuthController::class, 'showLogin'])
    ->middleware('guest')
    ->name('login');

Route::post('/login', [AuthController::class, 'login'])
    ->middleware('guest')
    ->name('login.authenticate');

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');


/*
|--------------------------------------------------------------------------
| ESPACE ADMINISTRATEUR
|--------------------------------------------------------------------------
|
| Toutes les routes placées ici nécessitent :
|
| 1. une authentification ;
| 2. le rôle administrateur.
|
*/

Route::middleware(['admin'])->group(function () {


    /*
    |--------------------------------------------------------------------------
    | TABLEAU DE BORD
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');


    /*
    |--------------------------------------------------------------------------
    | EMPLOYÉS
    |--------------------------------------------------------------------------
    */

    Route::resource('employees', EmployeesControler::class);

    /*
    |--------------------------------------------------------------------------
    | JOURNAL DES FRAUDES
    |--------------------------------------------------------------------------
    */

    Route::get('/fraudes', [FraudController::class, 'index'])
    ->middleware('auth')
    ->name('frauds.index');


    /*
    |--------------------------------------------------------------------------
    | PRÉSENCES
    |--------------------------------------------------------------------------
    */

    Route::get('/presences', [PresenceController::class, 'index'])
        ->name('presences.index');


    /*
    |--------------------------------------------------------------------------
    | RAPPORTS
    |--------------------------------------------------------------------------
    */

    Route::get('/rapports', [RapportController::class, 'index'])
        ->name('rapports.index');

    Route::get('/rapports/pdf', [RapportController::class, 'pdf'])
        ->name('rapports.pdf');

    Route::post('/rapports/enregistrer', [RapportController::class, 'enregistrer'])
        ->name('rapports.enregistrer');


    /*
    |--------------------------------------------------------------------------
    | TEST EMPREINTES FACIALES
    |--------------------------------------------------------------------------
    */

    Route::get('/test-face-references', function (FaceRecognitionService $service) {

        $references = $service->getAllReferences();

        return response()->json([
            'success' => true,
            'total_references' => count($references),
            'references' => $references,
        ]);
    });


    /*
    |--------------------------------------------------------------------------
    | TEST ENREGISTREMENT VISAGE
    |--------------------------------------------------------------------------
    */

    Route::get('/test-enroll-face/{user}', function (User $user) {

        // ---------------------------------------------------------
        // 1. Vérifier que l'employé possède une vraie photo
        // ---------------------------------------------------------

        if (!$user->photo || $user->photo === 'default.png') {
            return response()->json([
                'success' => false,
                'message' => 'Cet employé ne possède pas de photo de référence.'
            ], 400);
        }


        // ---------------------------------------------------------
        // 2. Chemin de la photo dans Laravel
        // ---------------------------------------------------------

        $imagePath = storage_path(
            'app/public/' . $user->photo
        );

        if (!file_exists($imagePath)) {
            return response()->json([
                'success' => false,
                'message' => 'Photo introuvable.',
                'path' => $imagePath
            ], 404);
        }


        // ---------------------------------------------------------
        // 3. Chemins de notre environnement Python
        // ---------------------------------------------------------

        $python = 'C:\xampp\htdocs\secureaccess-face\venv\Scripts\python.exe';

        $script = 'C:\xampp\htdocs\secureaccess-face\recognize.py';


        // ---------------------------------------------------------
        // 4. Lancer Python
        // ---------------------------------------------------------

        $result = Process::run(
            '"' . $python . '" "' . $script . '" "' . $imagePath . '"'
        );


        // ---------------------------------------------------------
        // 5. Vérifier si Python a réussi
        // ---------------------------------------------------------

        if ($result->failed()) {

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l’exécution de Python.',
                'error' => $result->errorOutput(),
                'output' => $result->output(),
            ], 500);
        }


        // ---------------------------------------------------------
        // 6. Récupérer le JSON produit par recognize.py
        // ---------------------------------------------------------

        $data = json_decode(
            $result->output(),
            true,
            512,
            JSON_INVALID_UTF8_SUBSTITUTE
        );


        if (!is_array($data) || !($data['success'] ?? false)) {

            return response()->json([
                'success' => false,
                'message' => 'Python n’a pas retourné une empreinte valide.',
                'python_output' => mb_convert_encoding(
                    $result->output(),
                    'UTF-8',
                    'UTF-8'
                ),
                'exit_code' => $result->exitCode(),
            ], 500);
        }


        // ---------------------------------------------------------
        // 7. Vérifier les 128 valeurs
        // ---------------------------------------------------------

        $feature = $data['feature'] ?? null;

        if (!is_array($feature) || count($feature) !== 128) {

            return response()->json([
                'success' => false,
                'message' => 'L’empreinte reçue ne contient pas exactement 128 valeurs.',
                'nombre_valeurs' => is_array($feature) ? count($feature) : 0,
            ], 500);
        }


        // ---------------------------------------------------------
        // 8. Enregistrer l'empreinte comme référence numéro 1
        // ---------------------------------------------------------

        $faceEncoding = FaceEncoding::updateOrCreate(
            [
                'user_id' => $user->id,
                'reference_number' => 1,
            ],
            [
                'encoding' => json_encode(
                    array_map('floatval', $feature)
                ),
            ]
        );


        // ---------------------------------------------------------
        // 9. Réponse finale
        // ---------------------------------------------------------

        return response()->json([
            'success' => true,
            'message' => 'Empreinte faciale enregistrée avec succès.',
            'user_id' => $user->id,
            'nom' => $user->nom,
            'prenom' => $user->prenom,
            'reference_number' => 1,
            'nombre_valeurs' => count($feature),
            'face_confidence' => $data['face_confidence'] ?? null,
            'face_encoding_id' => $faceEncoding->id,
        ]);
    });


    /*
    |--------------------------------------------------------------------------
    | TEST RECONNAISSANCE FACIALE
    |--------------------------------------------------------------------------
    */

    Route::get('/test-recognition', function (FaceRecognitionService $faceService) {

        // 1. Récupérer une photo fraîche depuis l'ESP32-CAM
        $response = Http::timeout(10)->get(
            'http://192.168.1.156/capture'
        );


        if (!$response->successful()) {

            return response()->json([
                'success' => false,
                'message' => 'Impossible de récupérer une photo depuis l’ESP32-CAM.'
            ], 500);
        }


        // 2. Sauvegarder temporairement la nouvelle capture
        $filename = 'test-recognition-' . time() . '.jpg';

        Storage::disk('local')->put(
            'face-tests/' . $filename,
            $response->body()
        );


        $imagePath = storage_path(
            'app/private/face-tests/' . $filename
        );


        // 3. Récupérer les empreintes de référence
        $references = $faceService->getAllReferences();


        if (empty($references)) {

            return response()->json([
                'success' => false,
                'message' => 'Aucune empreinte faciale de référence trouvée.'
            ], 400);
        }


        // 4. Créer temporairement le fichier JSON des références
        $referencesFile = storage_path(
            'app/private/face-tests/references.json'
        );


        file_put_contents(
            $referencesFile,
            json_encode($references)
        );


        // 5. Lancer Python
        $python = 'C:\xampp\htdocs\secureaccess-face\venv\Scripts\python.exe';

        $script = 'C:\xampp\htdocs\secureaccess-face\reconnaissance.py';


        $result = Process::run(
            '"' . $python . '" "' .
            $script . '" "' .
            $imagePath . '" "' .
            $referencesFile . '"'
        );


        // 6. Vérifier l'exécution Python
        if ($result->failed()) {

            $error = mb_convert_encoding(
                $result->errorOutput(),
                'UTF-8',
                'UTF-8'
            );

            $output = mb_convert_encoding(
                $result->output(),
                'UTF-8',
                'UTF-8'
            );

            return response()->json([
                'success' => false,
                'message' => 'Erreur pendant la reconnaissance faciale.',
                'error' => $error,
                'output' => $output,
                'exit_code' => $result->exitCode(),
            ], 500, [], JSON_INVALID_UTF8_SUBSTITUTE);
        }


        // 7. Décoder le résultat Python
        $data = json_decode(
            $result->output(),
            true,
            512,
            JSON_INVALID_UTF8_SUBSTITUTE
        );


        if (!is_array($data)) {

            return response()->json([
                'success' => false,
                'message' => 'Réponse Python invalide.',
                'python_output' => $result->output(),
            ], 500);
        }


        // 8. Si le visage est reconnu,
        //    créer automatiquement le pointage

        $pointage = null;


        if (
            ($data['recognized'] ?? false) === true &&
            !empty($data['user_id'])
        ) {

            $user = User::find($data['user_id']);

            if ($user) {

                $pointageResponse = app(PointageController::class)
                    ->creerPointage($user);

                $pointage = $pointageResponse->getData(true);
            }
        }


        // 9. Retourner le résultat
        return response()->json([
            'success' => $data['success'] ?? false,
            'recognized' => $data['recognized'] ?? false,
            'user_id' => $data['user_id'] ?? null,
            'score' => $data['score'] ?? null,
            'threshold' => $data['threshold'] ?? null,
            'reference' => $data['reference'] ?? null,
            'face_confidence' => $data['face_confidence'] ?? null,
            'message' => $data['message'] ?? null,

            // Résultat du pointage automatique
            'pointage' => $pointage,

            // Uniquement pour le test
            'capture' => $filename,
        ]);
    });


    /*
    |--------------------------------------------------------------------------
    | PHOTO D'UNE FRAUDE
    |--------------------------------------------------------------------------
    */

    Route::get('/fraud-photo/{filename}', function ($filename) {

        // Sécurité : empêcher l'accès à un autre dossier
        $filename = basename($filename);

        $path = 'face-tests/' . $filename;


        if (!Storage::disk('local')->exists($path)) {
            abort(404);
        }


        return Response::file(
            Storage::disk('local')->path($path)
        );

    })->name('fraud.photo');


    /*
    |--------------------------------------------------------------------------
    | RECONNAISSANCE AUTOMATIQUE
    |--------------------------------------------------------------------------
    */

    Route::get('/test-auto-recognition', function (AutomaticRecognitionService $service) {

        return response()->json(
            $service->process()
        );

    });

    // pour les parametres du compte administrateur
    Route::get('/parametres', [SettingsController::class, 'index'])
    ->middleware('auth')
    ->name('settings.index');

    Route::put('/parametres/profil', [SettingsController::class, 'updateProfile'])
    ->middleware('auth')
    ->name('settings.profile.update');

    Route::put('/parametres/mot-de-passe', [SettingsController::class, 'updatePassword'])
    ->middleware('auth')
    ->name('settings.password.update');

    // verify-face
    Route::post(
    '/esp32/verify-face',
    [BadgeController::class, 'verifyFace']
);

});