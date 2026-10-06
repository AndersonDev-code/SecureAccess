@extends('layouts.admin')

@section('title', 'Modifier un employé | SecureAccess')

@section('content')

<div class="content">

    {{-- EN-TÊTE --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <div class="d-flex align-items-center gap-2 mb-1">

                <a href="{{ route('employees.index') }}"
                   class="text-muted">

                    <i class="bi bi-arrow-left"></i>

                </a>

                <h1 class="page-title">
                    Modifier un employé
                </h1>

            </div>

            <div class="page-subtitle">

                Modifier les informations de l'employé
                et ses données biométriques.

            </div>

        </div>

        <a href="{{ route('employees.index') }}"
           class="btn btn-light border">

            <i class="bi bi-x-lg me-1"></i>

            Annuler

        </a>

    </div>


    {{-- FORMULAIRE --}}
    <form method="POST"
          action="{{ route('employees.update', $employee->id) }}"
          enctype="multipart/form-data">

        @csrf

        @method('PUT')

        @if ($errors->any())

            <div class="alert alert-danger">

                <div class="fw-bold mb-2">

                    <i class="bi bi-exclamation-triangle-fill me-2"></i>

                    Impossible de modifier l'employé

                </div>

                <ul class="mb-0">

                    @foreach ($errors->all() as $error)

                        <li>{{ $error }}</li>

                    @endforeach

                </ul>

            </div>

        @endif


        {{-- PHOTO CAPTUREE EN BASE64 --}}
        <input type="hidden"
               name="face_photo"
               id="face_photo">


        <div class="row g-4">


            {{-- =====================================================
                 COLONNE PRINCIPALE
            ====================================================== --}}

            <div class="col-xl-8">


                {{-- =================================================
                     INFORMATIONS PERSONNELLES
                ================================================== --}}

                <div class="dashboard-card mb-4">

                    <div class="card-header-custom">

                        <div>

                            <div class="card-title-custom">

                                Informations personnelles

                            </div>

                            <div
                                class="text-muted"
                                style="font-size:10px;">

                                Informations générales de l'employé

                            </div>

                        </div>

                        <i class="bi bi-person text-primary"></i>

                    </div>


                    <div class="card-body-custom">

                        <div class="row g-3">


                            {{-- NOM --}}

                            <div class="col-md-6">

                                <label class="form-label fw-semibold">

                                    Nom

                                </label>

                                <input
                                    type="text"
                                    name="nom"
                                    class="form-control"
                                    placeholder="Ex : Kamga"
                                    value="{{ old('nom', $employee->nom) }}"
                                    required
                                >

                            </div>


                            {{-- PRENOM --}}

                            <div class="col-md-6">

                                <label class="form-label fw-semibold">

                                    Prénom

                                </label>

                                <input
                                    type="text"
                                    name="prenom"
                                    class="form-control"
                                    placeholder="Ex : Jean"
                                    value="{{ old('prenom', $employee->prenom) }}"
                                    required
                                >

                            </div>


                            {{-- MATRICULE --}}

                            <div class="col-md-6">

                                <label class="form-label fw-semibold">

                                    Matricule

                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">

                                        <i class="bi bi-card-text"></i>

                                    </span>

                                    <input
                                        type="text"
                                        name="matricule"
                                        class="form-control"
                                        placeholder="EMP-001"
                                        value="{{ old('matricule', $employee->matricule) }}"
                                        required
                                    >

                                </div>

                            </div>


                            {{-- SERVICE --}}

                            <div class="col-md-6">

                                <label class="form-label fw-semibold">

                                    Service

                                </label>

                                <select
                                    name="service"
                                    class="form-select"
                                    required>

                                    <option value="">
                                        Sélectionner un service
                                    </option>

                                    <option value="Direction"
                                        {{ old('service', $employee->service) == 'Direction' ? 'selected' : '' }}>
                                        Direction
                                    </option>

                                    <option value="Ressources Humaines"
                                        {{ old('service', $employee->service) == 'Ressources Humaines' ? 'selected' : '' }}>
                                        Ressources Humaines
                                    </option>

                                    <option value="Informatique"
                                        {{ old('service', $employee->service) == 'Informatique' ? 'selected' : '' }}>
                                        Informatique
                                    </option>

                                    <option value="Comptabilité"
                                        {{ old('service', $employee->service) == 'Comptabilité' ? 'selected' : '' }}>
                                        Comptabilité
                                    </option>

                                    <option value="Logistique"
                                        {{ old('service', $employee->service) == 'Logistique' ? 'selected' : '' }}>
                                        Logistique
                                    </option>

                                    <option value="Marketing"
                                        {{ old('service', $employee->service) == 'Marketing' ? 'selected' : '' }}>
                                        Marketing
                                    </option>

                                </select>

                            </div>


                            {{-- EMAIL --}}

                            <div class="col-md-6">

                                <label class="form-label fw-semibold">

                                    Adresse email

                                </label>

                                <input
                                    type="email"
                                    name="email"
                                    class="form-control"
                                    placeholder="employe@entreprise.com"
                                    value="{{ old('email', $employee->email) }}"
                                >

                            </div>


                            {{-- TELEPHONE --}}

                            <div class="col-md-6">

                                <label class="form-label fw-semibold">

                                    Téléphone

                                </label>

                                <input
                                    type="tel"
                                    name="telephone"
                                    class="form-control"
                                    placeholder="+237 6XX XXX XXX"
                                    value="{{ old('telephone', $employee->telephone) }}"
                                >

                            </div>


                        </div>

                    </div>

                </div>



                {{-- =================================================
                     RFID
                ================================================== --}}

                <div class="dashboard-card mb-4">

                    <div class="card-header-custom">

                        <div>

                            <div class="card-title-custom">

                                Identification RFID

                            </div>

                            <div
                                class="text-muted"
                                style="font-size:10px;">

                                Modification ou remplacement du badge

                            </div>

                        </div>

                        <i class="bi bi-rss text-primary"></i>

                    </div>


                    <div class="card-body-custom">

                        <div class="row align-items-end g-3">


                            <div class="col-md-8">

                                <label class="form-label fw-semibold">

                                    UID du badge RFID

                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">

                                        <i class="bi bi-credit-card-2-front"></i>

                                    </span>

                                    <input
                                        type="text"
                                        name="rfid_uid"
                                        id="rfid_uid"
                                        class="form-control"
                                        placeholder="Ex : A4:B7:23:91"
                                        value="{{ old('rfid_uid', $employee->rfid_uid) }}"
                                    >

                                </div>

                                <div class="form-text">

                                    Vous pouvez conserver le badge actuel
                                    ou le remplacer par un nouveau badge.

                                </div>

                            </div>


                            <div class="col-md-4">

                                <button
                                    type="button"
                                    class="btn btn-outline-primary w-100"
                                    id="scanRfid">

                                    <i class="bi bi-rss me-2"></i>

                                    Scanner le badge

                                </button>

                            </div>

                        </div>


                        <div
                            id="rfidStatus"
                            class="mt-3 {{ $employee->rfid_uid ? '' : 'd-none' }}">

                            <div class="alert alert-success mb-0">

                                <i class="bi bi-check-circle-fill me-2"></i>

                                Badge RFID actuellement associé.

                            </div>

                        </div>

                    </div>

                </div>



                {{-- =================================================
                     BIOMETRIE
                ================================================== --}}

                <div class="dashboard-card mb-4">

                    <div class="card-header-custom">

                        <div>

                            <div class="card-title-custom">

                                Reconnaissance faciale

                            </div>

                            <div
                                class="text-muted"
                                style="font-size:10px;">

                                Modification de l'enregistrement facial

                            </div>

                        </div>

                        <i class="bi bi-person-bounding-box text-primary"></i>

                    </div>


                    <div class="card-body-custom">

                        <div class="row align-items-center g-4">


                            <div class="col-md-5 text-center">

                                <div
                                    id="facePreview"
                                    class="mx-auto rounded-4 bg-light d-flex align-items-center justify-content-center"
                                    style="
                                        width:180px;
                                        height:180px;
                                        border:2px dashed #d1d5db;
                                        overflow:hidden;
                                    "
                                >

                                    @if($employee->photo)

                                        <img
                                            src="{{ asset('storage/' . $employee->photo) }}"
                                            class="w-100 h-100"
                                            style="object-fit:cover;"
                                            alt="Photo de {{ $employee->prenom }} {{ $employee->nom }}"
                                        >

                                    @else

                                        <div class="text-center text-muted">

                                            <i
                                                class="bi bi-person-bounding-box"
                                                style="font-size:45px;">
                                            </i>

                                            <div
                                                class="mt-2"
                                                style="font-size:11px;">

                                                Aucun visage enregistré

                                            </div>

                                        </div>

                                    @endif

                                </div>

                            </div>


                            <div class="col-md-7">

                                <h6 class="fw-bold">

                                    Capture biométrique

                                </h6>

                                <p
                                    class="text-muted"
                                    style="font-size:11px;">

                                    Vous pouvez conserver le visage
                                    actuellement enregistré ou effectuer
                                    une nouvelle capture avec l'ESP32-CAM.

                                </p>


                                <button
                                    type="button"
                                    class="btn btn-primary"
                                    id="captureFace">

                                    <i class="bi bi-camera-fill me-2"></i>

                                    Reprendre la photo

                                </button>


                                <input
                                    type="file"
                                    name="photo"
                                    id="facephoto"
                                    hidden
                                >


                                <div class="mt-3">

                                    @if($employee->face_encoding || $employee->photo)

                                        <span
                                            id="faceStatus"
                                            class="badge bg-success">

                                            <i class="bi bi-check-circle-fill me-1"></i>

                                            Visage enregistré

                                        </span>

                                    @else

                                        <span
                                            id="faceStatus"
                                            class="badge bg-secondary">

                                            Non enregistré

                                        </span>

                                    @endif

                                </div>

                            </div>

                        </div>

                    </div>

                </div>



                {{-- =================================================
                     HORAIRE
                ================================================== --}}

                <div class="dashboard-card">

                    <div class="card-header-custom">

                        <div>

                            <div class="card-title-custom">

                                Horaire de travail

                            </div>

                            <div
                                class="text-muted"
                                style="font-size:10px;">

                                Définition des heures de présence

                            </div>

                        </div>

                        <i class="bi bi-clock text-primary"></i>

                    </div>


                    <div class="card-body-custom">

                        <div class="row g-3">


                            <div class="col-md-4">

                                <label class="form-label fw-semibold">

                                    Heure d'entrée

                                </label>

                                <input
                                    type="time"
                                    name="heure_entree"
                                    class="form-control"
                                    value="{{ old('heure_entree', $schedule->heure_entree ?? '08:00') }}"
                                >

                            </div>


                            <div class="col-md-4">

                                <label class="form-label fw-semibold">

                                    Heure de sortie

                                </label>

                                <input
                                    type="time"
                                    name="heure_sortie"
                                    class="form-control"
                                    value="{{ old('heure_sortie', $schedule->heure_sortie ?? '17:00') }}"
                                >

                            </div>


                            <div class="col-md-4">

                                <label class="form-label fw-semibold">

                                    Tolérance retard

                                </label>

                                @php
                                    $tolerance = old(
                                    'tolerance',
                                    $schedule->tolerance ?? 10
                                    );
                                @endphp

                                <select
                                    name="tolerance"
                                    class="form-select">

                                    <option
                                    value="5"
                                    {{ $tolerance == 5 ? 'selected' : '' }}>
                                    5 minutes
                                    </option>

                                    <option
                                        value="10"
                                        {{ $tolerance == 10 ? 'selected' : '' }}>
                                        10 minutes
                                    </option>

                                    <option
                                        value="15"
                                        {{ $tolerance == 15 ? 'selected'     : '' }}>
                                        15 minutes
                                    </option>

                                    <option
                                        value="30"
                                        {{ $tolerance == 30 ? 'selected' : '' }}>
                                            30 minutes
                                    </option>

                                </select>
                                
                            </div>

                        </div>

                    </div>

                </div>

            </div>



            {{-- =====================================================
                 COLONNE DROITE
            ====================================================== --}}

            <div class="col-xl-4">


                {{-- APERCU --}}

                <div class="dashboard-card mb-4">

                    <div class="card-header-custom">

                        <div class="card-title-custom">

                            Résumé

                        </div>

                    </div>


                    <div class="card-body-custom">


                        <div class="text-center mb-4">

                            <div
                                id="profilePreview"
                                class="mx-auto rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center"
                                style="
                                    width:80px;
                                    height:80px;
                                    overflow:hidden;
                                "
                            >

                                @if($employee->photo)

                                    <img
                                        src="{{ asset('storage/' . $employee->photo) }}"
                                        class="w-100 h-100 rounded-circle"
                                        style="object-fit:cover;"
                                        alt="Photo"
                                    >

                                @else

                                    <i
                                        class="bi bi-person-fill"
                                        style="font-size:35px;">
                                    </i>

                                @endif

                            </div>


                            <div
                                id="profileName"
                                class="fw-bold mt-3">

                                {{ $employee->prenom }} {{ $employee->nom }}

                            </div>


                            <div
                                class="text-muted"
                                style="font-size:10px;">

                                {{ $employee->matricule }}

                            </div>

                        </div>


                        <div class="border-top pt-3">


                            {{-- RFID --}}

                            <div class="d-flex justify-content-between mb-3">

                                <span
                                    class="text-muted"
                                    style="font-size:11px;">

                                    Badge RFID

                                </span>

                                <span
                                    id="summaryRfid"
                                    class="badge {{ $employee->rfid_uid ? 'bg-success' : 'bg-secondary' }}">

                                    {{ $employee->rfid_uid ? 'Badge associé' : 'Non associé' }}

                                </span>

                            </div>


                            {{-- BIOMETRIE --}}

                            <div class="d-flex justify-content-between mb-3">

                                <span
                                    class="text-muted"
                                    style="font-size:11px;">

                                    Biométrie

                                </span>

                                <span
                                    id="summaryFace"
                                    class="badge {{ ($employee->face_encoding || $employee->photo) ? 'bg-success' : 'bg-secondary' }}">

                                    {{ ($employee->face_encoding || $employee->photo) ? 'Enregistrée' : 'Non enregistrée' }}

                                </span>

                            </div>


                            {{-- STATUT --}}

                            <div class="d-flex justify-content-between">

                                <span
                                    class="text-muted"
                                    style="font-size:11px;">

                                    Statut

                                </span>

                                <span
                                    class="badge {{ $employee->is_active ? 'bg-success' : 'bg-danger' }}">

                                    {{ $employee->is_active ? 'Actif' : 'Désactivé' }}

                                </span>

                            </div>


                        </div>

                    </div>

                </div>



                {{-- AIDE --}}

                <div class="dashboard-card mb-4">

                    <div class="card-body-custom">

                        <div class="d-flex gap-3">

                            <div class="text-primary">

                                <i class="bi bi-info-circle-fill fs-4"></i>

                            </div>

                            <div>

                                <div
                                    class="fw-bold"
                                    style="font-size:12px;">

                                    Modification du profil

                                </div>

                                <p
                                    class="text-muted mb-0 mt-1"
                                    style="font-size:10px;">

                                    Les informations existantes sont
                                    conservées tant que vous ne les
                                    modifiez pas. Une nouvelle capture
                                    faciale remplacera l'enregistrement
                                    précédent.

                                </p>

                            </div>

                        </div>

                    </div>

                </div>



                {{-- ACTIONS --}}

                <div class="dashboard-card">

                    <div class="card-body-custom">

                        <button
                            type="submit"
                            class="btn btn-primary w-100 py-2 mb-2">

                            <i class="bi bi-check2-circle me-2"></i>

                            Enregistrer les modifications

                        </button>


                        <a
                            href="{{ route('employees.index') }}"
                            class="btn btn-light border w-100">

                            Annuler

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </form>

</div>

@endsection


@push('scripts')

<script>

    /*
    ======================================
    SIMULATION DU SCAN RFID
    ======================================
    */

    document
        .getElementById('scanRfid')
        .addEventListener('click', function () {

            const button = this;

            button.disabled = true;

            button.innerHTML = `
                <span
                    class="spinner-border spinner-border-sm me-2">
                </span>

                Lecture du badge...
            `;


            setTimeout(function () {

                document
                    .getElementById('rfid_uid')
                    .value = 'A4:B7:23:91';


                document
                    .getElementById('rfidStatus')
                    .classList
                    .remove('d-none');


                document
                    .getElementById('summaryRfid')
                    .className =
                    'badge bg-success';


                document
                    .getElementById('summaryRfid')
                    .innerText =
                    'Badge associé';


                button.disabled = false;

                button.innerHTML = `
                    <i class="bi bi-check-circle me-2"></i>
                    Badge détecté
                `;

            }, 1500);

        });



    /*
    ======================================
    CAPTURE FACIALE ESP32-CAM
    ======================================
    */

    document
        .getElementById('captureFace')
        .addEventListener('click', async function () {

            const button = this;

            const preview =
                document.getElementById('facePreview');

            const status =
                document.getElementById('faceStatus');


            const cameraUrl =
                'http://192.168.1.156/capture';


            button.disabled = true;

            button.innerHTML = `
                <span
                    class="spinner-border spinner-border-sm me-2">
                </span>

                Capture en cours...
            `;


            status.className =
                'badge bg-warning text-dark';

            status.innerHTML = `
                <i class="bi bi-camera me-1"></i>
                Capture en cours...
            `;


            preview.innerHTML = `

                <div class="text-center">

                    <div
                        class="spinner-border text-primary"
                        role="status">
                    </div>

                    <div
                        class="small text-muted mt-2">

                        Capture du visage...

                    </div>

                </div>

            `;


            try {

                const response =
                    await fetch(cameraUrl);


                if (!response.ok) {

                    throw new Error(
                        'Erreur lors de la capture ' +
                        response.status
                    );

                }


                const imageBlob =
                    await response.blob();


                const imageUrl =
                    URL.createObjectURL(imageBlob);


                /*
                ======================================
                CONVERSION EN BASE64
                ======================================
                */

                const reader =
                    new FileReader();


                reader.readAsDataURL(imageBlob);


                reader.onloadend =
                    function () {

                        document
                            .getElementById('face_photo')
                            .value =
                            reader.result;

                    };


                /*
                ======================================
                APERCU BIOMETRIQUE
                ======================================
                */

                preview.innerHTML = `

                    <div
                        class="w-100 h-100 rounded-4 overflow-hidden">

                        <img
                            src="${imageUrl}"
                            class="w-100 h-100"
                            style="object-fit:cover;"
                            alt="Visage capturé">

                    </div>

                `;


                /*
                ======================================
                APERCU PROFIL
                ======================================
                */

                const profilePreview =
                    document.getElementById(
                        'profilePreview'
                    );


                profilePreview.innerHTML = `

                    <img
                        src="${imageUrl}"
                        class="w-100 h-100 rounded-circle"
                        style="object-fit:cover;"
                        alt="Visage capturé">

                `;


                /*
                ======================================
                STATUT
                ======================================
                */

                status.className =
                    'badge bg-success';

                status.innerHTML = `
                    <i class="bi bi-check-circle-fill me-1"></i>
                    Nouvelle capture effectuée
                `;


                document
                    .getElementById('summaryFace')
                    .className =
                    'badge bg-success';


                document
                    .getElementById('summaryFace')
                    .innerText =
                    'Enregistrée';


                /*
                ======================================
                BOUTON
                ======================================
                */

                button.disabled = false;

                button.innerHTML = `
                    <i class="bi bi-camera-fill me-2"></i>
                    Reprendre la photo
                `;


                console.log(
                    'Nouvelle capture faciale réussie'
                );

            } catch (error) {

                console.error(
                    'Erreur lors de la capture faciale:',
                    error
                );


                preview.innerHTML = `

                    <div
                        class="text-center text-danger">

                        <i
                            class="bi bi-camera-video-off"
                            style="font-size:45px;">
                        </i>

                        <div
                            class="small mt-2">

                            Impossible de contacter
                            la caméra

                        </div>

                    </div>

                `;


                status.className =
                    'badge bg-danger';


                status.innerHTML = `
                    <i class="bi bi-x-circle-fill me-1"></i>
                    Échec de la capture
                `;


                button.disabled = false;


                button.innerHTML = `
                    <i class="bi bi-camera-fill me-2"></i>
                    Reprendre la photo
                `;

            }

        });

</script>

@endpush