@extends('layouts.admin')

@section('title', 'Paramètres')

@section('content')


@if(session('success'))

    <div class="alert alert-success border-0 shadow-sm"
         style="font-size: 12px;">

        <i class="bi bi-check-circle me-1"></i>

        {{ session('success') }}

    </div>

@endif

{{-- ========================================================= --}}
{{-- PROFIL ADMINISTRATEUR --}}
{{-- ========================================================= --}}

<div class="dashboard-card mb-4">

    <div class="card-header-custom">

        <div>

            <h5 class="card-title-custom">
                Profil administrateur
            </h5>

            <div class="page-subtitle">
                Informations du compte connecté
            </div>

        </div>

        <span class="live-badge">
            <i class="bi bi-person-check"></i>
            COMPTE ACTIF
        </span>

    </div>


    <div class="card-body-custom">


        {{-- ================================================= --}}
        {{-- MODE CONSULTATION --}}
        {{-- ================================================= --}}

        <div id="profile-view">

            <div class="row g-4">

                <div class="col-md-6">

                    <div class="info-label">
                        Nom
                    </div>

                    <div class="info-value">
                        {{ $admin->nom ?? 'Non renseigné' }}
                    </div>

                </div>


                <div class="col-md-6">

                    <div class="info-label">
                        Prénom
                    </div>

                    <div class="info-value">
                        {{ $admin->prenom ?? 'Non renseigné' }}
                    </div>

                </div>


                <div class="col-md-6">

                    <div class="info-label">
                        Matricule
                    </div>

                    <div class="info-value">
                        {{ $admin->matricule ?? 'Non renseigné' }}
                    </div>

                </div>


                <div class="col-md-6">

                    <div class="info-label">
                        Service
                    </div>

                    <div class="info-value">
                        {{ $admin->service ?? 'Non renseigné' }}
                    </div>

                </div>


                <div class="col-md-6">

                    <div class="info-label">
                        Adresse email
                    </div>

                    <div class="info-value">
                        {{ $admin->email ?? 'Non renseigné' }}
                    </div>

                </div>


                <div class="col-md-6">

                    <div class="info-label">
                        Téléphone
                    </div>

                    <div class="info-value">
                        {{ $admin->telephone ?? 'Non renseigné' }}
                    </div>

                </div>

            </div>


            <div class="mt-4">

                <button
                    type="button"
                    class="btn btn-primary"
                    onclick="showProfileEdit()"
                >

                    <i class="bi bi-pencil"></i>

                    Modifier

                </button>

            </div>

        </div>


        {{-- ================================================= --}}
        {{-- MODE MODIFICATION --}}
        {{-- ================================================= --}}

        <div id="profile-edit" style="display: none;">

            <form method="POST" action="{{ route('settings.profile.update') }}">

                @csrf
                @method('PUT')

                <div class="row g-4">

                    <div class="col-md-6">

                        <label class="form-label-custom">
                            Nom
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="{{ $admin->nom }}"
                            name="nom"
                        >

                    </div>


                    <div class="col-md-6">

                        <label class="form-label-custom">
                            Prénom
                        </label>

                        <input
                            type="text"
                            name="prenom"
                            class="form-control"
                            value="{{ $admin->prenom }}"
                        >

                    </div>


                    <div class="col-md-6">

                        <label class="form-label-custom">
                            Matricule
                        </label>

                        <input
                            type="text"
                            name="matricule"
                            class="form-control"
                            value="{{ $admin->matricule }}"
                        >

                    </div>


                    <div class="col-md-6">

                        <label class="form-label-custom">
                            Service
                        </label>

                        <input
                            type="text"
                            name="service"
                            class="form-control"
                            value="{{ $admin->service }}"
                        >

                    </div>


                    <div class="col-md-6">

                        <label class="form-label-custom">
                            Adresse email
                        </label>

                        <input
                            type="email"
                            name="email"
                            class="form-control"
                            value="{{ $admin->email }}"
                        >

                    </div>


                    <div class="col-md-6">

                        <label class="form-label-custom">
                            Téléphone
                        </label>

                        <input
                            type="text"
                            name="telephone"
                            class="form-control"
                            value="{{ $admin->telephone }}"
                        >

                    </div>

                </div>


                <div class="mt-4 d-flex gap-2">

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >

                        <i class="bi bi-check-lg"></i>

                        Enregistrer

                    </button>


                    <button
                        type="button"
                        class="btn btn-light border"
                        onclick="hideProfileEdit()"
                    >

                        Annuler

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


{{-- ========================================================= --}}
{{-- SÉCURITÉ --}}
{{-- ========================================================= --}}

<div class="dashboard-card">

    <div class="card-header-custom">

        <div>

            <h5 class="card-title-custom">
                Sécurité du compte
            </h5>

            <div class="page-subtitle">
                Gestion du mot de passe administrateur
            </div>

        </div>

        <i class="bi bi-shield-lock fs-5 text-muted"></i>

    </div>


    <div class="card-body-custom">


        {{-- MODE CONSULTATION --}}

        <div id="password-view">

            <div class="password-info">

                <div>

                    <div class="info-label">
                        Mot de passe
                    </div>

                    <div class="info-value password-dots">
                        ••••••••••••
                    </div>

                </div>

            </div>


            <div class="mt-4">

                <button
                    type="button"
                    class="btn btn-dark"
                    onclick="showPasswordEdit()"
                >

                    <i class="bi bi-key"></i>

                    Modifier le mot de passe

                </button>

            </div>

        </div>


        {{-- MODE MODIFICATION --}}

        <div id="password-edit" style="display: none;">

            <form method="POST" action="{{ route('settings.password.update') }}">

                @csrf
                @method('PUT')

                <div class="row g-4">
                    <div class="col-md-12">
    <label class="form-label-custom">Mot de passe actuel</label>

    <div class="password-input-wrapper">
        <input
            type="password"
            name="current_password"
            id="current_password"
            class="form-control"
            placeholder="••••••••"
        >

        <button
            type="button"
            class="password-toggle"
            onclick="togglePassword('current_password', this)"
        >
            <i class="bi bi-eye"></i>
        </button>
    </div>
</div>

<div class="col-md-6">
    <label class="form-label-custom">Nouveau mot de passe</label>

    <div class="password-input-wrapper">
        <input
            type="password"
            name="password"
            id="password"
            class="form-control"
            placeholder="••••••••"
        >

        <button
            type="button"
            class="password-toggle"
            onclick="togglePassword('password', this)"
        >
            <i class="bi bi-eye"></i>
        </button>
    </div>
</div>

<div class="col-md-6">
    <label class="form-label-custom">Confirmer le nouveau mot de passe</label>

    <div class="password-input-wrapper">
        <input
            type="password"
            name="password_confirmation"
            id="password_confirmation"
            class="form-control"
            placeholder="••••••••"
        >

        <button
            type="button"
            class="password-toggle"
            onclick="togglePassword('password_confirmation', this)"
        >
            <i class="bi bi-eye"></i>
        </button>
    </div>
</div>
                   
                </div>


                <div class="mt-4 d-flex gap-2">

                    <button
                        type="submit"
                        class="btn btn-dark"
                    >

                        <i class="bi bi-check-lg"></i>

                        Modifier le mot de passe

                    </button>


                    <button
                        type="button"
                        class="btn btn-light border"
                        onclick="hidePasswordEdit()"
                    >

                        Annuler

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


<style>

.info-label {
    margin-bottom: 5px;
    font-size: 10px;
    font-weight: 600;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.info-value {
    font-size: 13px;
    font-weight: 500;
    color: #212529;
}

.form-label-custom {
    display: block;
    margin-bottom: 5px;
    font-size: 11px;
    font-weight: 600;
    color: #6c757d;
}

.form-control {
    font-size: 12px;
    padding: 8px 10px;
}

.password-dots {
    letter-spacing: 2px;
}

.password-input-wrapper {
    position: relative;
}

.password-input-wrapper .form-control {
    padding-right: 40px;
}

.password-toggle {
    position: absolute;
    top: 50%;
    right: 10px;
    transform: translateY(-50%);

    border: none;
    background: transparent;

    color: #6c757d;
    cursor: pointer;

    padding: 4px;
}

.password-toggle:hover {
    color: #212529;
}


/* =========================================================
   RESPONSIVE — PAGE PARAMÈTRES
   ========================================================= */

@media (max-width: 768px) {

    /* Cartes */
    .dashboard-card {
        border-radius: 12px;
    }

    .dashboard-card .card-header-custom {
        padding: 16px;
        gap: 12px;
    }

    .dashboard-card .card-body-custom {
        padding: 16px;
    }

    /* Titres */
    .card-title-custom {
        font-size: 13px;
    }

    .card-header-custom .page-subtitle {
        font-size: 10px;
    }

    /* Badge compte actif */
    .live-badge {
        font-size: 9px;
        padding: 5px 8px;
    }

    /* Informations du profil */
    .info-label {
        font-size: 9px;
    }

    .info-value {
        font-size: 12px;
        word-break: break-word;
    }

    /* Champs */
    .form-label-custom {
        font-size: 10px;
        margin-bottom: 5px;
    }

    .form-control {
        min-height: 38px;
        font-size: 11px;
    }

    /* Boutons */
    .dashboard-card .btn {
        min-height: 38px;
        font-size: 11px;
    }

    /* Édition du profil */
    #profile-edit .row,
    #password-edit .row {
        --bs-gutter-y: 1rem;
    }

    /* Boutons d'action */
    #profile-edit .mt-4,
    #password-edit .mt-4 {
        flex-wrap: wrap;
    }

    /* Sécurité */
    .password-info {
        width: 100%;
    }
}


/* =========================================================
   TRÈS PETITS ÉCRANS
   ========================================================= */

@media (max-width: 480px) {

    /* Cartes */
    .dashboard-card {
        border-radius: 10px;
    }

    .dashboard-card .card-header-custom {
        padding: 14px;
    }

    .dashboard-card .card-body-custom {
        padding: 14px;
    }

    /* Titres */
    .card-title-custom {
        font-size: 12px;
    }

    .card-header-custom .page-subtitle {
        font-size: 9px;
    }

    /* Badge */
    .live-badge {
        font-size: 8px;
        padding: 4px 7px;
    }

    /* Informations */
    .info-label {
        font-size: 8px;
    }

    .info-value {
        font-size: 11px;
    }

    /* Formulaire */
    .form-label-custom {
        font-size: 9px;
    }

    .form-control {
        min-height: 37px;
        font-size: 10px;
        padding: 8px 10px;
    }

    /* Boutons */
    #profile-view .btn,
    #password-view .btn,
    #profile-edit .btn,
    #password-edit .btn {
        width: 100%;
        font-size: 10px;
    }

    /* Boutons Enregistrer / Annuler */
    #profile-edit .mt-4,
    #password-edit .mt-4 {
        flex-direction: column;
        align-items: stretch;
        gap: 8px !important;
    }

    /* Icône œil */
    .password-toggle {
        right: 8px;
    }

    /* Message de succès */
    .alert {
        font-size: 10px !important;
    }
}
</style>


<script>

function showProfileEdit()
{
    document.getElementById('profile-view').style.display = 'none';
    document.getElementById('profile-edit').style.display = 'block';
}

function hideProfileEdit()
{
    document.getElementById('profile-edit').style.display = 'none';
    document.getElementById('profile-view').style.display = 'block';
}

function showPasswordEdit()
{
    document.getElementById('password-view').style.display = 'none';
    document.getElementById('password-edit').style.display = 'block';
}

function hidePasswordEdit()
{
    document.getElementById('password-edit').style.display = 'none';
    document.getElementById('password-view').style.display = 'block';
}


function togglePassword(inputId, button)
{
    const input = document.getElementById(inputId);
    const icon = button.querySelector('i');

    if (input.type === 'password') {

        input.type = 'text';

        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');

    } else {

        input.type = 'password';

        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');
    }
}
</script>

@endsection