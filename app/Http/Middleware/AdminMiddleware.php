<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Vérifier que l'utilisateur est administrateur.
     */
    public function handle(Request $request, Closure $next): Response
    {
        /*
        |--------------------------------------------------------------------------
        | Vérifier la connexion
        |--------------------------------------------------------------------------
        */

        if (!Auth::check()) {
            return redirect()
                ->route('login')
                ->withErrors([
                    'email' => 'Veuillez vous connecter pour accéder à cette page.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Vérifier le rôle
        |--------------------------------------------------------------------------
        */

        $user = Auth::user();

        $role = strtolower(trim((string) $user->role));

        if (!in_array($role, ['admin', 'administrateur'])) {

            abort(403, 'Accès réservé à l’administrateur.');
        }


        /*
        |--------------------------------------------------------------------------
        | Autoriser
        |--------------------------------------------------------------------------
        */

        return $next($request);
    }
}