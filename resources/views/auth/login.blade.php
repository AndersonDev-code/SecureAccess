@extends('layouts.app')

@section('title', 'Connexion | SecureAccess')

@section('content')

<style>
    /*
    |--------------------------------------------------------------------------
    | PAGE DE CONNEXION
    |--------------------------------------------------------------------------
    */

    .secure-login-page {
        min-height: 100vh;
        padding: 32px 16px;

        background:
            radial-gradient(
                circle at top left,
                rgba(37, 99, 235, 0.08),
                transparent 35%
            ),
            radial-gradient(
                circle at bottom right,
                rgba(30, 58, 138, 0.06),
                transparent 35%
            ),
            #f5f7fb;
    }


    /*
    |--------------------------------------------------------------------------
    | CARTE PRINCIPALE
    |--------------------------------------------------------------------------
    */

    .secure-login-card {
        width: 100%;
        max-width: 1040px;

        border: 1px solid rgba(15, 23, 42, 0.06);
        border-radius: 24px;

        background: #ffffff;

        box-shadow:
            0 24px 60px rgba(15, 23, 42, 0.10),
            0 8px 24px rgba(15, 23, 42, 0.05);

        overflow: hidden;
    }


    /*
    |--------------------------------------------------------------------------
    | PANNEAU GAUCHE
    |--------------------------------------------------------------------------
    */

    .secure-login-visual {
        min-height: 610px;

        background:
            linear-gradient(
                145deg,
                #1d4ed8 0%,
                #1e40af 48%,
                #1e3a8a 100%
            );

        position: relative;
        overflow: hidden;
    }


    /*
    |--------------------------------------------------------------------------
    | FORMES DÉCORATIVES
    |--------------------------------------------------------------------------
    */

    .secure-login-visual::before {
        content: "";

        position: absolute;

        width: 260px;
        height: 260px;

        right: -110px;
        top: -110px;

        border-radius: 50%;

        background: rgba(255, 255, 255, 0.07);
    }


    .secure-login-visual::after {
        content: "";

        position: absolute;

        width: 220px;
        height: 220px;

        left: -100px;
        bottom: -90px;

        border-radius: 50%;

        background: rgba(255, 255, 255, 0.05);
    }


    /*
    |--------------------------------------------------------------------------
    | CONTENU GAUCHE
    |--------------------------------------------------------------------------
    */

    .secure-login-visual-content {
        position: relative;
        z-index: 2;

        height: 100%;

        padding: 48px;

        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }


    /*
    |--------------------------------------------------------------------------
    | LOGO
    |--------------------------------------------------------------------------
    */

    .secure-logo-box {
        width: 48px;
        height: 48px;

        border-radius: 14px;

        background: #ffffff;
        color: #1d4ed8;

        display: flex;
        align-items: center;
        justify-content: center;

        flex-shrink: 0;
    }


    /*
    |--------------------------------------------------------------------------
    | TITRE GAUCHE
    |--------------------------------------------------------------------------
    */

    .secure-login-title {
        font-size: 38px;
        line-height: 1.16;
        letter-spacing: -0.7px;
    }


    .secure-login-description {
        max-width: 430px;

        color: rgba(255, 255, 255, 0.78);

        font-size: 14px;
        line-height: 1.75;
    }


    /*
    |--------------------------------------------------------------------------
    | INDICATEURS SÉCURITÉ
    |--------------------------------------------------------------------------
    */

    .security-item {
        display: flex;
        align-items: center;

        margin-bottom: 18px;
    }


    .security-item:last-child {
        margin-bottom: 0;
    }


    .security-icon {
        width: 40px;
        height: 40px;

        border-radius: 50%;

        background: rgba(255, 255, 255, 0.12);

        display: flex;
        align-items: center;
        justify-content: center;

        margin-right: 12px;

        flex-shrink: 0;
    }


    /*
    |--------------------------------------------------------------------------
    | PANNEAU FORMULAIRE
    |--------------------------------------------------------------------------
    */

    .secure-login-form-panel {
        min-height: 610px;

        display: flex;
        align-items: center;

        background: #ffffff;
    }


    .secure-login-form-content {
        width: 100%;

        padding: 48px;
    }


    /*
    |--------------------------------------------------------------------------
    | EN-TÊTE FORMULAIRE
    |--------------------------------------------------------------------------
    */

    .secure-login-heading {
        font-size: 31px;
        line-height: 1.2;

        color: #111827;
        letter-spacing: -0.5px;
    }


    .secure-login-subtitle {
        font-size: 14px;
        line-height: 1.6;
        color: #6b7280;
    }


    /*
    |--------------------------------------------------------------------------
    | CHAMPS
    |--------------------------------------------------------------------------
    */

    .secure-form-label {
        color: #374151;

        font-size: 13px;
        font-weight: 600;
    }


    .secure-input-group {
        border-radius: 12px;
        overflow: hidden;
    }


    .secure-input-group .input-group-text {
        border-color: #e5e7eb;

        color: #6b7280;

        min-width: 46px;

        justify-content: center;
    }


    .secure-input-group .form-control {
        min-height: 46px;

        border-color: #e5e7eb;

        font-size: 14px;

        box-shadow: none;
    }


    .secure-input-group .form-control:focus {
        border-color: #2563eb;

        box-shadow: 0 0 0 0.15rem rgba(37, 99, 235, 0.10);
    }


    .secure-input-group .btn {
        border-color: #e5e7eb;

        box-shadow: none;
    }


    /*
    |--------------------------------------------------------------------------
    | BOUTON CONNEXION
    |--------------------------------------------------------------------------
    */

    .secure-login-button {
        min-height: 48px;

        border: 0;

        border-radius: 12px;

        background:
            linear-gradient(
                135deg,
                #2563eb,
                #1d4ed8
            );

        box-shadow:
            0 8px 18px rgba(37, 99, 235, 0.20);

        transition:
            transform 0.2s ease,
            box-shadow 0.2s ease;
    }


    .secure-login-button:hover {
        transform: translateY(-1px);

        box-shadow:
            0 12px 24px rgba(37, 99, 235, 0.25);
    }


    /*
    |--------------------------------------------------------------------------
    | MESSAGE DE SÉCURITÉ
    |--------------------------------------------------------------------------
    */

    .secure-login-security-note {
        display: flex;
        align-items: center;
        justify-content: center;

        gap: 7px;

        margin-top: 20px;

        color: #6b7280;

        font-size: 11px;
    }


    /*
    |--------------------------------------------------------------------------
    | VERSION MOBILE
    |--------------------------------------------------------------------------
    */

    .secure-mobile-brand {
        margin-bottom: 28px;
    }


    /*
    |--------------------------------------------------------------------------
    | RESPONSIVE
    |--------------------------------------------------------------------------
    */

    @media (max-width: 991.98px) {

        .secure-login-page {
            padding: 20px 14px;
        }


        .secure-login-card {
            max-width: 560px;

            border-radius: 20px;
        }


        .secure-login-form-panel {
            min-height: auto;
        }


        .secure-login-form-content {
            padding: 38px 30px;
        }

    }


    @media (max-width: 575.98px) {

        .secure-login-page {
            padding: 12px;
        }


        .secure-login-card {
            border-radius: 18px;
        }


        .secure-login-form-content {
            padding: 30px 20px;
        }


        .secure-login-heading {
            font-size: 27px;
        }

    }
</style>


<div class="secure-login-page d-flex align-items-center justify-content-center">

    <div class="secure-login-card">

        <div class="row g-0 align-items-stretch">


            {{-- ============================================================
                 PANNEAU GAUCHE
            ============================================================= --}}

            <div class="col-lg-6 d-none d-lg-block">

                <div class="secure-login-visual">

                    <div class="secure-login-visual-content">


                        {{-- ------------------------------------------------
                             PARTIE SUPÉRIEURE
                        ------------------------------------------------- --}}

                        <div>

                            {{-- LOGO --}}

                            <div class="d-flex align-items-center mb-5">

                                <div class="secure-logo-box me-3">

                                    <i class="bi bi-shield-lock-fill fs-4"></i>

                                </div>


                                <div>

                                    <div class="fw-bold fs-4">
                                        SecureAccess
                                    </div>

                                    <small class="opacity-75">
                                        Biometric Security
                                    </small>

                                </div>

                            </div>


                            {{-- TITRE --}}

                            <h1 class="secure-login-title fw-bold text-white mb-3">

                                Contrôlez les accès.
                                <br>

                                Sécurisez votre organisation.

                            </h1>


                            {{-- DESCRIPTION --}}

                            <p class="secure-login-description mb-0">

                                Plateforme intelligente de contrôle
                                d'accès et de gestion de présence
                                par RFID et reconnaissance faciale.

                            </p>

                        </div>


                        {{-- ------------------------------------------------
                             CARACTÉRISTIQUES
                        ------------------------------------------------- --}}

                        <div>

                            <div class="security-item">

                                <div class="security-icon">

                                    <i class="bi bi-fingerprint"></i>

                                </div>

                                <span>
                                    Authentification biométrique
                                </span>

                            </div>


                            <div class="security-item">

                                <div class="security-icon">

                                    <i class="bi bi-rss"></i>

                                </div>

                                <span>
                                    Technologie RFID
                                </span>

                            </div>


                            <div class="security-item">

                                <div class="security-icon">

                                    <i class="bi bi-broadcast"></i>

                                </div>

                                <span>
                                    Supervision en temps réel
                                </span>

                            </div>

                        </div>


                    </div>

                </div>

            </div>



            {{-- ============================================================
                 PANNEAU FORMULAIRE
            ============================================================= --}}

            <div class="col-lg-6">

                <div class="secure-login-form-panel">

                    <div class="secure-login-form-content">


                        {{-- ------------------------------------------------
                             MARQUE MOBILE
                        ------------------------------------------------- --}}

                        <div class="secure-mobile-brand d-lg-none text-center">

                            <div
                                class="mx-auto mb-3 secure-logo-box"
                                style="
                                    background:#2563eb;
                                    color:#ffffff;
                                    width:56px;
                                    height:56px;
                                "
                            >

                                <i class="bi bi-shield-lock-fill fs-3"></i>

                            </div>


                            <h4 class="fw-bold mb-1">
                                SecureAccess
                            </h4>


                            <small class="text-muted">
                                Biometric Security System
                            </small>

                        </div>



                        {{-- ------------------------------------------------
                             EN-TÊTE
                        ------------------------------------------------- --}}

                        <div class="mb-4">

                            <div class="small text-primary fw-semibold mb-2">

                                ESPACE ADMINISTRATEUR

                            </div>


                            <h2 class="secure-login-heading fw-bold mb-2">

                                Bienvenue

                            </h2>


                            <p class="secure-login-subtitle mb-0">

                                Connectez-vous pour accéder
                                à votre espace d'administration.

                            </p>

                        </div>



                        {{-- ------------------------------------------------
                             MESSAGE SUCCÈS
                        ------------------------------------------------- --}}

                        @if (session('success'))

                            <div class="alert alert-success border-0 small">

                                <i class="bi bi-check-circle me-1"></i>

                                {{ session('success') }}

                            </div>

                        @endif



                        {{-- ------------------------------------------------
                             ERREURS
                        ------------------------------------------------- --}}

                        @if ($errors->any())

                            <div class="alert alert-danger border-0 small">

                                <div class="fw-semibold mb-1">

                                    <i class="bi bi-exclamation-triangle me-1"></i>

                                    Échec de connexion

                                </div>

                                <ul class="mb-0 ps-3">

                                    @foreach ($errors->all() as $error)

                                        <li>
                                            {{ $error }}
                                        </li>

                                    @endforeach

                                </ul>

                            </div>

                        @endif



                        {{-- ------------------------------------------------
                             FORMULAIRE
                        ------------------------------------------------- --}}

                        <form
                            method="POST"
                            action="{{ route('login.authenticate') }}"
                        >

                            @csrf


                            {{-- EMAIL --}}

                            <div class="mb-3">

                                <label
                                    for="email"
                                    class="form-label secure-form-label"
                                >
                                    Adresse email
                                </label>


                                <div class="input-group secure-input-group">

                                    <span class="input-group-text bg-white">

                                        <i class="bi bi-envelope"></i>

                                    </span>


                                    <input
                                        type="email"
                                        class="form-control"
                                        id="email"
                                        name="email"
                                        value="{{ old('email') }}"
                                        placeholder="exemple@entreprise.com"
                                        autocomplete="email"
                                        required
                                        autofocus
                                    >

                                </div>

                            </div>



                            {{-- MOT DE PASSE --}}

                            <div class="mb-3">

                                <div class="d-flex justify-content-between align-items-center">

                                    <label
                                        for="password"
                                        class="form-label secure-form-label mb-0"
                                    >
                                        Mot de passe
                                    </label>


                                    <span class="small text-muted">
                                        Compte administrateur
                                    </span>

                                </div>


                                <div class="input-group secure-input-group mt-2">

                                    <span class="input-group-text bg-white">

                                        <i class="bi bi-lock"></i>

                                    </span>


                                    <input
                                        type="password"
                                        class="form-control"
                                        id="password"
                                        name="password"
                                        placeholder="Votre mot de passe"
                                        autocomplete="current-password"
                                        required
                                    >


                                    <button
                                        type="button"
                                        class="btn btn-outline-secondary bg-white"
                                        id="togglePassword"
                                        aria-label="Afficher ou masquer le mot de passe"
                                    >

                                        <i class="bi bi-eye"></i>

                                    </button>

                                </div>

                            </div>



                            {{-- REMEMBER --}}

                            <div class="form-check mb-4">

                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    id="remember"
                                    name="remember"
                                >


                                <label
                                    class="form-check-label text-muted small"
                                    for="remember"
                                >

                                    Se souvenir de moi

                                </label>

                            </div>



                            {{-- BOUTON --}}

                            <button
                                type="submit"
                                class="btn btn-primary secure-login-button w-100 fw-semibold"
                            >

                                <i class="bi bi-box-arrow-in-right me-2"></i>

                                Se connecter

                            </button>

                        </form>



                        {{-- ------------------------------------------------
                             NOTE DE SÉCURITÉ
                        ------------------------------------------------- --}}

                        <div class="secure-login-security-note">

                            <i class="bi bi-shield-check"></i>

                            Connexion sécurisée à l'espace administrateur

                        </div>



                        {{-- ------------------------------------------------
                             COPYRIGHT
                        ------------------------------------------------- --}}

                        <div class="text-center mt-4">

                            <small class="text-muted">

                                SecureAccess © {{ date('Y') }}

                            </small>

                        </div>


                    </div>

                </div>

            </div>


        </div>

    </div>

</div>

@endsection



@push('scripts')

<script>

    /*
    |--------------------------------------------------------------------------
    | AFFICHER / MASQUER LE MOT DE PASSE
    |--------------------------------------------------------------------------
    */

    document.addEventListener('DOMContentLoaded', function () {

        const passwordInput =
            document.getElementById('password');

        const togglePassword =
            document.getElementById('togglePassword');


        if (!passwordInput || !togglePassword) {
            return;
        }


        togglePassword.addEventListener('click', function () {

            const type =
                passwordInput.getAttribute('type') === 'password'
                    ? 'text'
                    : 'password';


            passwordInput.setAttribute('type', type);


            const icon =
                this.querySelector('i');


            icon.classList.toggle(
                'bi-eye',
                type === 'password'
            );


            icon.classList.toggle(
                'bi-eye-slash',
                type === 'text'
            );

        });

    });

</script>

@endpush