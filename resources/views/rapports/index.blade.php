@extends('layouts.admin')

@section('title', 'Rapports')

@section('content')

<div class="content rapports-page">

    {{-- EN-TÊTE --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h1 class="page-title">
                Rapports
            </h1>

            <div class="page-subtitle">
                Génération et consultation des rapports de présence du personnel
            </div>
        </div>

    </div>


   

    {{-- GÉNÉRATEUR --}}
<div class="dashboard-card mb-4">

    <div class="card-header-custom">

        <div>
            <h2 class="card-title-custom">
                <i class="bi bi-file-earmark-bar-graph me-2"></i>
                Générer un rapport
            </h2>

            <div class="text-muted mt-1" style="font-size:10px;">
                Sélectionnez les critères du rapport à générer
            </div>
        </div>

        {{-- Actualiser le formulaire --}}
        <a
            href="{{ route('rapports.index') }}"
            class="btn btn-light border"
            title="Réinitialiser les filtres"
            style="
                width:36px;
                height:36px;
                display:flex;
                align-items:center;
                justify-content:center;
                padding:0;
            "
        >
            <i class="bi bi-arrow-clockwise"></i>
        </a>

    </div>


    <div class="card-body-custom">

        <form
            method="GET"
            action="{{ route('rapports.index') }}"
        >

            <div class="row g-3">

                {{-- TYPE DE RAPPORT --}}
                <div class="col-lg-4 col-md-6">

                    <label class="form-label report-label">
                        Type de rapport
                    </label>

                    <select
                        name="type_rapport"
                        class="form-select report-input"
                    >

                        <option
                            value="pointage"
                            {{ request('type_rapport', 'pointage') === 'pointage' ? 'selected' : '' }}
                        >
                            Rapport des pointages
                        </option>

                        <option
                            value="personnel"
                            {{ request('type_rapport') === 'personnel' ? 'selected' : '' }}
                        >
                            Rapport d'un personnel
                        </option>

                        <option
                            value="retard"
                            {{ request('type_rapport') === 'retard' ? 'selected' : '' }}
                        >
                            Rapport des retards
                        </option>

                        <option
                            value="entrees_sortie"
                            {{ request('type_rapport') === 'entrees_sortie' ? 'selected' : '' }}
                        >
                            Entrées et sorties
                        </option>

                    </select>

                </div>


                {{-- EMPLOYÉ --}}
                <div class="col-lg-4 col-md-6">

                    <label class="form-label report-label">
                        Employé
                    </label>

                    <select
                        name="employe"
                        class="form-select report-input"
                    >

                        <option value="tous">
                            Tous les employés
                        </option>

                        @foreach($users as $user)

                            <option
                                value="{{ $user->id }}"
                                {{ request('employe') == $user->id ? 'selected' : '' }}
                            >

                                {{ $user->nom }} {{ $user->prenom }}

                                @if(!empty($user->matricule))
                                    — {{ $user->matricule }}
                                @endif

                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- PÉRIODE --}}
                <div class="col-lg-4 col-md-6">

                    <label class="form-label report-label">
                        Période
                    </label>

                    <select
                        name="periode"
                        id="periode"
                        class="form-select report-input"
                    >

                        <option
                            value="aujourd_hui"
                            {{ request('periode', 'aujourd_hui') === 'aujourd_hui' ? 'selected' : '' }}
                        >
                            Aujourd'hui
                        </option>

                        <option
                            value="hier"
                            {{ request('periode') === 'hier' ? 'selected' : '' }}
                        >
                            Hier
                        </option>

                        <option
                            value="semaine"
                            {{ request('periode') === 'semaine' ? 'selected' : '' }}
                        >
                            Cette semaine
                        </option>

                        <option
                            value="mois"
                            {{ request('periode') === 'mois' ? 'selected' : '' }}
                        >
                            Ce mois
                        </option>

                        <option
                            value="personnalisee"
                            {{ request('periode') === 'personnalisee' ? 'selected' : '' }}
                        >
                            Personnalisée
                        </option>

                    </select>

                </div>


                {{-- DATE DÉBUT --}}
                <div class="col-lg-4 col-md-6">

                    <label class="form-label report-label">
                        Date de début
                    </label>

                    <input
                        type="date"
                        name="date_debut"
                        id="date_debut"
                        class="form-control report-input"
                        value="{{ $dateDebut ?? request('date_debut') }}"
                    >

                </div>


                {{-- DATE FIN --}}
                <div class="col-lg-4 col-md-6">

                    <label class="form-label report-label">
                        Date de fin
                    </label>

                    <input
                        type="date"
                        name="date_fin"
                        id="date_fin"
                        class="form-control report-input"
                        value="{{ $dateFin ?? request('date_fin') }}"
                    >

                </div>


                {{-- BOUTONS --}}
                <div class="col-lg-4 col-md-6">

                    <div class="d-flex justify-content-end gap-2 mt-2">

                        <button
                            type="submit"
                            name="previsualiser"
                            value="1"
                            class="btn btn-light border"
                        >
                            <i class="bi bi-eye me-1"></i>
                            Prévisualiser
                        </button>

                        <a
                            href="{{ route('rapports.pdf', request()->query()) }}"
                            class="btn btn-primary"
                            target="_blank"
                        >
                        <i class="bi bi-file-earmark-pdf me-1"></i>
                            Générer PDF
                        </a>

                    </div>

                </div>

            </div>

        </form>

    </div>

</div>

        @if($previsualisation)

    <div class="dashboard-card mb-4">

        <div class="card-header-custom">

            <div>
                <h2 class="card-title-custom">
                    <i class="bi bi-eye me-2"></i>
                    Prévisualisation du rapport
                </h2>

                <div
                    class="text-muted mt-1"
                    style="font-size:10px;"
                >
                    Résultats correspondant aux critères sélectionnés
                </div>
            </div>

            <span
                class="badge"
                style="
                    background:#dbeafe;
                    color:#2563eb;
                    font-size:9px;
                "
            >
                APERÇU
            </span>

        </div>


        <div class="card-body-custom">

            {{-- INFORMATIONS DU RAPPORT --}}
            <div class="row g-3 mb-4">

                <div class="col-lg-3 col-md-6">

                    <div class="stat-card">

                        <div class="stat-label">
                            Présents
                        </div>

                        <div class="stat-number">
                            {{ $totalPresents }}
                        </div>

                    </div>

                </div>


                <div class="col-lg-3 col-md-6">

                    <div class="stat-card">

                        <div class="stat-label">
                            Entrées
                        </div>

                        <div class="stat-number">
                            {{ $totalEntrees }}
                        </div>

                    </div>

                </div>


                <div class="col-lg-3 col-md-6">

                    <div class="stat-card">

                        <div class="stat-label">
                            Sorties
                        </div>

                        <div class="stat-number">
                            {{ $totalSorties }}
                        </div>

                    </div>

                </div>


                <div class="col-lg-3 col-md-6">

                    <div class="stat-card">

                        <div class="stat-label">
                            Retards
                        </div>

                        <div class="stat-number">
                            {{ $totalRetards }}
                        </div>

                    </div>

                </div>

            </div>


            {{-- INFORMATIONS --}}
            <div
                class="mb-3"
                style="font-size:11px;"
            >

                <strong>Période :</strong>

                {{ \Carbon\Carbon::parse($dateDebut)->format('d/m/Y') }}

                au

                {{ \Carbon\Carbon::parse($dateFin)->format('d/m/Y') }}

            </div>


            {{-- TABLEAU --}}
            <div class="table-responsive">

                <table class="table attendance-table align-middle">

                    <thead>

                        <tr>

                            <th>
                                Employé
                            </th>

                            <th>
                                Matricule
                            </th>

                            <th>
                                Date
                            </th>

                            <th>
                                Heure
                            </th>

                            <th>
                                Type
                            </th>

                            <th>
                                Statut
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($pointages as $pointage)

                            <tr>

                                <td>

                                    @if($pointage->user)

                                        <div class="fw-semibold">
                                            {{ $pointage->user->nom }}
                                            {{ $pointage->user->prenom }}
                                        </div>

                                    @else

                                        <span class="text-muted">
                                            Utilisateur inconnu
                                        </span>

                                    @endif

                                </td>


                                <td>

                                    {{ $pointage->user->matricule ?? '—' }}

                                </td>


                                <td>

                                    {{ \Carbon\Carbon::parse($pointage->date)->format('d/m/Y') }}

                                </td>


                                <td>

                                    {{ $pointage->heure }}

                                </td>


                                <td>

                                    @if($pointage->type === 'ENTREE')

                                        <span
                                            class="badge"
                                            style="
                                                background:#dcfce7;
                                                color:#16a34a;
                                            "
                                        >
                                            Entrée
                                        </span>

                                    @else

                                        <span
                                            class="badge"
                                            style="
                                                background:#dbeafe;
                                                color:#2563eb;
                                            "
                                        >
                                            Sortie
                                        </span>

                                    @endif

                                </td>


                                <td>

                                    @if($pointage->status === 'RETARD')

                                        <span
                                            class="badge"
                                            style="
                                                background:#fef3c7;
                                                color:#d97706;
                                            "
                                        >
                                            Retard
                                        </span>

                                    @else

                                        <span
                                            class="badge"
                                            style="
                                                background:#dcfce7;
                                                color:#16a34a;
                                            "
                                        >
                                            Normal
                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="6"
                                    class="text-center py-4"
                                >

                                    <div
                                        class="text-muted"
                                        style="font-size:11px;"
                                    >
                                        Aucun pointage ne correspond
                                        aux critères sélectionnés.
                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

@endif


  
{{-- HISTORIQUE DES RAPPORTS --}}
<div class="dashboard-card">

    <div class="card-header-custom">
        <div>
            <h2 class="card-title-custom">
                <i class="bi bi-folder2-open me-2"></i>
                Rapports générés
            </h2>

            <div class="text-muted mt-1" style="font-size:10px;">
                Historique des documents PDF générés
            </div>
        </div>

        <span
            class="badge"
            style="
                background:#fee2e2;
                color:#dc2626;
                font-size:9px;
            "
        >
            {{ $rapports->count() }} RAPPORT{{ $rapports->count() > 1 ? 'S' : '' }}
        </span>
    </div>

    <div class="card-body-custom">

        @if($rapports->count() > 0)

            <div class="table-responsive">

                <table class="table attendance-table align-middle">

                    <thead>
                        <tr>
                            <th>Rapport</th>
                            <th>Employé</th>
                            <th>Période</th>
                            <th>Pointages</th>
                            <th>Généré le</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($rapports as $rapport)

                            <tr>

                                {{-- TYPE DE RAPPORT --}}
                                <td>

                                    @php
                                        $types = [
                                            'pointage' => [
                                                'label' => 'Pointages',
                                                'icon' => 'bi-list-check',
                                                'bg' => '#dbeafe',
                                                'color' => '#2563eb'
                                            ],

                                            'personnel' => [
                                                'label' => 'Personnel',
                                                'icon' => 'bi-person-vcard',
                                                'bg' => '#e0f2fe',
                                                'color' => '#0284c7'
                                            ],

                                            'retard' => [
                                                'label' => 'Retards',
                                                'icon' => 'bi-clock-history',
                                                'bg' => '#fef3c7',
                                                'color' => '#d97706'
                                            ],

                                            'entrees_sortie' => [
                                                'label' => 'Entrées / Sorties',
                                                'icon' => 'bi-arrow-left-right',
                                                'bg' => '#dcfce7',
                                                'color' => '#16a34a'
                                            ],
                                        ];

                                        $type = $types[$rapport->type_rapport]
                                            ?? [
                                                'label' => ucfirst($rapport->type_rapport),
                                                'icon' => 'bi-file-earmark-pdf',
                                                'bg' => '#f3f4f6',
                                                'color' => '#6b7280'
                                            ];
                                    @endphp

                                    <div class="d-flex align-items-center gap-2">

                                        <div
                                            style="
                                                width:34px;
                                                height:34px;
                                                border-radius:8px;
                                                background:{{ $type['bg'] }};
                                                color:{{ $type['color'] }};
                                                display:flex;
                                                align-items:center;
                                                justify-content:center;
                                                flex-shrink:0;
                                            "
                                        >
                                            <i class="bi {{ $type['icon'] }}"></i>
                                        </div>

                                        <div>
                                            <div
                                                class="fw-semibold"
                                                style="font-size:11px;"
                                            >
                                                {{ $type['label'] }}
                                            </div>

                                            <div
                                                class="text-muted"
                                                style="font-size:9px;"
                                            >
                                                {{ $rapport->nom_fichier }}
                                            </div>
                                        </div>

                                    </div>

                                </td>


                                {{-- EMPLOYÉ --}}
                                <td>

                                    @if($rapport->user)

                                        <div
                                            class="fw-semibold"
                                            style="font-size:11px;"
                                        >
                                            {{ $rapport->user->nom }}
                                            {{ $rapport->user->prenom }}
                                        </div>

                                        <div
                                            class="text-muted"
                                            style="font-size:9px;"
                                        >
                                            {{ $rapport->user->matricule }}
                                        </div>

                                    @else

                                        <span
                                            class="badge"
                                            style="
                                                background:#f3f4f6;
                                                color:#6b7280;
                                                font-size:9px;
                                            "
                                        >
                                            Tous les employés
                                        </span>

                                    @endif

                                </td>


                                {{-- PÉRIODE --}}
                                <td>

                                    <div style="font-size:10px;">

                                        {{ $rapport->date_debut->format('d/m/Y') }}

                                        @if($rapport->date_debut->format('Y-m-d')
                                            !== $rapport->date_fin->format('Y-m-d'))

                                            <span class="text-muted">→</span>

                                            {{ $rapport->date_fin->format('d/m/Y') }}

                                        @endif

                                    </div>

                                </td>


                                {{-- NOMBRE DE POINTAGES --}}
                                <td>

                                    <span
                                        class="badge"
                                        style="
                                            background:#f3f4f6;
                                            color:#374151;
                                            font-size:9px;
                                        "
                                    >
                                        {{ $rapport->nombre_pointages }}
                                        pointage{{ $rapport->nombre_pointages > 1 ? 's' : '' }}
                                    </span>

                                </td>


                                {{-- DATE DE GÉNÉRATION --}}
                                <td>

                                    <div style="font-size:10px;">
                                        {{ $rapport->created_at->format('d/m/Y') }}
                                    </div>

                                    <div
                                        class="text-muted"
                                        style="font-size:9px;"
                                    >
                                        {{ $rapport->created_at->format('H:i') }}
                                    </div>

                                </td>


                                {{-- ACTION --}}
                                <td class="text-end">

                                    <a
                                        href="{{ route('rapports.pdf', [
                                            'type_rapport' => $rapport->type_rapport,
                                            'employe' => $rapport->user_id ?? 'tous',
                                            'periode' => 'personnalisee',
                                            'date_debut' => $rapport->date_debut->format('Y-m-d'),
                                            'date_fin' => $rapport->date_fin->format('Y-m-d'),
                                        ]) }}"
                                        target="_blank"
                                        class="btn btn-sm btn-light border"
                                        title="Regénérer ce rapport"
                                    >
                                        <i class="bi bi-file-earmark-pdf me-1"></i>
                                        PDF
                                    </a>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @else

            {{-- AUCUN RAPPORT --}}
            <div class="text-center py-5">

                <div
                    class="mx-auto mb-3"
                    style="
                        width:60px;
                        height:60px;
                        border-radius:14px;
                        background:#f3f4f6;
                        display:flex;
                        align-items:center;
                        justify-content:center;
                        font-size:25px;
                        color:#9ca3af;
                    "
                >
                    <i class="bi bi-file-earmark-pdf"></i>
                </div>

                <div
                    class="fw-semibold"
                    style="font-size:13px;"
                >
                    Aucun rapport généré
                </div>

                <div
                    class="text-muted mt-1"
                    style="font-size:10px;"
                >
                    Les rapports PDF générés apparaîtront ici.
                </div>

            </div>

        @endif

    </div>

</div>


</div>


@push('styles')

<style>

/* =========================================================
   PAGE RAPPORTS — DESIGN ET RESPONSIVE
========================================================= */

.rapports-page .dashboard-card {
    overflow: hidden;
}

.rapports-page .report-label {
    font-size: 11px;
    font-weight: 600;
    color: var(--text);
    margin-bottom: 6px;
}

.rapports-page .report-input {
    min-height: 38px;
    font-size: 11px;
    border-color: var(--border);
    border-radius: 8px;
}

.rapports-page .report-input:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(37, 99, 235, .08);
}

/* Boutons */
.rapports-page .btn {
    min-height: 38px;
}

/* Tables */
.rapports-page .attendance-table {
    margin-bottom: 0;
}

.rapports-page .attendance-table th,
.rapports-page .attendance-table td {
    border-color: var(--border);
    white-space: nowrap;
}

/* Statistiques */
.rapports-page .stat-card {
    min-height: 110px;
}


/* =========================================================
   TABLETTE
========================================================= */

@media (max-width: 991px) {

    .rapports-page .stat-card {
        min-height: 105px;
    }

}


/* =========================================================
   MOBILE
========================================================= */

@media (max-width: 768px) {

    /* En-tête */
    .rapports-page
    > .d-flex.justify-content-between.align-items-center.mb-4 {
        flex-direction: column;
        align-items: flex-start !important;
        gap: 12px;
    }


    /* Cartes */
    .rapports-page .dashboard-card {
        border-radius: 12px;
        margin-bottom: 16px !important;
    }

    .rapports-page .card-header-custom {
        padding: 16px;
        gap: 12px;
    }

    .rapports-page .card-body-custom {
        padding: 16px;
    }


    /* Titres */
    .rapports-page .card-title-custom {
        font-size: 13px;
    }

    .rapports-page .card-header-custom .text-muted {
        font-size: 10px !important;
    }


    /* Formulaire */
    .rapports-page .report-label {
        font-size: 10px;
        margin-bottom: 5px;
    }

    .rapports-page .report-input {
        min-height: 38px;
        font-size: 11px;
    }


    /* Boutons */
    .rapports-page form .btn {
        min-height: 38px;
        font-size: 11px;
    }


    /*
     * Les deux boutons du générateur passent
     * l'un sous l'autre sur petit écran.
     */
    .rapports-page form .d-flex.justify-content-end {
        flex-direction: column;
        width: 100%;
    }

    .rapports-page form .d-flex.justify-content-end .btn {
        width: 100%;
    }


    /* Tables */
    .rapports-page .table-responsive {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .rapports-page .attendance-table {
        min-width: 720px;
    }

    .rapports-page .attendance-table th,
    .rapports-page .attendance-table td {
        white-space: nowrap;
    }


    /* Prévisualisation */
    .rapports-page .stat-card {
        min-height: auto;
    }


    /* Historique */
    .rapports-page .table-responsive {
        margin-bottom: 0;
    }

}


/* =========================================================
   TRÈS PETITS ÉCRANS
========================================================= */

@media (max-width: 480px) {

    /* En-tête */
    .rapports-page .page-title {
        font-size: 20px;
    }

    .rapports-page .page-subtitle {
        font-size: 10px;
    }


    /* Cartes */
    .rapports-page .dashboard-card {
        border-radius: 10px;
    }

    .rapports-page .card-header-custom {
        padding: 14px;
    }

    .rapports-page .card-body-custom {
        padding: 14px;
    }


    /* Titres */
    .rapports-page .card-title-custom {
        font-size: 12px;
    }


    /* Formulaire */
    .rapports-page .report-label {
        font-size: 9px;
    }

    .rapports-page .report-input {
        min-height: 37px;
        font-size: 10px;
    }


    /* Boutons */
    .rapports-page form .btn {
        min-height: 37px;
        font-size: 10px;
    }


    /* Statistiques */
    .rapports-page .stat-card {
        padding: 14px !important;
    }

    .rapports-page .stat-label {
        font-size: 9px;
    }

    .rapports-page .stat-number {
        font-size: 21px;
    }

    .rapports-page .stat-change {
        font-size: 9px;
    }

    .rapports-page .stat-icon {
        width: 34px;
        height: 34px;
        font-size: 14px;
    }


    /* Tables */
    .rapports-page .attendance-table {
        min-width: 680px;
    }

    .rapports-page .attendance-table th {
        font-size: 9px;
    }

    .rapports-page .attendance-table td {
        font-size: 10px;
    }


    /* Badge */
    .rapports-page .badge {
        font-size: 8px !important;
    }


    /* Action PDF */
    .rapports-page .attendance-table .btn {
        width: auto;
        min-height: 32px;
        font-size: 9px;
    }

}

</style>

@endpush


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const periode = document.getElementById('periode');
    const dateDebut = document.getElementById('date_debut');
    const dateFin = document.getElementById('date_fin');

    if (!periode || !dateDebut || !dateFin) {
        return;
    }


    function formatDate(date) {

        const annee = date.getFullYear();

        const mois = String(
            date.getMonth() + 1
        ).padStart(2, '0');

        const jour = String(
            date.getDate()
        ).padStart(2, '0');

        return `${annee}-${mois}-${jour}`;
    }


    function debutSemaine(date) {

        const jour = date.getDay();

        // JavaScript :
        // dimanche = 0
        // lundi = 1
        // ...
        // samedi = 6

        const difference = jour === 0
            ? -6
            : 1 - jour;

        const lundi = new Date(date);

        lundi.setDate(
            date.getDate() + difference
        );

        return lundi;
    }


    function finSemaine(date) {

        const lundi = debutSemaine(date);

        const dimanche = new Date(lundi);

        dimanche.setDate(
            lundi.getDate() + 6
        );

        return dimanche;
    }


    function appliquerPeriode() {

        const aujourdHui = new Date();

        let debut = new Date(aujourdHui);
        let fin = new Date(aujourdHui);


        switch (periode.value) {

            case 'aujourd_hui':

                debut = aujourdHui;
                fin = aujourdHui;

                break;


            case 'hier':

                debut = new Date(aujourdHui);

                debut.setDate(
                    aujourdHui.getDate() - 1
                );

                fin = new Date(debut);

                break;


            case 'semaine':

                debut = debutSemaine(
                    aujourdHui
                );

                fin = finSemaine(
                    aujourdHui
                );

                break;


            case 'mois':

                debut = new Date(
                    aujourdHui.getFullYear(),
                    aujourdHui.getMonth(),
                    1
                );

                fin = new Date(
                    aujourdHui.getFullYear(),
                    aujourdHui.getMonth() + 1,
                    0
                );

                break;


            case 'personnalisee':

                // Les dates sont choisies manuellement.
                return;
        }


        dateDebut.value = formatDate(debut);

        dateFin.value = formatDate(fin);
    }


    periode.addEventListener(
        'change',
        appliquerPeriode
    );


    /*
    |--------------------------------------------------------------------------
    | INITIALISATION
    |--------------------------------------------------------------------------
    */

    if (
        !dateDebut.value ||
        !dateFin.value
    ) {
        appliquerPeriode();
    }


    /*
    |--------------------------------------------------------------------------
    | GÉNÉRATION PDF
    |--------------------------------------------------------------------------
    */

    const btnPdf = document.getElementById(
        'btnGenererPdf'
    );

    if (btnPdf) {

        btnPdf.addEventListener(
            'click',
            function () {

                alert(
                    'La génération PDF sera activée à la prochaine étape.'
                );

            }
        );
    }

});
</script>
@endpush

@endsection