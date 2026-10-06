<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Schedule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class EmployeesControler extends Controller
{

    // liste des employes

    public function index()
    {
        $employees = User::latest()->paginate(10);

        return  view('employees.index', compact('employees'));
    }

    //formulaire d'ajout d'employe
    public function create()
    {
        return view('employees.create');
    }

    //enregistrement d'un employe
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required',
            'prenom' => 'required',
            'matricule' => 'required|unique:users',

            'email' => 'nullable|email',
            'telephone' => 'nullable',

            'service' => 'required',
            'rfid_uid' => 'required|string|max:100|unique:users',
            'rfid_scan_token' => 'required|string',
            'heure_entree'=> 'required',
            'heure_sortie'=> 'required',

            'tolerance'=> 'required|integer',
            'face_photo' => 'nullable|string',
        ]);



        /*
|--------------------------------------------------------------------------
| VÉRIFICATION DU SCAN RFID
|--------------------------------------------------------------------------
|
| On vérifie que l'UID présent dans le formulaire
| provient réellement d'un scan récent.
|
|--------------------------------------------------------------------------
*/

$registrationData = Cache::get(
    'rfid_registration_' .
    $request->rfid_scan_token
);


/*
|--------------------------------------------------------------------------
| Aucun scan valide
|--------------------------------------------------------------------------
*/

if (
    !$registrationData ||
    empty($registrationData['uid'])
) {

    return back()
        ->withErrors([
            'rfid_uid' =>
                'Veuillez scanner le badge RFID avant d’enregistrer l’employé.'
        ])
        ->withInput();
}


/*
|--------------------------------------------------------------------------
| UID réellement scanné
|--------------------------------------------------------------------------
*/

$scannedUid = strtoupper(
    preg_replace(
        '/[^A-F0-9]/i',
        '',
        $registrationData['uid']
    )
);


/*
|--------------------------------------------------------------------------
| UID reçu dans le formulaire
|--------------------------------------------------------------------------
*/

$formUid = strtoupper(
    preg_replace(
        '/[^A-F0-9]/i',
        '',
        $request->rfid_uid
    )
);


/*
|--------------------------------------------------------------------------
| Vérifier que les deux correspondent
|--------------------------------------------------------------------------
*/

if ($scannedUid !== $formUid) {

    return back()
        ->withErrors([
            'rfid_uid' =>
                'L’UID saisi ne correspond pas au badge réellement scanné.'
        ])
        ->withInput();
}


/*
|--------------------------------------------------------------------------
| Vérifier une dernière fois que le badge est libre
|--------------------------------------------------------------------------
*/

if (
    User::whereRaw(
        'UPPER(rfid_uid) = ?',
        [$scannedUid]
    )->exists()
) {

    return back()
        ->withErrors([
            'rfid_uid' =>
                'Ce badge RFID est déjà associé à un employé.'
        ])
        ->withInput();
}


/*
|--------------------------------------------------------------------------
| Utiliser l'UID normalisé
|--------------------------------------------------------------------------
*/

$request->merge([
    'rfid_uid' => $scannedUid
]);

        DB::transaction(function () use ($request) {
            
            $photo = 'default.png';
            if($request->filled('face_photo')){
                $image = $request->input('face_photo');

                // verification du format base64
                if(preg_match('/^data:image\/(\w+);base64,/', $image, $type)){

                    // retirer le prefixe : data:image/jpeg;base64 
                    $image = substr($image, strpos($image, ',') + 1);

                    // decodage base64
                    $image = base64_decode($image);

                    // generation du nom de fichier unique
                    $filename = 'employees/' .Str::uuid() . '.jpg';

                    // sauvegarde de l'image dans le dossier public/storage/employees
                    \Storage::disk('public')->put($filename, $image);

                    // chemin enregistre dans la bd
                    $photo = $filename;
                }
            }

            $user = User::create([
                'nom' => $request->nom,
                'prenom' => $request->prenom,
                'matricule' => $request->matricule,
                'email' => $request->email,
                'telephone' => $request->telephone,
                'service' => $request->service,
                'rfid_uid' => $request->rfid_uid,

                'photo'=>$photo,
                'face_encoding'=>null,
                'is_active'=>true,
                'password' => Hash::make('password'),
            ]);
 
            Schedule::create([
                'heure_entree' => $request->heure_entree,
                'heure_sortie' => $request->heure_sortie,
                'tolerance' => $request->tolerance,
                'user_id' => $user->id,
            ]);
        });

        Cache::forget(
            'rfid_registration_' .
            $request->rfid_scan_token
        );

        return redirect()
               ->route('employees.index')
               ->with('success', 'Employé ajouté avec succès.');
    }

    // modifieer un employe
    public function edit(User $employee)
    {
        // Récupérer l'horaire associé à l'employé
        $schedule = Schedule::where('user_id', $employee->id)->first();

        return view('employees.edit', compact('employee','schedule'));
    }

    public function show(User $employee)
    {
        return view('employees.show', compact('employee'));
    }

    // supprimer un employe
    public function destroy(User $employee)
   {
    $employee->update([
        'is_active' => false,
    ]);
    return back()->with(
        'success',
        'Employé désactivé avec succès.'
    );
   } 


  public function update(Request $request, User $employee)
{
    /*
    =====================================================
    VALIDATION
    =====================================================
    */

    $request->validate([

        'nom' => 'required|string|max:100',

        'prenom' => 'required|string|max:100',

        'matricule' => 'required|string|max:50|unique:users,matricule,' . $employee->id,

        'email' => 'nullable|email|max:255|unique:users,email,' . $employee->id,

        'telephone' => 'nullable|string|max:30',

        'service' => 'required|string|max:100',

        'rfid_uid' => 'nullable|string|max:100|unique:users,rfid_uid,' . $employee->id,

        'heure_entree' => 'required',

        'heure_sortie' => 'required',

        'tolerance' => 'required|integer|in:5,10,15,30',

        'face_photo' => 'nullable|string',

    ]);


    /*
    =====================================================
    TRANSACTION
    =====================================================

    On modifie users ET schedules.
    Si une opération échoue, Laravel annule tout.
    */

    DB::transaction(function () use ($request, $employee) {


        /*
        =================================================
        DONNÉES DE L'EMPLOYÉ
        =================================================
        */

        $employeeData = [

            'nom' => $request->nom,

            'prenom' => $request->prenom,

            'matricule' => $request->matricule,

            'email' => $request->email,

            'telephone' => $request->telephone,

            'service' => $request->service,

            'rfid_uid' => $request->rfid_uid,

        ];


        /*
        =================================================
        NOUVELLE CAPTURE FACIALE
        =================================================

        On utilise exactement le même système
        que lors de l'ajout.
        */

        if ($request->filled('face_photo')) {

            $image = $request->input('face_photo');


            /*
            Vérification du format Base64
            */

            if (preg_match(
                '/^data:image\/(\w+);base64,/',
                $image,
                $type
            )) {


                /*
                Retirer :
                data:image/jpeg;base64,
                */

                $image = substr(
                    $image,
                    strpos($image, ',') + 1
                );


                /*
                Décodage Base64
                */

                $image = base64_decode($image);


                /*
                Vérification du décodage
                */

                if ($image !== false) {


                    /*
                    Génération d'un nom unique
                    */

                    $filename =
                        'employees/' .
                        Str::uuid() .
                        '.jpg';


                    /*
                    Sauvegarde de la nouvelle photo
                    */

                    \Storage::disk('public')
                        ->put($filename, $image);


                    /*
                    Supprimer l'ancienne photo
                    si elle existe
                    */

                    if (
                        $employee->photo &&
                        $employee->photo !== 'default.png'
                    ) {

                        \Storage::disk('public')
                            ->delete($employee->photo);
                    }


                    /*
                    Nouveau chemin photo
                    */

                    $employeeData['photo'] =
                        $filename;
                }
            }
        }


        /*
        =================================================
        MISE À JOUR DE L'EMPLOYÉ
        =================================================
        */

        $employee->update($employeeData);


        /*
        =================================================
        MISE À JOUR DE L'HORAIRE
        =================================================
        */

        Schedule::updateOrCreate(

            [
                'user_id' => $employee->id
            ],

            [
                'heure_entree' =>
                    $request->heure_entree,

                'heure_sortie' =>
                    $request->heure_sortie,

                'tolerance' =>
                    $request->tolerance,
            ]

        );

    });


    /*
    =====================================================
    RETOUR
    =====================================================
    */

    return redirect()
        ->route('employees.index')
        ->with(
            'success',
            'Les informations de l’employé ont été mises à jour avec succès.'
        );
}

}