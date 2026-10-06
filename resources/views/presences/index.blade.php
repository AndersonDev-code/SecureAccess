@extends('layouts.admin')

@section('title', 'Présences | SecureAccess')

@section('content')

<div class="content">

{{-- =========================
     EN-TÊTE DE PAGE
========================== --}}

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h1 class="page-title">
            Présences
        </h1>

        <div class="page-subtitle">
            Suivi des entrées, sorties et retards du personnel
        </div>
    </div>

    <div>
        <button class="btn btn-primary btn-sm">
            <i class="bi bi-download me-1"></i>
            Exporter
        </button>
    </div>

</div>


{{-- =========================
     FILTRES
========================== --}}

<div class="dashboard-card mb-4">

    <form method="GET" action="{{ route('presences.index') }}">

        <div class="card-body-custom">

            <div class="row g-3 align-items-end">

                {{-- Date / période --}}

                <div class="col-12 col-md-3">

                    <label class="form-label small fw-semibold">
                        Période
                    </label>

                    <select
                        name="periode"
                        class="form-select form-select-sm"
                    >

                        <option
                            value="toutes"
                            {{ $periode === 'toutes' ? 'selected' : '' }}
                        >
                            Toutes les dates
                        </option>

                        <option
                            value="aujourd_hui"
                            {{ $periode === 'aujourd_hui' ? 'selected' : '' }}
                        >
                            Aujourd'hui
                        </option>

                        <option
                            value="hier"
                            {{ $periode === 'hier' ? 'selected' : '' }}
                        >
                            Hier
                        </option>

                        <option
                            value="semaine"
                            {{ $periode === 'semaine' ? 'selected' : '' }}
                        >
                            Cette semaine
                        </option>

                        <option
                            value="mois"
                            {{ $periode === 'mois' ? 'selected' : '' }}
                        >
                            Ce mois
                        </option>

                    </select>

                </div>


                {{-- Recherche --}}

                <div class="col-12 col-md-4">

                    <label class="form-label small fw-semibold">
                        Rechercher un employé
                    </label>

                    <div class="input-group input-group-sm">

                        <span class="input-group-text bg-white">
                            <i class="bi bi-search text-muted"></i>
                        </span>

                        <input
                            type="text"
                            name="recherche"
                            value="{{ $recherche }}"
                            class="form-control"
                            placeholder="Nom, prénom ou matricule..."
                        >

                    </div>

                </div>


                {{-- Type --}}

                <div class="col-12 col-md-3">

                    <label class="form-label small fw-semibold">
                        Type de pointage
                    </label>

                    <select
                        name="type"
                        class="form-select form-select-sm"
                    >

                        <option
                            value="tous"
                            {{ $type === 'tous' ? 'selected' : '' }}
                        >
                            Tous
                        </option>

                        <option
                            value="entrees"
                            {{ $type === 'entrees' ? 'selected' : '' }}
                        >
                            Entrées
                        </option>

                        <option
                            value="sorties"
                            {{ $type === 'sorties' ? 'selected' : '' }}
                        >
                            Sorties
                        </option>

                        <option
                            value="retards"
                            {{ $type === 'retards' ? 'selected' : '' }}
                        >
                            Retards
                        </option>

                    </select>

                </div>


                {{-- Bouton --}}

                <div class="col-12 col-md-2">

                    <button
                        type="submit"
                        class="btn btn-outline-secondary btn-sm w-100"
                    >

                        <i class="bi bi-funnel me-1"></i>

                        Filtrer

                    </button>

                </div>

            </div>

        </div>

    </form>

</div>


{{-- =========================
     STATISTIQUES
========================== --}}

<div class="row g-3 mb-4">

    {{-- Présents --}}

    <div class="col-12 col-sm-6 col-xl-3">

        <div class="stat-card">

            <div class="d-flex justify-content-between">

                <div>

                    <div class="stat-label">
                        Présents 
                    </div>

                    <div class="stat-number" id="presenceTotalStat">
                        {{ $totalPresents }}
                    </div>

                    <div class="stat-change text-success">

                        <i class="bi bi-person-check"></i>

                        Personnel présent

                    </div>

                </div>

                <div class="stat-icon bg-success bg-opacity-10 text-success">

                    <i class="bi bi-person-check-fill"></i>

                </div>

            </div>

        </div>

    </div>


    {{-- Entrées --}}

    <div class="col-12 col-sm-6 col-xl-3">

        <div class="stat-card">

            <div class="d-flex justify-content-between">

                <div>

                    <div class="stat-label">
                        Entrées
                    </div>

                    <div class="stat-number" id="entreesTotalStat">
                            {{ $totalEntrees }}
                    </div>

                    <div class="stat-change text-primary">

                        <i class="bi bi-box-arrow-in-right"></i>

                        Période sélectionnée

                    </div>

                </div>

                <div class="stat-icon bg-primary bg-opacity-10 text-primary">

                    <i class="bi bi-box-arrow-in-right"></i>

                </div>

            </div>

        </div>

    </div>


    {{-- Sorties --}}

    <div class="col-12 col-sm-6 col-xl-3">

        <div class="stat-card">

            <div class="d-flex justify-content-between">

                <div>

                    <div class="stat-label">
                        Sorties
                    </div>

                    <div class="stat-number" id="sortiesTotalStat">
                        {{ $totalSorties }}
                    </div>

                    <div class="stat-change text-info">

                        <i class="bi bi-box-arrow-right"></i>

                        Période sélectionnée

                    </div>

                </div>

                <div class="stat-icon bg-info bg-opacity-10 text-info">

                    <i class="bi bi-box-arrow-right"></i>

                </div>

            </div>

        </div>

    </div>


    {{-- Retards --}}

    <div class="col-12 col-sm-6 col-xl-3">

        <div class="stat-card">

            <div class="d-flex justify-content-between">

                <div>

                    <div class="stat-label">
                        Retards
                    </div>

                    <div class="stat-number" id="retardsTotalStat">
                        {{ $totalRetards }}
                    </div>

                    <div class="stat-change text-warning">

                        <i class="bi bi-clock"></i>

                        Période sélectionnée

                    </div>

                </div>

                <div class="stat-icon bg-warning bg-opacity-10 text-warning">

                    <i class="bi bi-clock-history"></i>

                </div>

            </div>

        </div>

    </div>

</div>


{{-- =========================
     TABLEAU DES PRÉSENCES
========================== --}}

<div class="dashboard-card">

    <div class="card-header-custom">

        <div>

            <h5 class="card-title-custom">
                Historique des présences
            </h5>

            <div class="page-subtitle">
                Pointages enregistrés aujourd'hui
            </div>

        </div>

        <span class="live-badge">

            <span class="live-dot"></span>

            TEMPS RÉEL

        </span>

    </div>


    <div class="table-responsive">

        <table class="table mb-0 attendance-table">

            <thead>

                <tr>

                    <th>
                        Employé
                    </th>

                    <th>
                        Matricule
                    </th>

                    <th>
                        Service
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

            {{-- Employé --}}

            <td>

                <div class="d-flex align-items-center gap-2">

                    <div class="employee-photo bg-light d-flex align-items-center justify-content-center">

                        <i class="bi bi-person text-secondary"></i>

                    </div>

                    <div>

                        <div class="employee-name">
                            {{ $pointage->user->prenom ?? '' }}
                            {{ $pointage->user->nom ?? '' }}
                        </div>

                        <div class="employee-service">
                            {{ $pointage->user->service ?? 'Personnel' }}
                        </div>

                    </div>

                </div>

            </td>


            {{-- Matricule --}}

            <td>
                {{ $pointage->user->matricule ?? '—' }}
            </td>


            {{-- Service --}}

            <td>
                {{ $pointage->user->service ?? '—' }}
            </td>


            {{-- Heure --}}

            <td>
                {{ $pointage->heure }}
            </td>


            {{-- Type --}}

            <td>

                @if($pointage->type === 'ENTREE')

                    <span class="badge-entry">
                        Entrée
                    </span>

                @else

                    <span class="badge-exit">
                        Sortie
                    </span>

                @endif

            </td>


            {{-- Statut --}}

            <td>

                @if($pointage->status === 'RETARD')

                    <span class="text-warning" style="font-size:10px;">

                        <i class="bi bi-clock-fill"></i>

                        Retard

                    </span>

                @else

                    <span class="text-success" style="font-size:10px;">

                        <i class="bi bi-check-circle-fill"></i>

                        Validé

                    </span>

                @endif

            </td>

        </tr>

    @empty

        <tr>

            <td colspan="6" class="text-center py-5">

                <i
                    class="bi bi-calendar-x text-muted"
                    style="font-size:30px;"
                ></i>

                <div class="mt-2 fw-semibold">
                    Aucun pointage aujourd'hui
                </div>

                <div class="text-muted" style="font-size:11px;">
                    Les pointages apparaîtront automatiquement ici.
                </div>

            </td>

        </tr>

    @endforelse

</tbody>

        </table>

    </div>


    {{-- =========================
         PAGINATION
    ========================== --}}

    <div class="card-body-custom border-top">

    <div class="d-flex justify-content-between align-items-center">

        <div class="text-muted" style="font-size:11px;">

            Affichage de
            {{ $pointages->firstItem() ?? 0 }}
            à
            {{ $pointages->lastItem() ?? 0 }}
            sur
            {{ $pointages->total() }}
            pointage(s)

        </div>

        <div>
            {{ $pointages->links('pagination::bootstrap-5') }}
        </div>

    </div>

</div>

</div>

</div>

<style>
    .pagination {
        margin-bottom: 0;
    }

    .pagination .page-link {
        font-size: 12px;
        padding: 5px 9px;
    }

    .pagination svg {
        width: 14px;
        height: 14px;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const table = document.querySelector('.attendance-table tbody');

    const presenceStat = document.getElementById('presenceTotalStat');
    const entreesStat = document.getElementById('entreesTotalStat');
    const sortiesStat = document.getElementById('sortiesTotalStat');
    const retardsStat = document.getElementById('retardsTotalStat');

    const reverb = new Pusher('{{ config('broadcasting.connections.reverb.key') }}', {
        cluster: 'mt1',
        wsHost: '127.0.0.1',
        wsPort: 8080,
        wssPort: 8080,
        forceTLS: false,
        enabledTransports: ['ws']
    });

    const pointageChannel = reverb.subscribe('secureaccess-pointages');

    pointageChannel.bind('pointage.enregistre', function (data) {

        console.log('🟢 NOUVEAU POINTAGE REÇU SUR PRÉSENCES :', data);

        const pointage = data.pointage;

        /*
        |--------------------------------------------------------------------------
        | 1. Mise à jour des statistiques
        |--------------------------------------------------------------------------
        */

        let entrees = parseInt(entreesStat?.textContent.trim()) || 0;
        let sorties = parseInt(sortiesStat?.textContent.trim()) || 0;
        let retards = parseInt(retardsStat?.textContent.trim()) || 0;
        let presents = parseInt(presenceStat?.textContent.trim()) || 0;

        if (pointage.type === 'ENTREE') {
            entrees++;
            presents++;
        }

        if (pointage.type === 'SORTIE') {
            sorties++;
            presents--;
        }

        if (pointage.status === 'RETARD') {
            retards++;
        }

        presents = Math.max(0, presents);

        if (entreesStat) {
            entreesStat.textContent = entrees;
        }

        if (sortiesStat) {
            sortiesStat.textContent = sorties;
        }

        if (retardsStat) {
            retardsStat.textContent = retards;
        }

        if (presenceStat) {
            presenceStat.textContent = presents;
        }

        /*
        |--------------------------------------------------------------------------
        | 2. Suppression du message "aucun pointage"
        |--------------------------------------------------------------------------
        */

        const emptyRow = table?.querySelector('td[colspan="6"]');

        if (emptyRow) {
            emptyRow.closest('tr').remove();
        }

        /*
        |--------------------------------------------------------------------------
        | 3. Création de la ligne en temps réel
        |--------------------------------------------------------------------------
        */

        if (!table) {
            return;
        }

        const typeHtml = pointage.type === 'ENTREE'
            ? `<span class="badge-entry">Entrée</span>`
            : `<span class="badge-exit">Sortie</span>`;

        const statusHtml = pointage.status === 'RETARD'
            ? `
                <span class="text-warning" style="font-size:10px;">
                    <i class="bi bi-clock-fill"></i>
                    Retard
                </span>
              `
            : `
                <span class="text-success" style="font-size:10px;">
                    <i class="bi bi-check-circle-fill"></i>
                    Validé
                </span>
              `;

        const row = document.createElement('tr');

        row.innerHTML = `
            <td>
                <div class="d-flex align-items-center gap-2">
                    <div class="employee-photo bg-light d-flex align-items-center justify-content-center">
                        <i class="bi bi-person text-secondary"></i>
                    </div>

                    <div>
                        <div class="employee-name">
                            ${pointage.prenom ?? ''} ${pointage.nom ?? ''}
                        </div>

                        <div class="employee-service">
                            Pointage en temps réel
                        </div>
                    </div>
                </div>
            </td>

            <td>—</td>

            <td>—</td>

            <td>${pointage.heure ?? '—'}</td>

            <td>${typeHtml}</td>

            <td>${statusHtml}</td>
        `;

        /*
        |--------------------------------------------------------------------------
        | 4. Ajouter la nouvelle ligne en haut
        |--------------------------------------------------------------------------
        */

        table.prepend(row);

        /*
        |--------------------------------------------------------------------------
        | 5. Garder la page propre
        |--------------------------------------------------------------------------
        */

        const rows = table.querySelectorAll('tr');

        if (rows.length > 10) {
            rows[rows.length - 1].remove();
        }

    });

});
</script>

@endsection
