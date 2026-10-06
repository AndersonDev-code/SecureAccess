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
        | Tentative de connexion
        |--------------------------------------------------------------------------
        */

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {

            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'email' => 'Email ou mot de passe incorrect.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Régénérer la session
        |--------------------------------------------------------------------------
        */

        $request->session()->regenerate();


        /*
        |--------------------------------------------------------------------------
        | Vérification du rôle administrateur
        |--------------------------------------------------------------------------
        */

        $user = Auth::user();

        /*
         * On accepte ici les deux écritures courantes :
         * admin
         * administrateur
         */
        $role = strtolower(trim((string) $user->role));

        if (!in_array($role, ['admin', 'administrateur'])) {

            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->withErrors([
                'email' => 'Ce compte ne possède pas les droits administrateur.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Connexion réussie
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