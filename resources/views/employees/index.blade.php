@extends('layouts.admin')

@section('title', 'Employés')

@section('content')

<div class="content">

    {{-- =========================================================
         EN-TÊTE
    ========================================================== --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="fw-bold mb-1">Liste des employés</h3>

            <small class="text-muted">
                Gestion du personnel
            </small>
        </div>

        <a href="{{ route('employees.create') }}"
           class="btn btn-primary">

            <i class="bi bi-plus-circle me-2"></i>
            Ajouter un employé

        </a>

    </div>


    {{-- =========================================================
         MESSAGE DE SUCCÈS
    ========================================================== --}}
    @if(session('success'))

        <div class="alert alert-success border-0 shadow-sm mb-4">

            <i class="bi bi-check-circle me-2"></i>

            {{ session('success') }}

        </div>

    @endif


    {{-- =========================================================
         CARTE PRINCIPALE
    ========================================================== --}}
    <div class="dashboard-card employees-card">


        {{-- =====================================================
             HEADER DE LA CARTE
        ====================================================== --}}
        <div class="employees-toolbar">

            {{-- Informations --}}
            <div class="employees-toolbar-info">

                <div class="employees-title-row">

                    <div class="employees-title-icon">
                        <i class="bi bi-people"></i>
                    </div>

                    <div>

                        <h5 class="card-title-custom mb-1">
                            Employés enregistrés
                        </h5>

                        <small class="text-muted">
                            {{ $employees->total() }} employé(s) au total
                        </small>

                    </div>

                </div>

            </div>


            {{-- =================================================
                 RECHERCHE + ACTUALISATION
            ================================================== --}}
            <div class="employees-tools">

                <form
                    action="{{ route('employees.index') }}"
                    method="GET"
                    class="employee-search"
                >

                    <i class="bi bi-search search-icon"></i>

                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Rechercher un employé..."
                        autocomplete="off"
                    >

                    @if(request('search'))

                        <a
                            href="{{ route('employees.index') }}"
                            class="search-clear"
                            title="Effacer la recherche"
                        >

                            <i class="bi bi-x-circle-fill"></i>

                        </a>

                    @endif

                    <button
                        type="submit"
                        class="search-submit"
                        title="Rechercher"
                    >

                        <i class="bi bi-arrow-right"></i>

                    </button>

                </form>


                {{-- Actualiser --}}
                <a
                    href="{{ route('employees.index', request()->query()) }}"
                    class="refresh-button"
                    title="Actualiser"
                >

                    <i class="bi bi-arrow-clockwise"></i>

                </a>

            </div>

        </div>


        {{-- =====================================================
             TABLEAU
        ====================================================== --}}
        <div class="table-responsive">

            <table class="table align-middle mb-0 attendance-table">

                <thead>

                    <tr>

                        <th>Employé</th>

                        <th>Matricule</th>

                        <th>Service</th>

                        <th>RFID</th>

                        <th>Statut</th>

                        <th class="text-end pe-4">Actions</th>

                    </tr>

                </thead>


                <tbody>

                @forelse($employees as $employee)

                    <tr>

                        {{-- =================================================
                             EMPLOYÉ
                        ================================================== --}}
                        <td>

                            <div class="d-flex align-items-center gap-3">

                                @if($employee->photo)

                                    <img
                                        src="{{ Str::startsWith($employee->photo, 'http') ? $employee->photo : asset('storage/' . $employee->photo) }}"
                                        alt="Photo de {{ $employee->nom }} {{ $employee->prenom }}"
                                        class="employee-photo"
                                    >

                                @else

                                    <div class="employee-photo employee-avatar-placeholder">

                                        <i class="bi bi-person"></i>

                                    </div>

                                @endif


                                <div>

                                    <div class="employee-name">

                                        {{ $employee->nom }}
                                        {{ $employee->prenom }}

                                    </div>

                                    <div class="employee-service">

                                        {{ $employee->email }}

                                    </div>

                                </div>

                            </div>

                        </td>


                        {{-- =================================================
                             MATRICULE
                        ================================================== --}}
                        <td>

                            <span class="employee-matricule">

                                {{ $employee->matricule }}

                            </span>

                        </td>


                        {{-- =================================================
                             SERVICE
                        ================================================== --}}
                        <td>

                            <span class="employee-service-name">

                                {{ $employee->service }}

                            </span>

                        </td>


                        {{-- =================================================
                             RFID
                        ================================================== --}}
                        <td>

                            @if($employee->rfid_uid)

                                <span class="rfid-code">

                                    <i class="bi bi-credit-card-2-front me-1"></i>

                                    {{ $employee->rfid_uid }}

                                </span>

                            @else

                                <span class="text-muted small">

                                    Non attribué

                                </span>

                            @endif

                        </td>


                        {{-- =================================================
                             STATUT
                        ================================================== --}}
                        <td>

                            @if($employee->is_active)

                                <span class="status-badge status-active">

                                    <span class="status-dot-small"></span>

                                    Actif

                                </span>

                            @else

                                <span class="status-badge status-disabled">

                                    <span class="status-dot-small"></span>

                                    Désactivé

                                </span>

                            @endif

                        </td>


                        {{-- =================================================
                             ACTIONS
                        ================================================== --}}
                        <td class="text-end pe-4">

                            <div class="employee-actions">

                                {{-- Voir --}}
                                <button
                                    type="button"
                                    class="action-icon action-view"
                                    title="Voir le profil"
                                    data-bs-toggle="modal"
                                    data-bs-target="#employeeModal{{ $employee->id }}"
                                >   
                                    <i class="bi bi-eye"></i>
                                </button>


                                {{-- Modifier --}}
                                <a
                                    href="{{ route('employees.edit', $employee) }}"
                                    class="action-icon action-edit"
                                    title="Modifier"
                                >

                                    <i class="bi bi-pencil"></i>

                                </a>


                                {{-- Désactiver --}}
                                @if($employee->is_active)

                                    <form
                                        action="{{ route('employees.destroy', $employee) }}"
                                        method="POST"
                                        class="d-inline"
                                    >

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="action-icon action-delete"
                                            title="Désactiver"
                                            onclick="return confirm('Désactiver cet employé ?')"
                                        >

                                            <i class="bi bi-trash3"></i>

                                        </button>

                                    </form>

                                @endif

                            </div>

                        </td>

                    </tr>


{{-- =========================================================
     MODAL PROFIL EMPLOYÉ
========================================================== --}}
<div
    class="modal fade"
    id="employeeModal{{ $employee->id }}"
    tabindex="-1"
    aria-labelledby="employeeModalLabel{{ $employee->id }}"
    aria-hidden="true"
>

    <div class="modal-dialog modal-dialog-centered modal-lg">

        <div class="modal-content employee-modal">


            {{-- =================================================
                 HEADER
            ================================================== --}}
            <div class="modal-header employee-modal-header">

                <div>

                    <h5
                        class="modal-title fw-bold"
                        id="employeeModalLabel{{ $employee->id }}"
                    >

                        Profil de l'employé

                    </h5>

                    <small class="text-muted">

                        Informations enregistrées dans SecureAccess

                    </small>

                </div>


                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Fermer"
                ></button>

            </div>


            {{-- =================================================
                 BODY
            ================================================== --}}
            <div class="modal-body p-0">

                <div class="employee-profile">


                    {{-- ================================
                         PROFIL
                    ================================= --}}
                    <div class="employee-profile-header">

                        <div class="employee-profile-photo-wrapper">

                            @if($employee->photo)

                                <img
                                    src="{{ asset('storage/' . $employee->photo) }}"
                                    alt="Photo de {{ $employee->nom }} {{ $employee->prenom }}"
                                    class="employee-profile-photo"
                                >

                            @else

                                <div class="employee-profile-placeholder">

                                    <i class="bi bi-person"></i>

                                </div>

                            @endif

                        </div>


                        <div class="employee-profile-main">

                            <h4>

                                {{ $employee->nom }}
                                {{ $employee->prenom }}

                            </h4>

                            <p>

                                {{ $employee->service }}

                                <span class="profile-separator">
                                    •
                                </span>

                                {{ $employee->matricule }}

                            </p>


                            @if($employee->is_active)

                                <span class="status-badge status-active">

                                    <span class="status-dot-small"></span>

                                    Compte actif

                                </span>

                            @else

                                <span class="status-badge status-disabled">

                                    <span class="status-dot-small"></span>

                                    Compte désactivé

                                </span>

                            @endif

                        </div>

                    </div>


                    {{-- ================================
                         INFORMATIONS
                    ================================= --}}
                    <div class="employee-profile-content">

                        <div class="profile-section-title">

                            <i class="bi bi-person-vcard"></i>

                            Informations personnelles

                        </div>


                        <div class="profile-grid">

                            {{-- Nom --}}
                            <div class="profile-item">

                                <span class="profile-label">
                                    Nom
                                </span>

                                <span class="profile-value">
                                    {{ $employee->nom }}
                                </span>

                            </div>


                            {{-- Prénom --}}
                            <div class="profile-item">

                                <span class="profile-label">
                                    Prénom
                                </span>

                                <span class="profile-value">
                                    {{ $employee->prenom }}
                                </span>

                            </div>


                            {{-- Email --}}
                            <div class="profile-item">

                                <span class="profile-label">
                                    Adresse email
                                </span>

                                <span class="profile-value">

                                    {{ $employee->email }}

                                </span>

                            </div>


                            {{-- Téléphone --}}
                            <div class="profile-item">

                                <span class="profile-label">
                                    Téléphone
                                </span>

                                <span class="profile-value">

                                    {{ $employee->telephone ?? 'Non renseigné' }}

                                </span>

                            </div>

                        </div>


                        {{-- ================================
                             INFORMATIONS PROFESSIONNELLES
                        ================================= --}}
                        <div class="profile-section-title mt-4">

                            <i class="bi bi-briefcase"></i>

                            Informations professionnelles

                        </div>


                        <div class="profile-grid">

                            {{-- Matricule --}}
                            <div class="profile-item">

                                <span class="profile-label">
                                    Matricule
                                </span>

                                <span class="profile-value">

                                    {{ $employee->matricule }}

                                </span>

                            </div>


                            {{-- Service --}}
                            <div class="profile-item">

                                <span class="profile-label">
                                    Service
                                </span>

                                <span class="profile-value">

                                    {{ $employee->service }}

                                </span>

                            </div>

                        </div>


                        {{-- ================================
                             AUTHENTIFICATION
                        ================================= --}}
                        <div class="profile-section-title mt-4">

                            <i class="bi bi-shield-lock"></i>

                            Authentification

                        </div>


                        <div class="profile-grid">

                            {{-- RFID --}}
                            <div class="profile-item">

                                <span class="profile-label">
                                    Identifiant RFID
                                </span>

                                <span class="profile-value">

                                    @if($employee->rfid_uid)

                                        <span class="rfid-code">

                                            <i class="bi bi-credit-card-2-front"></i>

                                            {{ $employee->rfid_uid }}

                                        </span>

                                    @else

                                        <span class="text-muted">
                                            Non attribué
                                        </span>

                                    @endif

                                </span>

                            </div>


                            {{-- Reconnaissance faciale --}}
                            <div class="profile-item">

                                <span class="profile-label">
                                    Reconnaissance faciale
                                </span>

                                <span class="profile-value">

                                    @if($employee->face_encoding)

                                        <span class="face-status face-active">

                                            <i class="bi bi-check-circle-fill"></i>

                                            Enregistrée

                                        </span>

                                    @else

                                        <span class="face-status face-missing">

                                            <i class="bi bi-exclamation-circle-fill"></i>

                                            Non enregistrée

                                        </span>

                                    @endif

                                </span>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                 FOOTER
            ================================================== --}}
            <div class="modal-footer employee-modal-footer">

                <button
                    type="button"
                    class="btn btn-light"
                    data-bs-dismiss="modal"
                >

                    Fermer

                </button>


                <a
                    href="{{ route('employees.edit', $employee) }}"
                    class="btn btn-primary"
                >

                    <i class="bi bi-pencil me-2"></i>

                    Modifier l'employé

                </a>

            </div>

        </div>

    </div>

</div>


                @empty

                    {{-- =================================================
                         AUCUN EMPLOYÉ
                    ================================================== --}}
                    <tr>

                        <td colspan="6">

                            <div class="empty-employees">

                                <div class="empty-icon">

                                    <i class="bi bi-people"></i>

                                </div>


                                @if(request('search'))

                                    <h6>
                                        Aucun employé trouvé
                                    </h6>

                                    <p>

                                        Aucun résultat pour
                                        <strong>
                                            "{{ request('search') }}"
                                        </strong>

                                    </p>

                                    <a
                                        href="{{ route('employees.index') }}"
                                        class="btn btn-sm btn-outline-primary"
                                    >

                                        <i class="bi bi-arrow-counterclockwise me-1"></i>

                                        Réinitialiser

                                    </a>

                                @else

                                    <h6>
                                        Aucun employé enregistré
                                    </h6>

                                    <p>
                                        Commencez par ajouter votre premier employé.
                                    </p>

                                    <a
                                        href="{{ route('employees.create') }}"
                                        class="btn btn-sm btn-primary"
                                    >

                                        <i class="bi bi-plus-circle me-1"></i>

                                        Ajouter un employé

                                    </a>

                                @endif

                            </div>

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>


        {{-- =====================================================
             PAGINATION
        ====================================================== --}}
        @if($employees->hasPages())

            <div class="employees-pagination">

                <div class="pagination-info">

                    Affichage de

                    <strong>
                        {{ $employees->firstItem() }}
                    </strong>

                    à

                    <strong>
                        {{ $employees->lastItem() }}
                    </strong>

                    sur

                    <strong>
                        {{ $employees->total() }}
                    </strong>

                </div>


                <div class="custom-pagination">

                    {{-- Page précédente --}}
                    @if($employees->onFirstPage())

                        <span class="pagination-arrow disabled">
                            <i class="bi bi-chevron-left"></i>
                        </span>

                    @else

                        <a
                            href="{{ $employees->previousPageUrl() }}"
                            class="pagination-arrow"
                        >

                            <i class="bi bi-chevron-left"></i>

                        </a>

                    @endif


                    {{-- Numéros --}}
                    @foreach($employees->getUrlRange(
                        max(1, $employees->currentPage() - 2),
                        min($employees->lastPage(), $employees->currentPage() + 2)
                    ) as $page => $url)

                        @if($page == $employees->currentPage())

                            <span class="pagination-number active">
                                {{ $page }}
                            </span>

                        @else

                            <a
                                href="{{ $url }}"
                                class="pagination-number"
                            >

                                {{ $page }}

                            </a>

                        @endif

                    @endforeach


                    {{-- Page suivante --}}
                    @if($employees->hasMorePages())

                        <a
                            href="{{ $employees->nextPageUrl() }}"
                            class="pagination-arrow"
                        >

                            <i class="bi bi-chevron-right"></i>

                        </a>

                    @else

                        <span class="pagination-arrow disabled">
                            <i class="bi bi-chevron-right"></i>
                        </span>

                    @endif

                </div>

            </div>

        @endif

    </div>

</div>


{{-- =============================================================
     STYLE SPÉCIFIQUE À LA PAGE EMPLOYÉS
============================================================= --}}
@push('styles')

<style>

/* =========================================================
   TOOLBAR
========================================================= */

.employees-toolbar {

    padding: 18px 20px;

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 20px;

    border-bottom: 1px solid var(--border);

}

.employees-title-row {

    display: flex;

    align-items: center;

    gap: 12px;

}

.employees-title-icon {

    width: 38px;

    height: 38px;

    border-radius: 10px;

    background: #eff6ff;

    color: var(--primary);

    display: flex;

    align-items: center;

    justify-content: center;

    font-size: 17px;

}


/* =========================================================
   OUTILS
========================================================= */

.employees-tools {

    display: flex;

    align-items: center;

    gap: 9px;

}


/* =========================================================
   RECHERCHE
========================================================= */

.employee-search {

    height: 40px;

    width: 310px;

    display: flex;

    align-items: center;

    position: relative;

    background: #f8fafc;

    border: 1px solid #e2e8f0;

    border-radius: 10px;

    transition: all .2s ease;

}

.employee-search:focus-within {

    background: white;

    border-color: rgba(37,99,235,.45);

    box-shadow:
        0 0 0 3px rgba(37,99,235,.08);

}

.search-icon {

    margin-left: 13px;

    color: #94a3b8;

    font-size: 14px;

}

.employee-search input {

    flex: 1;

    height: 100%;

    min-width: 0;

    border: none;

    outline: none;

    background: transparent;

    padding: 0 10px;

    font-size: 12px;

    color: var(--text);

}

.employee-search input::placeholder {

    color: #94a3b8;

}


/* =========================================================
   EFFACER
========================================================= */

.search-clear {

    color: #94a3b8;

    font-size: 13px;

    text-decoration: none;

    display: flex;

    align-items: center;

    margin-right: 5px;

    transition: .2s;

}

.search-clear:hover {

    color: var(--danger);

}


/* =========================================================
   BOUTON RECHERCHE
========================================================= */

.search-submit {

    width: 34px;

    height: 32px;

    margin-right: 3px;

    border: none;

    border-radius: 8px;

    background: var(--primary);

    color: white;

    display: flex;

    align-items: center;

    justify-content: center;

    cursor: pointer;

    transition: .2s;

}

.search-submit:hover {

    background: var(--primary-dark);

    transform: translateX(1px);

}


/* =========================================================
   ACTUALISATION
========================================================= */

.refresh-button {

    width: 40px;

    height: 40px;

    border-radius: 10px;

    border: 1px solid #e2e8f0;

    background: white;

    color: #64748b;

    display: flex;

    align-items: center;

    justify-content: center;

    text-decoration: none;

    transition: all .2s ease;

}

.refresh-button:hover {

    color: var(--primary);

    border-color: #bfdbfe;

    background: #eff6ff;

    transform: rotate(20deg);

}


/* =========================================================
   EMPLOYÉ
========================================================= */

.employee-photo {

    width: 40px;

    height: 40px;

    border-radius: 10px;

    object-fit: cover;

}

.employee-avatar-placeholder {

    display: flex;

    align-items: center;

    justify-content: center;

    background: #eff6ff;

    color: #60a5fa;

    font-size: 17px;

}

.employee-name {

    font-weight: 600;

    font-size: 12px;

}

.employee-service {

    color: var(--muted);

    font-size: 10px;

    margin-top: 3px;

}

.employee-matricule {

    font-size: 11px;

    font-weight: 600;

    color: #334155;

}

.employee-service-name {

    font-size: 11px;

    color: #475569;

}

.rfid-code {

    display: inline-flex;

    align-items: center;

    font-size: 10px;

    color: #475569;

    background: #f8fafc;

    padding: 5px 8px;

    border-radius: 6px;

}


/* =========================================================
   STATUT
========================================================= */

.status-badge {

    display: inline-flex;

    align-items: center;

    gap: 6px;

    padding: 5px 9px;

    border-radius: 20px;

    font-size: 9px;

    font-weight: 700;

}

.status-active {

    background: #ecfdf5;

    color: #15803d;

}

.status-disabled {

    background: #fef2f2;

    color: #dc2626;

}

.status-dot-small {

    width: 6px;

    height: 6px;

    border-radius: 50%;

    background: currentColor;

}


/* =========================================================
   ACTIONS — ICONES SANS CADRE
========================================================= */

.employee-actions {

    display: flex;

    align-items: center;

    justify-content: flex-end;

    gap: 13px;

}

.action-icon {

    border: none;

    background: transparent;

    padding: 3px;

    margin: 0;

    font-size: 15px;

    line-height: 1;

    cursor: pointer;

    text-decoration: none;

    transition:
        transform .2s ease,
        color .2s ease;

}

.action-icon:hover {

    transform: translateY(-2px);

}

.action-view {

    color: #64748b;

}

.action-view:hover {

    color: var(--primary);

}

.action-edit {

    color: #f59e0b;

}

.action-edit:hover {

    color: #d97706;

}

.action-delete {

    color: #94a3b8;

}

.action-delete:hover {

    color: var(--danger);

}


/* =========================================================
   TABLEAU
========================================================= */

.attendance-table tbody tr {

    transition: background .15s ease;

}

.attendance-table tbody tr:hover {

    background: #f8fafc;

}


/* =========================================================
   EMPTY STATE
========================================================= */

.empty-employees {

    padding: 55px 20px;

    text-align: center;

}

.empty-icon {

    width: 60px;

    height: 60px;

    margin: 0 auto 15px;

    border-radius: 16px;

    background: #f1f5f9;

    color: #94a3b8;

    display: flex;

    align-items: center;

    justify-content: center;

    font-size: 25px;

}

.empty-employees h6 {

    font-size: 13px;

    margin-bottom: 6px;

}

.empty-employees p {

    font-size: 11px;

    color: var(--muted);

    margin-bottom: 18px;

}


/* =========================================================
   PAGINATION
========================================================= */

.employees-pagination {

    border-top: 1px solid var(--border);

    padding: 14px 20px;

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 15px;

}

.pagination-info {

    color: var(--muted);

    font-size: 10px;

}

.pagination-info strong {

    color: #334155;

}


.custom-pagination {

    display: flex;

    align-items: center;

    gap: 5px;

}

.pagination-number,
.pagination-arrow {

    width: 30px;

    height: 30px;

    border-radius: 7px;

    display: flex;

    align-items: center;

    justify-content: center;

    text-decoration: none;

    font-size: 11px;

    font-weight: 600;

    color: #64748b;

    transition: .2s;

}

.pagination-number:hover,
.pagination-arrow:hover {

    background: #eff6ff;

    color: var(--primary);

}

.pagination-number.active {

    background: var(--primary);

    color: white;

    box-shadow:
        0 3px 8px rgba(37,99,235,.2);

}

.pagination-arrow {

    color: #475569;

}

.pagination-arrow.disabled {

    color: #cbd5e1;

    cursor: not-allowed;

}


/* =========================================================
   RESPONSIVE
========================================================= */

@media(max-width: 900px) {

    .employees-toolbar {

        align-items: flex-start;

        flex-direction: column;

    }

    .employees-tools {

        width: 100%;

    }

    .employee-search {

        flex: 1;

        width: auto;

    }

}

@media(max-width: 600px) {

    .employees-pagination {

        flex-direction: column;

        align-items: flex-start;

    }

    .pagination-info {

        order: 2;

    }

    .custom-pagination {

        order: 1;

    }

    .employees-tools {

        width: 100%;

    }

    .refresh-button {

        flex-shrink: 0;

    }

}



/* =========================================================
   MODAL PROFIL EMPLOYÉ
========================================================= */

.employee-modal {

    border: none;

    border-radius: 18px;

    overflow: hidden;

    box-shadow:
        0 25px 60px rgba(15, 23, 42, .18);

}


/* =========================================================
   HEADER MODAL
========================================================= */

.employee-modal-header {

    padding: 20px 24px;

    border-bottom: 1px solid var(--border);

    background: white;

}


/* =========================================================
   PROFIL HEADER
========================================================= */

.employee-profile-header {

    padding: 26px 28px;

    display: flex;

    align-items: center;

    gap: 20px;

    background:
        linear-gradient(
            135deg,
            #f8fafc 0%,
            #eff6ff 100%
        );

    border-bottom: 1px solid var(--border);

}


.employee-profile-photo-wrapper {

    flex-shrink: 0;

}


.employee-profile-photo,
.employee-profile-placeholder {

    width: 82px;

    height: 82px;

    border-radius: 18px;

    object-fit: cover;

}


.employee-profile-placeholder {

    background: #dbeafe;

    color: #2563eb;

    display: flex;

    align-items: center;

    justify-content: center;

    font-size: 30px;

}


.employee-profile-main h4 {

    margin: 0 0 4px;

    font-size: 19px;

    font-weight: 700;

}


.employee-profile-main p {

    margin: 0 0 9px;

    color: var(--muted);

    font-size: 11px;

}


.profile-separator {

    margin: 0 5px;

    color: #cbd5e1;

}


/* =========================================================
   CONTENU
========================================================= */

.employee-profile-content {

    padding: 24px 28px;

}


.profile-section-title {

    display: flex;

    align-items: center;

    gap: 8px;

    margin-bottom: 14px;

    font-size: 11px;

    font-weight: 700;

    color: #334155;

    text-transform: uppercase;

    letter-spacing: .4px;

}


.profile-section-title i {

    color: var(--primary);

    font-size: 14px;

}


/* =========================================================
   GRID
========================================================= */

.profile-grid {

    display: grid;

    grid-template-columns: repeat(2, 1fr);

    gap: 10px;

}


.profile-item {

    padding: 12px 14px;

    background: #f8fafc;

    border: 1px solid #f1f5f9;

    border-radius: 9px;

}


.profile-label {

    display: block;

    color: #94a3b8;

    font-size: 9px;

    margin-bottom: 5px;

}


.profile-value {

    display: block;

    color: #334155;

    font-size: 11px;

    font-weight: 600;

}


/* =========================================================
   RECONNAISSANCE FACIALE
========================================================= */

.face-status {

    display: inline-flex;

    align-items: center;

    gap: 5px;

    font-size: 10px;

}


.face-active {

    color: #15803d;

}


.face-missing {

    color: #d97706;

}


/* =========================================================
   FOOTER
========================================================= */

.employee-modal-footer {

    padding: 14px 24px;

    border-top: 1px solid var(--border);

    background: #fafafa;

}


/* =========================================================
   RESPONSIVE
========================================================= */

@media(max-width: 576px) {

    .employee-profile-header {

        padding: 20px;

    }

    .employee-profile-content {

        padding: 20px;

    }

    .profile-grid {

        grid-template-columns: 1fr;

    }

    .employee-profile-main h4 {

        font-size: 16px;

    }

}

</style>

@endpush

@endsection