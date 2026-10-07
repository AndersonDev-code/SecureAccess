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
    | Validation
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
    | TEST DE CONNEXION À LA BASE DE DONNÉES
    |--------------------------------------------------------------------------
    */

    try {

        // Teste réellement la connexion à la base configurée sur Render/Aiven.
        \DB::connection()->getPdo();

    } catch (\Throwable $e) {

        // IMPORTANT :
        // On affiche temporairement l'erreur réelle pour le diagnostic.
        return back()
            ->withInput($request->only('email'))
            ->withErrors([
                'email' => 'ERREUR DE CONNEXION À LA BASE DE DONNÉES : '
                    . $e->getMessage(),
            ]);
    }


    /*
    |--------------------------------------------------------------------------
    | TEST DE LA TABLE USERS
    |--------------------------------------------------------------------------
    */

    try {

        // Vérifie que Laravel peut réellement accéder à la table users.
        \DB::table('users')->limit(1)->get();

    } catch (\Throwable $e) {

        return back()
            ->withInput($request->only('email'))
            ->withErrors([
                'email' => 'CONNEXION À LA BASE OK, MAIS ERREUR SUR LA TABLE USERS : '
                    . $e->getMessage(),
            ]);
    }


    /*
    |--------------------------------------------------------------------------
    | TENTATIVE DE CONNEXION
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
                    'email' => 'BASE DE DONNÉES ACCESSIBLE. Utilisateur introuvable ou mot de passe incorrect.',
                ]);
        }

    } catch (\Throwable $e) {

        return back()
            ->withInput($request->only('email'))
            ->withErrors([
                'email' => 'ERREUR PENDANT LA REQUÊTE D’AUTHENTIFICATION : '
                    . $e->getMessage(),
            ]);
    }


    /*
    |--------------------------------------------------------------------------
    | RÉGÉNÉRER LA SESSION
    |--------------------------------------------------------------------------
    */

    $request->session()->regenerate();


    /*
    |--------------------------------------------------------------------------
    | VÉRIFICATION DU RÔLE ADMINISTRATEUR
    |--------------------------------------------------------------------------
    */

    $user = Auth::user();

    $role = strtolower(trim((string) $user->role));

    if (!in_array($role, ['admin', 'administrateur'])) {

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return back()->withErrors([
            'email' => 'CONNEXION RÉUSSIE, MAIS CE COMPTE N’A PAS LE RÔLE ADMINISTRATEUR.',
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | CONNEXION RÉUSSIE
    |--------------------------------------------------------------------------
    */

    return redirect()
        ->intended(route('dashboard'))
        ->with('success', 'Connexion administrateur réussie.');
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