@extends('layouts.admin')

@section('title', 'Ajouter un employé | SecureAccess')


@section('content')

<div class="content employee-create-page">

    {{-- EN-TÊTE --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <div class="d-flex align-items-center gap-2 mb-1">

                <a href="{{ route('employees.index') }}"
                   class="text-muted">

                    <i class="bi bi-arrow-left"></i>

                </a>

                <h1 class="page-title">
                    Ajouter un employé
                </h1>

            </div>

            <div class="page-subtitle">

                Enregistrer un nouvel employé
                et ses informations biométriques.

            </div>

        </div>

    </div>


    <form method="POST" action="{{ route('employees.store') }}" enctype="multipart/form-data">

        @csrf

        @if ($errors->any())
            <div class="alert alert-danger">
               <div class="fw-bold mb-2">
                 <i class="bi bi-exclamation-triangle-fill me-2"></i>
                 impossible d'enregistre l'employe
               </div>

               <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
               </ul>
            </div>
        @endif    

        <input type="hidden" name="face_photo" id="face_photo">

        <div class="row g-4">


            {{-- ============================
                 COLONNE PRINCIPALE
            ============================= --}}

            <div class="col-xl-8">


                {{-- INFORMATIONS PERSONNELLES --}}

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

                                    <option>
                                        Direction
                                    </option>

                                    <option>
                                        Ressources Humaines
                                    </option>

                                    <option>
                                        Informatique
                                    </option>

                                    <option>
                                        Comptabilité
                                    </option>

                                    <option>
                                        Logistique
                                    </option>

                                    <option>
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
                                >

                            </div>


                        </div>

                    </div>

                </div>



                {{-- ============================
                     RFID
                ============================= --}}

                <div class="dashboard-card mb-4">

                    <div class="card-header-custom">

                        <div>

                            <div class="card-title-custom">

                                Identification RFID

                            </div>

                            <div
                                class="text-muted"
                                style="font-size:10px;">

                                Association du badge à l'employé

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
                                        placeholder="Scannez votre badge RFID"
                                        readonly
                                        required
                                    >

                                    <input
                                        type="hidden"
                                        name="rfid_scan_token"
                                        id="rfid_scan_token"
                                    >

                                </div>

                                <div class="form-text">

                                    Scannez le badge avec le lecteur
                                    RFID pour récupérer automatiquement
                                    son UID.

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
                            class="mt-3 d-none">

                            <div
                                class="alert alert-success mb-0">

                                <i class="bi bi-check-circle-fill me-2"></i>

                                Badge RFID détecté avec succès.

                            </div>

                        </div>

                    </div>

                </div>



                {{-- ============================
                     BIOMETRIE
                ============================= --}}

                <div class="dashboard-card mb-4">

                    <div class="card-header-custom">

                        <div>

                            <div class="card-title-custom">

                                Reconnaissance faciale

                            </div>

                            <div
                                class="text-muted"
                                style="font-size:10px;">

                                Enregistrement du visage de l'employé

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
                                    "
                                >

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

                                </div>

                            </div>


                            <div class="col-md-7">

                                <h6 class="fw-bold">

                                    Capture biométrique

                                </h6>

                                <p
                                    class="text-muted"
                                    style="font-size:11px;">

                                    Le visage sera associé au profil
                                    de l'employé afin de permettre
                                    la double authentification
                                    RFID + reconnaissance faciale.

                                </p>


                                <button
                                    type="button"
                                    class="btn btn-primary"
                                    id="captureFace">

                                    <i class="bi bi-camera-fill me-2"></i>

                                    Capturer le visage

                                </button>

                                <input type="file"
                                    name="photo"
                                    id="facephoto"
                                    hidden
                                >

                                <div class="mt-3">

                                    <span
                                        id="faceStatus"
                                        class="badge bg-secondary">

                                        Non enregistré

                                    </span>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>



                {{-- ============================
                     HORAIRE
                ============================= --}}

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
                                    value="08:00"
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
                                    value="17:00"
                                >

                            </div>


                            <div class="col-md-4">

                                <label class="form-label fw-semibold">

                                    Tolérance retard

                                </label>

                                <select
                                    name="tolerance"
                                    class="form-select">

                                    <option value="5">
                                        5 minutes
                                    </option>

                                    <option value="10" selected>
                                        10 minutes
                                    </option>

                                    <option value="15">
                                        15 minutes
                                    </option>

                                    <option value="30">
                                        30 minutes
                                    </option>

                                </select>

                            </div>

                        </div>

                    </div>

                </div>

            </div>



            {{-- ============================
                 COLONNE DROITE
            ============================= --}}

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
                                "
                            >

                                <i
                                    class="bi bi-person-fill"
                                    style="font-size:35px;">
                                </i>

                            </div>

                            <div
                                class="fw-bold mt-3">

                                Nouvel employé

                            </div>

                            <div
                                class="text-muted"
                                style="font-size:10px;">

                                Profil en cours de création

                            </div>

                        </div>


                        <div class="border-top pt-3">


                            <div class="d-flex justify-content-between mb-3">

                                <span
                                    class="text-muted"
                                    style="font-size:11px;">

                                    Badge RFID

                                </span>

                                <span
                                    id="summaryRfid"
                                    class="badge bg-secondary">

                                    Non associé

                                </span>

                            </div>


                            <div class="d-flex justify-content-between mb-3">

                                <span
                                    class="text-muted"
                                    style="font-size:11px;">

                                    Biométrie

                                </span>

                                <span
                                    id="summaryFace"
                                    class="badge bg-secondary">

                                    Non enregistrée

                                </span>

                            </div>


                            <div class="d-flex justify-content-between">

                                <span
                                    class="text-muted"
                                    style="font-size:11px;">

                                    Statut

                                </span>

                                <span
                                    class="badge bg-success">

                                    Actif

                                </span>

                            </div>


                        </div>

                    </div>

                </div>



                {{-- AIDE --}}

                <div class="dashboard-card mb-4">

                    <div class="card-body-custom">

                        <div class="d-flex gap-3">

                            <div
                                class="text-primary">

                                <i class="bi bi-info-circle-fill fs-4"></i>

                            </div>

                            <div>

                                <div
                                    class="fw-bold"
                                    style="font-size:12px;">

                                    Double authentification

                                </div>

                                <p
                                    class="text-muted mb-0 mt-1"
                                    style="font-size:10px;">

                                    Un pointage n'est validé que
                                    lorsque le badge RFID et
                                    l'identité faciale correspondent
                                    au même employé.

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

                            <i class="bi bi-person-plus-fill me-2"></i>

                            Enregistrer l'employé

                        </button>


               <a
    href="{{ route('employees.index') }}"
    class="btn btn-light border w-100">
    <i class="bi bi-x-lg me-1"></i>
    Annuler
</a>

                    </div>

                </div>

            </div>

        </div>

    </form>

</div>

@endsection


<style>
/* =========================================================
   PAGE AJOUT EMPLOYÉ — RESPONSIVE
========================================================= */

.employee-create-page .dashboard-card {
    overflow: hidden;
}

.employee-create-page .card-header-custom {
    min-height: 64px;
}

.employee-create-page .form-label {
    font-size: 11px;
    margin-bottom: 6px;
}

.employee-create-page .form-text {
    font-size: 10px;
    color: var(--muted);
}

.employee-create-page .input-group-text {
    background: #f9fafb;
    border-color: var(--border);
    color: var(--muted);
}

.employee-create-page #facePreview {
    max-width: 180px;
    max-height: 180px;
}

.employee-create-page #profilePreview {
    overflow: hidden;
}

.employee-create-page .summary-item {
    font-size: 11px;
}

.employee-create-page .action-card .btn {
    min-height: 40px;
}


/* =========================================================
   TABLETTE
========================================================= */

@media (max-width: 991px) {

    .employee-create-page .col-xl-8,
    .employee-create-page .col-xl-4 {
        width: 100%;
    }

    .employee-create-page .col-md-6,
    .employee-create-page .col-md-5,
    .employee-create-page .col-md-7,
    .employee-create-page .col-md-8,
    .employee-create-page .col-md-4 {
        width: 100%;
    }

    .employee-create-page #facePreview {
        margin-left: auto;
        margin-right: auto;
    }

    .employee-create-page .face-capture-content {
        text-align: center;
    }

}


/* =========================================================
   MOBILE
========================================================= */

@media (max-width: 768px) {

    .employee-create-page {
        padding-bottom: 20px;
    }

    .employee-create-page
    > .d-flex.justify-content-between.align-items-center.mb-4 {
        flex-direction: column;
        align-items: flex-start !important;
        gap: 14px;
    }

    .employee-create-page
    > .d-flex.justify-content-between.align-items-center.mb-4
    > div {
        width: 100%;
    }

    .employee-create-page
    > .d-flex.justify-content-between.align-items-center.mb-4
    > button {
        width: 100%;
    }

    .employee-create-page .dashboard-card {
        margin-bottom: 16px !important;
        border-radius: 12px;
    }

    .employee-create-page .card-header-custom {
        padding: 15px 16px;
    }

    .employee-create-page .card-body-custom {
        padding: 16px;
    }

    .employee-create-page .card-title-custom {
        font-size: 13px;
    }

    .employee-create-page .form-label {
        font-size: 10px;
    }

    .employee-create-page .form-control,
    .employee-create-page .form-select {
        min-height: 38px;
        font-size: 11px;
    }

    .employee-create-page .input-group-text {
        font-size: 12px;
    }

    /* RFID */

    .employee-create-page #scanRfid {
        width: 100%;
        min-height: 38px;
    }

    /* BIOMÉTRIE */

    .employee-create-page #facePreview {
        width: 160px !important;
        height: 160px !important;
    }

    .employee-create-page .face-capture-content {
        text-align: center;
    }

    .employee-create-page #captureFace {
        width: 100%;
        min-height: 40px;
    }

    /* RÉSUMÉ */

    .employee-create-page #profilePreview {
        width: 72px !important;
        height: 72px !important;
    }

    /* ACTIONS */

    .employee-create-page .action-card .btn {
        width: 100%;
        min-height: 40px;
    }

}


/* =========================================================
   TRÈS PETITS ÉCRANS
========================================================= */

@media (max-width: 480px) {

    .employee-create-page .page-title {
        font-size: 20px;
    }

    .employee-create-page .page-subtitle {
        font-size: 10px;
    }

    .employee-create-page .dashboard-card {
        border-radius: 10px;
    }

    .employee-create-page .card-header-custom {
        padding: 13px 14px;
    }

    .employee-create-page .card-body-custom {
        padding: 14px;
    }

    .employee-create-page .card-title-custom {
        font-size: 12px;
    }

    .employee-create-page .form-label {
        font-size: 9px;
    }

    .employee-create-page .form-control,
    .employee-create-page .form-select {
        min-height: 37px;
        font-size: 10px;
    }

    .employee-create-page .form-text {
        font-size: 9px;
    }

    .employee-create-page #facePreview {
        width: 145px !important;
        height: 145px !important;
    }

    .employee-create-page #facePreview i {
        font-size: 38px !important;
    }

    .employee-create-page .alert {
        font-size: 10px;
    }

}
</style>


@push('scripts')

<script>


/*
======================================
SCAN RFID RÉEL
======================================
*/

document
    .getElementById('scanRfid')
    .addEventListener('click', async function () {

        const button = this;

        const uidInput =
            document.getElementById('rfid_uid');

        const tokenInput =
            document.getElementById('rfid_scan_token');

        const statusBox =
            document.getElementById('rfidStatus');

        const summaryRfid =
            document.getElementById('summaryRfid');


        /*
        ======================================
        ÉTAT INITIAL
        ======================================
        */

        button.disabled = true;

        uidInput.value = '';
        tokenInput.value = '';

        button.innerHTML = `
            <span
                class="spinner-border spinner-border-sm me-2">
            </span>

            En attente du badge...
        `;


        statusBox.classList.remove('d-none');

        statusBox.innerHTML = `
            <div class="alert alert-warning mb-0">

                <i class="bi bi-rss me-2"></i>

                Présentez votre badge devant
                le lecteur RFID...

            </div>
        `;


        /*
        ======================================
        DÉMARRER LA SESSION RFID
        ======================================
        */

        try {

            const startResponse = await fetch(
                '{{ url('/api/esp32/registration/start') }}',
                {
                    method: 'POST',

                    headers: {
                        'Accept': 'application/json'
                    }
                }
            );


            if (!startResponse.ok) {

                throw new Error(
                    'Impossible de démarrer le scan RFID.'
                );
            }


            const startData =
                await startResponse.json();


            if (
                !startData.success ||
                !startData.token
            ) {

                throw new Error(
                    'Session RFID invalide.'
                );
            }


            /*
            ==================================
            CONSERVER LE TOKEN
            ==================================
            */

            tokenInput.value =
                startData.token;


            /*
            ==================================
            ATTENDRE LE SCAN
            ==================================
            */

            let tentatives = 0;

            const maxTentatives = 60;


            const polling = setInterval(
                async function () {

                    tentatives++;


                    try {

                        const response =
                            await fetch(
                                '{{ url('/api/esp32/registration/latest') }}?token=' +
                                encodeURIComponent(
                                    startData.token
                                ),
                                {
                                    headers: {
                                        'Accept':
                                            'application/json'
                                    }
                                }
                            );


                        if (!response.ok) {
                            return;
                        }


                        const data =
                            await response.json();


                        /*
                        ==========================
                        PAS ENCORE DE BADGE
                        ==========================
                        */

                        if (!data.scanned) {
                            return;
                        }


                        /*
                        ==========================
                        ARRÊTER LE POLLING
                        ==========================
                        */

                        clearInterval(polling);


                        /*
                        ==========================
                        BADGE DÉJÀ UTILISÉ
                        ==========================
                        */

                        if (data.already_used) {

                            uidInput.value = '';

                            summaryRfid.className =
                                'badge bg-danger';

                            summaryRfid.innerText =
                                'Badge déjà utilisé';


                            statusBox.innerHTML = `
                                <div class="alert alert-danger mb-0">

                                    <i class="bi bi-exclamation-triangle-fill me-2"></i>

                                    <strong>
                                        Badge déjà associé.
                                    </strong>

                                    <br>

                                    Employé :
                                    ${data.employee.prenom}
                                    ${data.employee.nom}

                                    <br>

                                    Matricule :
                                    ${data.employee.matricule}

                                </div>
                            `;


                            button.disabled = false;

                            button.innerHTML = `
                                <i class="bi bi-rss me-2"></i>

                                Scanner un autre badge
                            `;

                            return;
                        }


                        /*
                        ==========================
                        BADGE DISPONIBLE
                        ==========================
                        */

                        uidInput.value =
                            data.uid;


                        summaryRfid.className =
                            'badge bg-success';

                        summaryRfid.innerText =
                            'Badge associé';


                        statusBox.innerHTML = `
                            <div class="alert alert-success mb-0">

                                <i class="bi bi-check-circle-fill me-2"></i>

                                Badge RFID détecté :

                                <strong>
                                    ${data.uid}
                                </strong>

                            </div>
                        `;


                        button.disabled = false;

                        button.innerHTML = `
                            <i class="bi bi-check-circle me-2"></i>

                            Badge détecté
                        `;


                        console.log(
                            '✅ UID RFID reçu :',
                            data.uid
                        );


                    } catch (error) {

                        console.error(
                            'Erreur pendant le scan RFID :',
                            error
                        );

                    }


                    /*
                    ==========================
                    TIMEOUT
                    ==========================
                    */

                    if (
                        tentatives >= maxTentatives
                    ) {

                        clearInterval(
                            polling
                        );


                        button.disabled = false;

                        button.innerHTML = `
                            <i class="bi bi-rss me-2"></i>

                            Scanner le badge
                        `;


                        statusBox.innerHTML = `
                            <div class="alert alert-danger mb-0">

                                <i class="bi bi-clock-history me-2"></i>

                                Aucun badge détecté.
                                Veuillez réessayer.

                            </div>
                        `;

                    }

                },
                1000
            );


        } catch (error) {

            console.error(
                'Erreur RFID :',
                error
            );


            button.disabled = false;

            button.innerHTML = `
                <i class="bi bi-rss me-2"></i>

                Scanner le badge
            `;


            statusBox.innerHTML = `
                <div class="alert alert-danger mb-0">

                    <i class="bi bi-x-circle-fill me-2"></i>

                    Impossible de communiquer
                    avec le système RFID.

                </div>
            `;

        }

    });
   



    /*
    ======================================
    SIMULATION CAPTURE FACIALE
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

                // adresse de l'esp32-cam sur le réseau local
                const cameraUrl = 'http://192.168.1.156/capture';

                // capture en cours
                button.disabled = true;
                button.innerHTML = `
                    <span
                        class="spinner-border spinner-border-sm me-2">
                    </span>

                    Capture en cours...
                `;

                status.className =
                    'badge bg-warning text-dark';
                status.innerHTML = `<i class="bi bi-camera me-1"></i>
                    capture en cours...`;

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
                // demande de capture a l'esp32-cam
                const response = await fetch(cameraUrl);

                // verification de la reponse HTTP
                if (!response.ok) {
                    throw new Error('Erreur lors de la capture'+ response.status);
                }

                // recuperation de l'image en tant que blob
                const imageBlob = await response.blob();

                // affichage de de la photo
                const imageUrl = URL.createObjectURL(imageBlob);

                // conversion de l'image en base64 pour l'envoi au serveur
                const reader = new FileReader();
                reader.readAsDataURL(imageBlob);
                reader.onloadend = function () {
                    const base64data = reader.result;

                    // stockage de l'image en base64 dans le champ caché
                    document.getElementById('face_photo').value = base64data;
                };

                // apercu biometrique
                
                preview.innerHTML = `

                    <div
                        class="w-100 h-100 rounded-4 overflow-hidden">

                        <img
                            src="${imageUrl}"
                            class="w-100 h-100"
                            style="object-fit:cover;"
                            alt="Visage capturé"
                        >

                    </div>

                `;

                const profilePreview = document.getElementById('profilePreview');
                profilePreview.innerHTML = `
                    <img 
                        src="${imageUrl}" 
                        class="w-100 h-100 rounded-circle" 
                        style="object-fit:cover;" 
                        alt="Visage capturé"
                    >`;


                // statut
                status.className =
                    'badge bg-success';
                status.innerHTML = `
                    <i class="bi bi-check-circle-fill me-1"></i>
                    Visage enregistré
                `;

                // resume

                document
                    .getElementById('summaryFace')
                    .className =
                    'badge bg-success';

                    document
                    .getElementById('summaryFace')
                    .innerText =
                    'Enregistrée';


                    // bouton

                button.disabled = false;
                button.innerHTML = `
                    <i class="bi bi-camera-fill me-2"></i>
                    Reprendre la photo
                `;

                console.log('Capture faciale réussie');
            } catch (error) {
                console.error('Erreur lors de la capture faciale:', error);

                preview.innerHTML = `

                    <div class="text-center text-danger">

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
                    Echec de la capture
                `;

                button.disabled = false;
                button.innerHTML = `
                    <i class="bi bi-camera-fill me-2"></i>
                    Capturer le visage
                `;
            }

        });

</script>

@endpush