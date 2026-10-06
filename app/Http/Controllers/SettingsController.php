<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class SettingsController extends Controller
{
    /**
     * Afficher les paramètres.
     */
    public function index(Request $request)
    {
        $admin = $request->user();

        return view('settings.index', compact('admin'));
    }


    /**
     * Modifier les informations du profil.
     */
    public function updateProfile(Request $request)
    {
        $admin = $request->user();

        $validated = $request->validate([

            'nom' => [
                'required',
                'string',
                'max:100',
            ],

            'prenom' => [
                'required',
                'string',
                'max:100',
            ],

            'matricule' => [
                'required',
                'string',
                'max:50',
                Rule::unique('users', 'matricule')->ignore($admin->id),
            ],

            'service' => [
                'nullable',
                'string',
                'max:100',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($admin->id),
            ],

            'telephone' => [
                'nullable',
                'string',
                'max:30',
            ],

        ]);


        $admin->update($validated);


        return redirect()
            ->route('settings.index')
            ->with('success', 'Les informations du profil ont été mises à jour avec succès.');
    }


    /**
     * Modifier le mot de passe.
     */
    public function updatePassword(Request $request)
    {
        $admin = $request->user();

        $request->validate([

            'current_password' => [
                'required',
                'current_password',
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],

        ]);


        $admin->update([
            'password' => Hash::make($request->password),
        ]);


        return redirect()
            ->route('settings.index')
            ->with('success', 'Le mot de passe a été modifié avec succès.');
    }
}