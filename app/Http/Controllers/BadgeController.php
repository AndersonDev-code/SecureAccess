<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\FaceEncoding;
use App\Http\Controllers\PointageController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class BadgeController extends Controller
{
    

/**
 * ============================================================
 * DÉMARRER UN SCAN RFID POUR L'ENREGISTREMENT
 * ============================================================
 */
public function startRegistrationScan()
{
    $token = (string) Str::uuid();

    Cache::put(
        'rfid_registration_active_token',
        $token,
        now()->addSeconds(60)
    );

    Cache::put(
        'rfid_registration_' . $token,
        [
            'uid' => null,
        ],
        now()->addSeconds(120)
    );

    return response()->json([
        'success' => true,
        'token' => $token,
        'message' => 'Présentez votre badge RFID.'
    ]);
}


/**
 * ============================================================
 * RÉCUPÉRER LE BADGE SCANNÉ
 * ============================================================
 */
public function getRegistrationScan(Request $request)
{
    $request->validate([
        'token' => 'required|string'
    ]);

    $data = Cache::get(
        'rfid_registration_' . $request->token
    );

    if (!$data || empty($data['uid'])) {

        return response()->json([
            'success' => true,
            'scanned' => false,
        ]);
    }

    $uid = strtoupper(
        preg_replace('/[^A-F0-9]/i', '', $data['uid'])
    );

    $existingEmployee = User::whereRaw(
        'UPPER(rfid_uid) = ?',
        [$uid]
    )->first();

    if ($existingEmployee) {

        return response()->json([
            'success' => true,
            'scanned' => true,
            'already_used' => true,
            'uid' => $uid,

            'employee' => [
                'nom' => $existingEmployee->nom,
                'prenom' => $existingEmployee->prenom,
                'matricule' => $existingEmployee->matricule,
            ],

            'message' =>
                'Ce badge est déjà associé à un employé.'
        ]);
    }

    return response()->json([
        'success' => true,
        'scanned' => true,
        'already_used' => false,
        'uid' => $uid,
        'message' => 'Badge RFID disponible.'
    ]);
}


    /**
     * ============================================================
     * SCAN RFID + RECONNAISSANCE FACIALE
     * ============================================================
     *
     * Flux :
     *
     * RC522
     *   ↓
     * ESP32-CAM
     *   ↓
     * Laravel
     *   ↓
     * Recherche employé
     *   ↓
     * Capture visage
     *   ↓
     * YuNet + SFace
     *   ↓
     * Double authentification
     *
     */
    public function storeScan(Request $request)
    {
        // ========================================================
        // 1. RÉCUPÉRATION DU UID
        // ========================================================

        $uid = $request->input('uid');

        if (!$uid) {

            return response()->json([
                'status' => 'ERROR',
                'message' => 'UID manquant'
            ], 400);
        }


        // ========================================================
        // 2. NETTOYAGE DU UID
        // ========================================================

        // Exemple :
        // RFID:41332C55
        // devient :
        // 41332C55

        $uid = strtoupper(trim($uid));

        $uid = preg_replace(
            '/^RFID:/i',
            '',
            $uid
        );

        // Garder uniquement les caractères hexadécimaux
        $uid = preg_replace(
            '/[^A-F0-9]/',
            '',
            $uid
        );


        if (!$uid) {

            return response()->json([
                'status' => 'ERROR',
                'message' => 'UID invalide'
            ], 400);
        }


        Log::info(
            "UID reçu depuis l'ESP32 : " . $uid
        );


        /*
|--------------------------------------------------------------------------
| MODE ENREGISTREMENT RFID
|--------------------------------------------------------------------------
*/

$registrationToken = Cache::get(
    'rfid_registration_active_token'
);

if ($registrationToken) {

    Cache::put(
        'rfid_registration_' . $registrationToken,
        [
            'uid' => $uid,
        ],
        now()->addSeconds(120)
    );

    Cache::forget(
        'rfid_registration_active_token'
    );

    Log::info(
        'UID RFID capturé pour enregistrement : ' . $uid
    );

    return response()->json([
        'status' => 'REGISTRATION_UID',
        'message' => 'Badge capturé avec succès.',
        'uid' => $uid
    ]);
}

$registrationToken = Cache::get(
    'rfid_registration_active_token'
);


if ($registrationToken) {

    Cache::put(
        'rfid_registration_' . $registrationToken,
        [
            'uid' => $uid,
        ],
        now()->addSeconds(120)
    );

    Cache::forget(
        'rfid_registration_active_token'
    );

      Log::info(
        'UID RFID capturé pour enregistrement : ' . $uid
        );

    return response()->json([
        'status' => 'REGISTRATION_UID',
        'message' => 'Badge capturé avec succès.',
        'uid' => $uid
    ]);
}

        // ========================================================
        // 3. RECHERCHE DE L'EMPLOYÉ
        // ========================================================

        $employee = User::whereRaw(
            'UPPER(rfid_uid) = ?',
            [$uid]
        )->first();


        // ========================================================
        // BADGE INCONNU
        // ========================================================

        if (!$employee) {

            Log::warning(
                "Badge inconnu : " . $uid
            );

            return response()->json([
                'status' => 'ERROR',
                'message' => 'Badge non reconnu',
                'uid' => $uid
            ], 404);
        }


        // ========================================================
        // 4. EMPLOYÉ RECONNU
        // ========================================================

        Log::info(
            "Badge reconnu : "
            . $employee->nom
            . ' '
            . $employee->prenom
            . ' - '
            . $employee->matricule
        );


        // ========================================================
        // 5. MÉMORISER L'EMPLOYÉ EN ATTENTE
        // ========================================================

        cache()->put(
            'pending_face_employee',
            $employee->id,
            now()->addMinutes(2)
        );

        cache()->put(
            'latest_scanned_badge',
            $uid,
            now()->addMinutes(2)
        );


        Log::info(
            "Début vérification faciale pour l'employé ID : "
            . $employee->id
        );


        // ========================================================
        // 6. RÉCUPÉRER LES RÉFÉRENCES FACIALES
        // ========================================================

        $faceEncodings = FaceEncoding::where(
            'user_id',
            $employee->id
        )->get();


        $references = [];


        foreach ($faceEncodings as $faceEncoding) {

            try {

                $vector = json_decode(
                    $faceEncoding->encoding,
                    true
                );


                // Protection :
                // SFace doit avoir exactement 128 valeurs

                if (
                    !is_array($vector) ||
                    count($vector) !== 128
                ) {
                    continue;
                }


                $references[] = [

                    'user_id' =>
                        (string) $faceEncoding->user_id,

                    'reference_number' =>
                        $faceEncoding->reference_number,

                    'encoding' =>
                        array_map(
                            'floatval',
                            $vector
                        ),
                ];

            } catch (\Throwable $e) {

                Log::warning(
                    "Référence faciale ignorée : "
                    . $e->getMessage()
                );

                continue;
            }
        }


        // ========================================================
        // AUCUNE RÉFÉRENCE FACIALE
        // ========================================================

        if (empty($references)) {

            Log::warning(
                "Aucune référence faciale pour l'employé ID : "
                . $employee->id
            );


            cache()->forget(
                'pending_face_employee'
            );


            return response()->json([
                'status' => 'ERROR',
                'message' =>
                    'Aucune référence faciale enregistrée pour cet employé.',
                'employee_id' => $employee->id
            ], 400);
        }


        // ========================================================
        // 7. CAPTURE DEPUIS L'ESP32-CAM
        // ========================================================

        $captureUrl =
            'http://192.168.1.156/capture';


        try {

            sleep(3); // Attendre 3 secondes pour que l'ESP32-CAM soit prêt

            $captureResponse = Http::timeout(10)
                ->get($captureUrl);

        } catch (\Throwable $e) {

            Log::error(
                "Impossible de contacter l'ESP32-CAM : "
                . $e->getMessage()
            );


            return response()->json([
                'status' => 'ERROR',
                'message' =>
                    'ESP32-CAM inaccessible.',
                'error' =>
                    $e->getMessage()
            ], 500);
        }


        // ========================================================
        // 8. VÉRIFICATION DE LA CAPTURE
        // ========================================================

        if (!$captureResponse->successful()) {

            Log::error(
                "Erreur capture ESP32. Code HTTP : "
                . $captureResponse->status()
            );


            return response()->json([
                'status' => 'ERROR',
                'message' =>
                    'Impossible de capturer l’image depuis l’ESP32-CAM.',
                'http_code' =>
                    $captureResponse->status()
            ], 500);
        }


        // Vérification simple :
        // la réponse ne doit pas être vide

        if (
            empty($captureResponse->body())
        ) {

            Log::error(
                "L'ESP32-CAM a retourné une image vide."
            );


            return response()->json([
                'status' => 'ERROR',
                'message' =>
                    'La capture de l’ESP32-CAM est vide.'
            ], 500);
        }


        // ========================================================
        // 9. DOSSIER DES CAPTURES TEMPORAIRES
        // ========================================================

        $captureDirectory =
            storage_path('app/face_captures');


        if (!is_dir($captureDirectory)) {

            mkdir(
                $captureDirectory,
                0777,
                true
            );
        }


        // ========================================================
        // 10. CHEMIN DE LA PHOTO TEMPORAIRE
        // ========================================================

        $imagePath =
            $captureDirectory
            . DIRECTORY_SEPARATOR
            . 'capture_'
            . $employee->id
            . '_'
            . time()
            . '.jpg';


        file_put_contents(
            $imagePath,
            $captureResponse->body()
        );


        Log::info(
            "Capture ESP32 sauvegardée : "
            . $imagePath
        );


        // ========================================================
        // 11. JSON TEMPORAIRE DES RÉFÉRENCES
        // ========================================================
        //
        // IMPORTANT :
        // Tu ne gères PAS ce JSON.
        //
        // Laravel le crée automatiquement à partir
        // de la table face_encodings.
        //
        // ========================================================

        $referenceDirectory =
            storage_path('app/face_references');


        if (!is_dir($referenceDirectory)) {

            mkdir(
                $referenceDirectory,
                0777,
                true
            );
        }


        $referencesPath =
            $referenceDirectory
            . DIRECTORY_SEPARATOR
            . 'references_'
            . $employee->id
            . '_'
            . time()
            . '.json';


        file_put_contents(
            $referencesPath,
            json_encode(
                $references,
                JSON_UNESCAPED_UNICODE
            )
        );


        // ========================================================
        // 12. PYTHON DU VENV
        // ========================================================
        //
        // C'EST ICI QUE TON ANCIEN CODE AVAIT LE PROBLÈME.
        //
        // On n'utilise PLUS :
        //
        // $pythonBinary = 'py';
        //
        // On utilise exactement le Python qui fonctionne
        // dans ton test manuel.
        //
        // ========================================================

        $python =
            'C:/xampp/htdocs/secureaccess-face/venv/Scripts/python.exe';


        $script =
            'C:/xampp/htdocs/secureaccess-face/reconnaissance.py';


        // ========================================================
        // 13. VÉRIFICATION DES FICHIERS
        // ========================================================

        if (!file_exists($python)) {

            Log::error(
                "Python introuvable : "
                . $python
            );


            $this->deleteTemporaryFaceFiles(
                $imagePath,
                $referencesPath
            );


            return response()->json([
                'status' => 'ERROR',
                'message' =>
                    'Python introuvable.',
                'path' =>
                    $python
            ], 500);
        }


        if (!file_exists($script)) {

            Log::error(
                "Script introuvable : "
                . $script
            );


            $this->deleteTemporaryFaceFiles(
                $imagePath,
                $referencesPath
            );


            return response()->json([
                'status' => 'ERROR',
                'message' =>
                    'Script reconnaissance.py introuvable.',
                'path' =>
                    $script
            ], 500);
        }


        // ========================================================
        // 14. LANCER reconnaissance.py
        // ========================================================

        try {

            $result = Process::timeout(30)
                ->run([

                    $python,

                    // Ignore les variables PYTHON*
                    '-E',

                    $script,

                    // Argument 1 :
                    // image capturée

                    $imagePath,

                    // Argument 2 :
                    // références temporaires

                    $referencesPath,

                    // Argument 3 :
                    // ID de l'employé RFID

                    (string) $employee->id
                ]);

        } catch (\Throwable $e) {

            Log::error(
                "Erreur lancement reconnaissance.py : "
                . $e->getMessage()
            );


            $this->deleteTemporaryFaceFiles(
                $imagePath,
                $referencesPath
            );


            return response()->json([
                'status' => 'ERROR',
                'message' =>
                    'Impossible de lancer la reconnaissance faciale.',
                'error' =>
                    $e->getMessage()
            ], 500);
        }


        // ========================================================
        // 15. PYTHON A ÉCHOUÉ
        // ========================================================

        if ($result->failed()) {

            Log::error(
                "Python a retourné une erreur."
            );


            Log::error(
                "PYTHON STDOUT : "
                . $result->output()
            );


            Log::error(
                "PYTHON STDERR : "
                . $result->errorOutput()
            );


            $this->deleteTemporaryFaceFiles(
                $imagePath,
                $referencesPath
            );


            return response()->json([
                'status' => 'ERROR',
                'message' =>
                    'Erreur durant la reconnaissance faciale.',
                'python_output' =>
                    $result->output(),
                'python_error' =>
                    $result->errorOutput()
            ], 500);
        }


        // ========================================================
        // 16. LIRE LE JSON DE reconnaissance.py
        // ========================================================

        $faceResult = json_decode(
            trim($result->output()),
            true,
            512,
            JSON_INVALID_UTF8_SUBSTITUTE
        );


        if (!is_array($faceResult)) {

            Log::error(
                "Réponse Python invalide : "
                . $result->output()
            );


            $this->deleteTemporaryFaceFiles(
                $imagePath,
                $referencesPath
            );


            return response()->json([
                'status' => 'ERROR',
                'message' =>
                    'Réponse JSON invalide provenant de Python.',
                'python_output' =>
                    $result->output()
            ], 500);
        }


        // ========================================================
        // 17. SUPPRESSION DES FICHIERS TEMPORAIRES
        // ========================================================
        // 
        // $this->deleteTemporaryFaceFiles(
            // $imagePath,
            // $referencesPath
        // ); -->s


        // ========================================================
// 17. DIAGNOSTIC
// ========================================================
//
// On conserve temporairement la capture afin de pouvoir
// vérifier visuellement ce que l'ESP32-CAM a réellement envoyé.
//
// ========================================================

Log::info(
    "Taille capture : " .
    filesize($imagePath) .
    " octets"
);


// Vérification supplémentaire du fichier image
$imageInfo = @getimagesize($imagePath);

if ($imageInfo !== false) {

    Log::info(
        "Image valide : " .
        $imageInfo[0] .
        "x" .
        $imageInfo[1]
    );

} else {

    Log::error(
        "Le fichier capturé n'est pas reconnu comme une image."
    );
}


// Afficher exactement la réponse Python
Log::info(
    "Résultat reconnaissance.py : " .
    json_encode(
        $faceResult,
        JSON_UNESCAPED_UNICODE
    )
);

        // ========================================================
        // 18. RECONNAISSANCE REFUSÉE
        // ========================================================

        if (
            !isset($faceResult['recognized']) ||
            $faceResult['recognized'] !== true
        ) {

            Log::warning(
                "Reconnaissance faciale échouée pour l'employé ID : "
                . $employee->id
            );


            cache()->forget(
                'pending_face_employee'
            );


            return response()->json([
                'status' => 'DENIED',
                'message' =>
                    'Reconnaissance faciale échouée.',

                'employee' => [

                    'id' =>
                        $employee->id,

                    'nom' =>
                        $employee->nom,

                    'prenom' =>
                        $employee->prenom,

                    'matricule' =>
                        $employee->matricule,

                ],

                'face' =>
                    $faceResult
            ], 403);
        }


        // ========================================================
        // 19. VÉRIFICATION SUPPLÉMENTAIRE
        // ========================================================
        //
        // Le Python doit retourner le même user_id
        // que celui identifié par RFID.
        //
        // Cela ajoute une protection supplémentaire.
        //
        // ========================================================

        if (
            !isset($faceResult['user_id']) ||
            (string) $faceResult['user_id']
                !== (string) $employee->id
        ) {

            Log::warning(
                "Incohérence RFID / visage pour l'employé ID : "
                . $employee->id
            );


            cache()->forget(
                'pending_face_employee'
            );


            return response()->json([
                'status' => 'DENIED',
                'message' =>
                    'Le visage ne correspond pas à l’employé identifié par le badge.',
                'employee_id' =>
                    $employee->id,
                'face' =>
                    $faceResult
            ], 403);
        }


        // ========================================================
        // 20. DOUBLE AUTHENTIFICATION VALIDÉE
        // ========================================================

        Log::info(
            "Double authentification RFID + visage réussie : "
            . $employee->nom
            . ' '
            . $employee->prenom
        );

        // ========================================================
        // 20 BIS. ENREGISTRER LE POINTAGE
        // ========================================================

        $pointageController =
            app(PointageController::class);

        $pointageResponse =
        $pointageController->creerPointage($employee);

        $pointageData =
            $pointageResponse->getData(true);

        // Fin de l'état temporaire
        cache()->forget(
            'pending_face_employee'
        );


        if (
            !isset($pointageData['success'])
        ) {

            Log::error(
                "Réponse inattendue du pointage pour l'employé ID : "
                . $employee->id
            );

            return response()->json([
                'status' => 'ERROR',
                'message' => 'Erreur lors de l’enregistrement du pointage.',
                'face' => $faceResult
            ], 500);
        }

        // ========================================================
        // 21. RÉPONSE FINALE
        // ========================================================

        return response()->json([

            'status' =>
                'SUCCESS',

            'message' =>
                'Double authentification réussie.',

            'authentication' =>
                'RFID + FACE',

            'employee' => [

                'id' =>
                    $employee->id,

                'nom' =>
                    $employee->nom,

                'prenom' =>
                    $employee->prenom,

                'matricule' =>
                    $employee->matricule,

                'service' =>
                    $employee->service,
            ],

            'face' =>
                $faceResult,
            
            'pointage'=>
                $pointageData,

            'next_step' =>
                'TERMINE'
        ]);
    }


    /**
     * ============================================================
     * SUPPRESSION DES FICHIERS TEMPORAIRES
     * ============================================================
     */
    private function deleteTemporaryFaceFiles(
        string $imagePath,
        string $referencesPath
    ): void {

        if (file_exists($imagePath)) {

            @unlink($imagePath);
        }

        if (file_exists($referencesPath)) {

            @unlink($referencesPath);
        }
    }
}