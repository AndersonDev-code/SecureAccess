<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Afficher la page de connexion.
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Traiter la connexion.
     */

public function login(Request $request)
{
    /*
    |--------------------------------------------------------------------------
    | VALIDATION
    |--------------------------------------------------------------------------
    */

    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required', 'string'],
    ], [
        'email.required' => 'Veuillez saisir votre adresse email.',
        'email.email' => 'Veuillez saisir une adresse email valide.',
        'password.required' => 'Veuillez saisir votre mot de passe.',
    ]);


    /*
    |--------------------------------------------------------------------------
    | 1. TEST DE LA CONNEXION MYSQL
    |--------------------------------------------------------------------------
    */

    try {

        $connection = \DB::connection();

        // Force réellement la connexion à MySQL.
        $connection->getPdo();

        // Nom exact de la base utilisée par Laravel.
        $databaseName = $connection->getDatabaseName();

    } catch (\Throwable $e) {

        return back()
            ->withInput($request->only('email'))
            ->withErrors([
                'email' => '❌ CONNEXION MYSQL IMPOSSIBLE : '
                    . $e->getMessage(),
            ]);
    }


    /*
    |--------------------------------------------------------------------------
    | 2. IDENTIFICATION DU SERVEUR MYSQL
    |--------------------------------------------------------------------------
    */

    try {

        $serverInfo = \DB::selectOne("
            SELECT
                DATABASE() AS database_name,
                @@hostname AS mysql_host,
                VERSION() AS mysql_version
        ");

    } catch (\Throwable $e) {

        return back()
            ->withInput($request->only('email'))
            ->withErrors([
                'email' => '❌ Connexion MySQL établie, mais impossible de lire les informations du serveur : '
                    . $e->getMessage(),
            ]);
    }


    /*
    |--------------------------------------------------------------------------
    | 3. VÉRIFICATION DE LA TABLE USERS
    |--------------------------------------------------------------------------
    */

    try {

        $usersCount = \DB::table('users')->count();

    } catch (\Throwable $e) {

        return back()
            ->withInput($request->only('email'))
            ->withErrors([
                'email' => '❌ LA BASE EST ACCESSIBLE MAIS LA TABLE "users" EST INACCESSIBLE : '
                    . $e->getMessage(),
            ]);
    }


    /*
    |--------------------------------------------------------------------------
    | 4. RECHERCHE DE L'UTILISATEUR PAR EMAIL
    |--------------------------------------------------------------------------
    */

    try {

        $user = \DB::table('users')
            ->where('email', $credentials['email'])
            ->first();

    } catch (\Throwable $e) {

        return back()
            ->withInput($request->only('email'))
            ->withErrors([
                'email' => '❌ ERREUR LORS DE LA RECHERCHE DE L’EMAIL : '
                    . $e->getMessage(),
            ]);
    }


    /*
    |--------------------------------------------------------------------------
    | INFORMATIONS DE DIAGNOSTIC
    |--------------------------------------------------------------------------
    */

    if (!$user) {

        return back()
            ->withInput($request->only('email'))
            ->withErrors([
                'email' =>
                    '❌ BASE ACCESSIBLE MAIS UTILISATEUR ABSENT. '
                    . 'Base utilisée : [' . $databaseName . ']. '
                    . 'Nombre total d’utilisateurs : ' . $usersCount . '. '
                    . 'Serveur MySQL : ' . ($serverInfo->mysql_host ?? 'inconnu') . '.',
            ]);
    }


    /*
    |--------------------------------------------------------------------------
    | 5. VÉRIFICATION DU MOT DE PASSE
    |--------------------------------------------------------------------------
    */

    try {

        $passwordCorrect = \Hash::check(
            $credentials['password'],
            $user->password
        );

    } catch (\Throwable $e) {

        return back()
            ->withInput($request->only('email'))
            ->withErrors([
                'email' =>
                    '❌ UTILISATEUR TROUVÉ, MAIS ERREUR LORS DE LA VÉRIFICATION DU MOT DE PASSE : '
                    . $e->getMessage(),
            ]);
    }


    /*
    |--------------------------------------------------------------------------
    | MOT DE PASSE INCORRECT
    |--------------------------------------------------------------------------
    */

    if (!$passwordCorrect) {

        return back()
            ->withInput($request->only('email'))
            ->withErrors([
                'email' =>
                    '❌ UTILISATEUR TROUVÉ DANS LA BASE, MAIS MOT DE PASSE INCORRECT. '
                    . 'Base : [' . $databaseName . ']',
            ]);
    }


    /*
    |--------------------------------------------------------------------------
    | 6. VÉRIFICATION DU RÔLE
    |--------------------------------------------------------------------------
    */

    $role = strtolower(trim((string) ($user->role ?? '')));

    if (!in_array($role, ['admin', 'administrateur'])) {

        return back()
            ->withInput($request->only('email'))
            ->withErrors([
                'email' =>
                    '❌ EMAIL ET MOT DE PASSE CORRECTS, MAIS RÔLE ADMINISTRATEUR ABSENT. '
                    . 'Rôle actuel : [' . ($user->role ?? 'NULL') . ']',
            ]);
    }


    /*
    |--------------------------------------------------------------------------
    | 7. AUTHENTIFICATION LARAVEL
    |--------------------------------------------------------------------------
    */

    try {

        if (!Auth::attempt(
            $credentials,
            $request->boolean('remember')
        )) {

            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'email' =>
                        '❌ IDENTIFIANTS VALIDES DANS MYSQL, MAIS AUTH::ATTEMPT() A ÉCHOUÉ.',
                ]);
        }

    } catch (\Throwable $e) {

        return back()
            ->withInput($request->only('email'))
            ->withErrors([
                'email' =>
                    '❌ ERREUR LARAVEL PENDANT AUTH::ATTEMPT() : '
                    . $e->getMessage(),
            ]);
    }


    /*
    |--------------------------------------------------------------------------
    | 8. RÉGÉNÉRATION DE SESSION
    |--------------------------------------------------------------------------
    */

    $request->session()->regenerate();


    /*
    |--------------------------------------------------------------------------
    | 9. CONNEXION RÉUSSIE
    |--------------------------------------------------------------------------
    */

    return redirect()
        ->intended(route('dashboard'))
        ->with('success', '✅ Connexion administrateur réussie.');
}



    /**
     * Déconnexion.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with('success', 'Vous avez été déconnecté.');
    }
}