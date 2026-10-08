@extends('layouts.admin')

@section('title', 'Journal des fraudes')

@section('content')

<div class="dashboard-card">

    {{-- =========================
         EN-TÊTE
    ========================== --}}

    <div class="card-header-custom">

        <div>

            <h5 class="card-title-custom">
                Journal des fraudes
            </h5>

            <div class="page-subtitle">
                Historique des événements de sécurité détectés
            </div>

        </div>

        <span class="fraud-badge">

            <i class="bi bi-shield-exclamation"></i>

            {{ $frauds->total() }} événement(s)

        </span>

    </div>


    {{-- =========================
         FILTRES
    ========================== --}}

    <div class="card-body-custom border-bottom">

        <form method="GET"
              action="{{ route('frauds.index') }}">

            <div class="row g-2 align-items-end">

                {{-- Recherche --}}

                <div class="col-md-6">

                    <label class="form-label-custom">
                        Recherche
                    </label>

                    <div class="input-group input-group-sm">

                        <span class="input-group-text bg-white">

                            <i class="bi bi-search text-muted"></i>

                        </span>

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            placeholder="RFID, IP ou description..."
                            value="{{ request('search') }}"
                        >

                    </div>

                </div>


                {{-- Type --}}

                <div class="col-md-4">

                    <label class="form-label-custom">
                        Type
                    </label>

                    <select
                        name="type"
                        class="form-select form-select-sm"
                    >

                        <option value="">
                            Tous les types
                        </option>

                        <option value="BADGE_INCONNU"
                            {{ request('type') === 'BADGE_INCONNU' ? 'selected' : '' }}>
                            Badge inconnu
                        </option>

                        <option value="VISAGE_INVALIDE"
                            {{ request('type') === 'VISAGE_INVALIDE' ? 'selected' : '' }}>
                            Visage invalide
                        </option>

                        <option value="IP_NON_AUTORISEE"
                            {{ request('type') === 'IP_NON_AUTORISEE' ? 'selected' : '' }}>
                            IP non autorisée
                        </option>

                        <option value="ANTI_PASSBACK"
                            {{ request('type') === 'ANTI_PASSBACK' ? 'selected' : '' }}>
                            Anti-passback
                        </option>

                    </select>

                </div>


                {{-- Bouton --}}

                <div class="col-md-2">

                    <button
                        type="submit"
                        class="btn btn-primary btn-sm w-100"
                    >

                        <i class="bi bi-funnel me-1"></i>

                        Filtrer

                    </button>

                </div>

            </div>

        </form>

    </div>


    {{-- =========================
         TABLEAU
    ========================== --}}

    <div class="table-responsive">

        <table class="table mb-0 attendance-table fraud-table">

            <thead>

                <tr>

                    <th>
                        Date / Heure
                    </th>

                    <th>
                        Type
                    </th>

                    <th>
                        Employé
                    </th>

                    <th>
                        RFID
                    </th>

                    <th>
                        Station
                    </th>

                    <th>
                        Description
                    </th>

                </tr>

            </thead>


            <tbody>

                @forelse($frauds as $fraud)

                    <tr>

                        {{-- Date / Heure --}}

                        <td>

                            <div class="employee-name">

                                {{ \Carbon\Carbon::parse($fraud->heure_fraude)->format('d/m/Y') }}

                            </div>

                            <div class="employee-service">

                                {{ \Carbon\Carbon::parse($fraud->heure_fraude)->format('H:i:s') }}

                            </div>

                        </td>


                        {{-- Type --}}

                        <td>

                            @php

                                $typeLabels = [

                                    'BADGE_INCONNU' => 'Badge inconnu',

                                    'VISAGE_INVALIDE' => 'Visage invalide',

                                    'IP_NON_AUTORISEE' => 'IP non autorisée',

                                    'ANTI_PASSBACK' => 'Anti-passback',

                                ];

                            @endphp


                            <span class="fraud-type">

                                <i class="bi bi-exclamation-triangle-fill"></i>

                                {{ $typeLabels[$fraud->type] ?? $fraud->type }}

                            </span>

                        </td>


                        {{-- Employé --}}

                        <td>

                            @if($fraud->user)

                                <div class="d-flex align-items-center gap-2">

                                    <div class="employee-photo bg-light d-flex align-items-center justify-content-center">

                                        <i class="bi bi-person text-secondary"></i>

                                    </div>

                                    <div>

                                        <div class="employee-name">

                                            {{ $fraud->user->prenom ?? '' }}
                                            {{ $fraud->user->nom ?? $fraud->user->name ?? '' }}

                                        </div>

                                        <div class="employee-service">

                                            {{ $fraud->user->service ?? 'Personnel' }}

                                        </div>

                                    </div>

                                </div>

                            @else

                                <span class="text-muted" style="font-size:11px;">

                                    Non identifié

                                </span>

                            @endif

                        </td>


                        {{-- RFID --}}

                        <td>

                            @if($fraud->rfid_uid)

                                <code class="rfid-code">

                                    {{ $fraud->rfid_uid }}

                                </code>

                            @else

                                <span class="text-muted">
                                    —
                                </span>

                            @endif

                        </td>


                        {{-- Station --}}

                        <td>

                            @if($fraud->ip_station)

                                <span style="font-size:11px;">

                                    <i class="bi bi-pc-display text-muted me-1"></i>

                                    {{ $fraud->ip_station }}

                                </span>

                            @else

                                <span class="text-muted">
                                    —
                                </span>

                            @endif

                        </td>


                        {{-- Description --}}

                        <td>

                            <span
                                class="text-muted"
                                style="font-size:11px;"
                            >

                                {{ $fraud->description ?? 'Aucune description' }}

                            </span>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="6"
                            class="text-center py-5">

                            <i
                                class="bi bi-shield-check text-muted"
                                style="font-size:30px;"
                            ></i>

                            <div class="mt-2 fw-semibold">

                                Aucun événement de sécurité

                            </div>

                            <div
                                class="text-muted"
                                style="font-size:11px;"
                            >

                                Les tentatives suspectes apparaîtront automatiquement ici.

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

            <div
                class="text-muted"
                style="font-size:11px;"
            >

                Affichage de

                {{ $frauds->firstItem() ?? 0 }}

                à

                {{ $frauds->lastItem() ?? 0 }}

                sur

                {{ $frauds->total() }}

                événement(s)

            </div>


            <div>

                {{ $frauds->links('pagination::bootstrap-5') }}

            </div>

        </div>

    </div>

</div>


<style>

/* =========================
   BADGE TOTAL
========================= */

.fraud-badge {

    display: inline-flex;

    align-items: center;

    gap: 6px;

    padding: 5px 10px;

    border-radius: 20px;

    background: rgba(220, 53, 69, 0.08);

    color: #dc3545;

    font-size: 10px;

    font-weight: 600;

}


/* =========================
   LABELS
========================= */

.form-label-custom {

    display: block;

    margin-bottom: 4px;

    font-size: 10px;

    font-weight: 600;

    color: #6c757d;

}


/* =========================
   TYPE FRAUDE
========================= */

.fraud-type {

    display: inline-flex;

    align-items: center;

    gap: 5px;

    font-size: 10px;

    font-weight: 600;

    color: #dc3545;

}


/* =========================
   RFID
========================= */

.rfid-code {

    font-size: 10px;

    padding: 3px 6px;

    border-radius: 4px;

    background: #f8f9fa;

}


/* =========================
   TABLEAU
========================= */

.fraud-table th {

    font-size: 12px;

    text-transform: uppercase;

    letter-spacing: 0.3px;

}


.fraud-table td {

    font-size: 12px;

}


/* =========================
   PAGINATION
========================= */

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


/* =========================================================
   RESPONSIVE — JOURNAL DES FRAUDES
   ========================================================= */

@media (max-width: 768px) {

    /* En-tête */
    .dashboard-card > .card-header-custom {
        flex-direction: column;
        align-items: flex-start !important;
        gap: 10px;
    }

    .fraud-badge {
        font-size: 9px;
        padding: 5px 9px;
    }

    /* Zone des filtres */
    .dashboard-card > .card-body-custom.border-bottom {
        padding: 16px;
    }

    .form-label-custom {
        font-size: 10px;
        margin-bottom: 5px;
    }

    .form-control,
    .form-select {
        min-height: 38px;
        font-size: 11px;
    }

    /* Bouton filtrer */
    .dashboard-card form .btn {
        min-height: 38px;
        font-size: 11px;
    }

    /* Tableau */
    .fraud-table {
        min-width: 850px;
    }

    .fraud-table th,
    .fraud-table td {
        white-space: nowrap;
    }

    .fraud-table th {
        font-size: 10px;
    }

    .fraud-table td {
        font-size: 11px;
    }

    .fraud-type {
        font-size: 9px;
    }

    .rfid-code {
        font-size: 9px;
    }

    /* Pagination */
    .dashboard-card > .card-body-custom.border-top {
        padding: 14px 16px;
    }

    .dashboard-card > .card-body-custom.border-top
    > .d-flex.justify-content-between.align-items-center {
        flex-direction: column;
        align-items: flex-start !important;
        gap: 12px;
    }

    .dashboard-card > .card-body-custom.border-top .pagination {
        flex-wrap: wrap;
    }
}


/* =========================================================
   TRÈS PETITS ÉCRANS
   ========================================================= */

@media (max-width: 480px) {

    /* En-tête */
    .card-title-custom {
        font-size: 12px;
    }

    .page-subtitle {
        font-size: 9px;
    }

    .fraud-badge {
        font-size: 8px;
        padding: 4px 8px;
    }

    /* Filtres */
    .dashboard-card > .card-body-custom.border-bottom {
        padding: 14px;
    }

    .form-label-custom {
        font-size: 9px;
    }

    .form-control,
    .form-select {
        min-height: 37px;
        font-size: 10px;
    }

    .dashboard-card form .btn {
        min-height: 37px;
        font-size: 10px;
    }

    /* Tableau */
    .fraud-table {
        min-width: 800px;
    }

    .fraud-table th {
        font-size: 9px;
    }

    .fraud-table td {
        font-size: 10px;
    }

    .employee-name {
        font-size: 10px;
    }

    .employee-service {
        font-size: 9px;
    }

    .fraud-type {
        font-size: 8px;
    }

    .rfid-code {
        font-size: 8px;
    }

    /* Pagination */
    .dashboard-card > .card-body-custom.border-top {
        padding: 12px 14px;
    }

    .pagination .page-link {
        font-size: 10px;
        padding: 4px 7px;
    }
}
</style>

@endsection