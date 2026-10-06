@extends('layouts.admin')

@section('title', 'Dashboard | SecureAccess')


@section('content')

<div class="content">


    {{-- ==========================
         STATISTIQUES
    =========================== --}}

    <div class="row g-3 mb-4">


        {{-- Employés --}}

        <div class="col-12 col-sm-6 col-xl-3">

            <div class="stat-card">

                <div class="d-flex justify-content-between">

                    <div>

                        <div class="stat-label">
                            Total employés
                        </div>

                        <div class="stat-number">
                            {{ $totalEmployees }}
                        </div>

                        <div class="stat-change text-success">

                            <i class="bi bi-arrow-up"></i>

                            4,2% ce mois

                        </div>

                    </div>

                    <div
                        class="stat-icon bg-primary bg-opacity-10 text-primary">

                        <i class="bi bi-people-fill"></i>

                    </div>

                </div>

            </div>

        </div>


        {{-- Présents --}}

        <div class="col-12 col-sm-6 col-xl-3">

            <div class="stat-card">

                <div class="d-flex justify-content-between">

                    <div>

                        <div class="stat-label">
                            Présents aujourd'hui
                        </div>

                        <div class="stat-number" id="presentEmployeesStat">
                            {{ $presentEmployees }}
                        </div>

                        <div class="stat-change text-success" id="presencePercentage">

                            <i class="bi bi-check-circle"></i>
                             {{ $totalEmployees > 0 ? number_format(($presentEmployees / $totalEmployees) * 100, 1, ',', ' ') : '0,0' }}% du personnel

                        </div>

                    </div>

                    <div
                        class="stat-icon bg-success bg-opacity-10 text-success">

                        <i class="bi bi-person-check-fill"></i>

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
                            Retards détectés
                        </div>

                        <div class="stat-number" id="lateTodayStat">
                            {{ $lateToday }}
                        </div>

                        <div class="stat-change text-warning">

                            <i class="bi bi-clock"></i>

                            Aujourd'hui

                        </div>

                    </div>

                    <div
                        class="stat-icon bg-warning bg-opacity-10 text-warning">

                        <i class="bi bi-clock-history"></i>

                    </div>

                </div>

            </div>

        </div>


        {{-- Fraudes --}}

        <div class="col-12 col-sm-6 col-xl-3">

            <div class="stat-card">

                <div class="d-flex justify-content-between">

                    <div>

                        <div class="stat-label">
                            Alertes de fraude
                        </div>

                        <div class="stat-number text-danger">
                            03
                        </div>

                        <div class="stat-change text-danger">

                            <i class="bi bi-exclamation-triangle"></i>

                            2 nouvelles

                        </div>

                    </div>

                    <div
                        class="stat-icon bg-danger bg-opacity-10 text-danger">

                        <i class="bi bi-shield-fill-exclamation"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ==========================
         GRAPHIQUE + SYSTEME
    =========================== --}}

    <div class="row g-3 mb-4">


        {{-- Graphique --}}

        <div class="col-xl-8">

            <div class="dashboard-card">

                <div class="card-header-custom">

                    <div>

                        <div class="card-title-custom">
                            Activité de présence
                        </div>

                        <div
                            class="text-muted"
                            style="font-size:10px;">

                            Nombre de pointages cette semaine

                        </div>

                    </div>


                    <select
                        class="form-select form-select-sm"
                        style="width:120px;font-size:10px;">

                        <option>
                            Cette semaine
                        </option>

                        <option>
                            Ce mois
                        </option>

                    </select>

                </div>

                <div class="card-body-custom">

                    <canvas
                        id="attendanceChart"
                        height="110">
                    </canvas>

                </div>

            </div>

        </div>


        {{-- Etat système --}}

        <div class="col-xl-4">

            <div class="dashboard-card h-100">

                <div class="card-header-custom">

                    <div class="card-title-custom">

                        État du système

                    </div>

                    <span class="live-badge">

                        <span class="live-dot"></span>

                        OPÉRATIONNEL

                    </span>

                </div>


                <div class="card-body-custom">


                    <div class="system-item">

                        <div>

                            <i class="bi bi-camera-video me-2 text-primary"></i>

                            <span class="system-name">
                                Caméra biométrique
                            </span>

                        </div>

                        <span class="system-online">
                            ● Active
                        </span>

                    </div>


                    <div class="system-item">

                        <div>

                            <i class="bi bi-rss me-2 text-primary"></i>

                            <span class="system-name">
                                Lecteur RFID
                            </span>

                        </div>

                        <span class="system-online">
                            ● Actif
                        </span>

                    </div>


                    <div class="system-item">

                        <div>

                            <i class="bi bi-hdd-network me-2 text-primary"></i>

                            <span class="system-name">
                                Station biométrique
                            </span>

                        </div>

                        <span class="system-online">
                            ● Connectée
                        </span>

                    </div>


                    <div class="system-item">

                        <div>

                            <i class="bi bi-broadcast me-2 text-primary"></i>

                            <span class="system-name">
                                WebSocket
                            </span>

                        </div>

                        <span class="system-online">
                            ● Connecté
                        </span>

                    </div>


                    <div class="mt-3 p-3 bg-light rounded-3">

                        <div
                            class="text-muted"
                            style="font-size:9px;">

                            IP de la station

                        </div>

                        <div
                            class="fw-bold mt-1"
                            style="font-size:12px;">

                            192.168.10.20

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ==========================
         TABLE + FRAUDES
    =========================== --}}

    <div class="row g-3">


        {{-- Flux présence --}}

        <div class="col-xl-8">

            <div class="dashboard-card">

                <div class="card-header-custom">

                    <div>

                        <div class="card-title-custom">

                            Flux des présences

                        </div>

                        <div
                            class="text-muted"
                            style="font-size:10px;">

                            Événements reçus en temps réel

                        </div>

                    </div>

                    <span class="live-badge">

                        <span class="live-dot"></span>

                        LIVE

                    </span>

                </div>


                <div class="table-responsive">

                    <table
                        class="table mb-0 attendance-table">

                        <thead>

                            <tr>

                                <th>
                                    Employé
                                </th>

                                <th>
                                    Matricule
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


                        <tbody id="attendanceTable">
                           

                            @forelse($recentPointages as $pointage)

                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">

                                        @if($pointage->user->photo)
                                        <img
                                            src="{{ asset('storage/' . $pointage->user->photo) }}"
                                            class="employee-photo"
                                            alt="Photo employé"
                                        >
                                        @else
                                        <div class="employee-photo bg-light d-flex align-items-center justify-content-center">
                                            <i class="bi bi-person text-secondary"></i>
                                        </div>
                                        @endif

                                        <div>
                                            <div class="employee-name">
                                                {{ $pointage->user->prenom }} {{ $pointage->user->nom }}
                                            </div>

                                            <div class="employee-service">
                                                {{ $pointage->user->service }}
                                            </div>
                                        </div>

                                    </div>
                                </td>

                                <td>
                                    {{ $pointage->user->matricule }}
                                </td>

                                <td>
                                    {{ $pointage->heure }}
                                </td>

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
                                <td colspan="5" class="text-center text-muted py-4">
                                    <i class="bi bi-calendar-x me-1"></i>
                                    Aucun pointage enregistré aujourd'hui.
                                </td>
                            </tr>

                            @endforelse 

                        </tbody>


                    </table>

                </div>

            </div>

        </div>


        {{-- Fraudes --}}

        <div class="col-xl-4">

            <div class="dashboard-card">

                <div class="card-header-custom">

                    <div class="card-title-custom">

                        Dernières fraudes

                    </div>

                    <span
                        class="badge bg-danger">

                        03

                    </span>

                </div>


                <div class="card-body-custom">


                    @forelse($frauds as $fraud)

                    <div class="fraud-item">

                        {{-- Photo de la capture si disponible --}}
                        @if($fraud->photo_capture)
                            <img
                                src="{{ route('fraud.photo', ['filename' => $fraud->photo_capture]) }}"
                                class="fraud-photo"
                                alt="Capture fraude"
                            >
                        @else
                    <div class="fraud-photo bg-light d-flex align-items-center justify-content-center">
                        <i class="bi bi-shield-exclamation text-danger"></i>
                    </div>
                    @endif

                    <div class="flex-grow-1">

                        {{-- Type de fraude --}}
                        <div class="fraud-title">
                            @switch($fraud->type)

                                @case('BADGE_INCONNU')
                                Badge inconnu
                                @break

                                @case('VISAGE_INVALIDE')
                                Visage non reconnu
                                @break

                                @case('IP_NON_AUTORISEE')
                                IP non autorisée
                                @break

                                @case('ANTI_PASSBACK')
                                Anti-passback
                                @break

                                @default
                                Fraude détectée

                            @endswitch
                        </div>

                        {{-- Description --}}
                        <div class="fraud-info">
                            {{ $fraud->description }}
                        </div>

                        {{-- IP de la station --}}
                        <div class="fraud-info">
                                IP : {{ $fraud->ip_station }}
                        </div>

                        {{-- Heure --}}
                        <div class="fraud-info">
                            {{ $fraud->heure_fraude->format('d/m/Y · H:i') }}
                        </div>

                    </div>

                    <button class="btn btn-sm btn-outline-danger">
                        <i class="bi bi-eye"></i>
                    </button>

                </div>

                @empty

                <div class="text-center text-muted py-4">
                    <i class="bi bi-shield-check me-1"></i>
                    Aucune fraude enregistrée.
                </div>

                @endforelse


                    <button
                        class="btn btn-light w-100 mt-3"
                        style="font-size:11px;">

                        Voir tout le journal

                        <i class="bi bi-arrow-right ms-1"></i>

                    </button>


                </div>

            </div>

        </div>

    </div>

</div>

@endsection


@push('scripts')

<script>

    /* ==========================
       GRAPHIQUE
    =========================== */

    const ctx = document.getElementById('attendanceChart');

    const attendanceChart =new Chart(ctx, {
        type: 'line',

        data: {
            labels: @json($chartLabels),

            datasets: [{
                label: 'Pointages',
                data: @json($chartData),
                borderWidth: 2,
                tension: .4,
                fill: true
            }]
        },

        options: {
            responsive: true,

            plugins: {
                legend: {
                    display: false
                }
            },

            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: '#f1f5f9'
                    }
                },

                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });


    /* ==========================
       SIMULATION WEBSOCKET
    =========================== */

/* ==========================
   WEBSOCKET - POINTAGES
=========================== */

document.addEventListener('DOMContentLoaded', function () {

    const table =
        document.getElementById('attendanceTable');

    if (!table) {
        console.error(
            'Table attendanceTable introuvable.'
        );
        return;
    }


    // =====================================================
    // CONNEXION REVERB / PUSHER
    // =====================================================

    const reverb = new Pusher(
        '{{ config('broadcasting.connections.reverb.key') }}',
        {
            cluster: 'mt1',

            wsHost: '127.0.0.1',

            wsPort: 8080,

            wssPort: 8080,

            forceTLS: false,

            enabledTransports: ['ws']
        }
    );


    // =====================================================
    // ABONNEMENT AU CANAL
    // =====================================================

    const pointageChannel =
        reverb.subscribe(
            'secureaccess-pointages'
        );


    // =====================================================
    // CONNEXION WEBSOCKET
    // =====================================================

    reverb.connection.bind(
        'connected',
        function () {

            console.log(
                '🟢 WebSocket SecureAccess connecté.'
            );

        }
    );


    // =====================================================
    // ERREUR WEBSOCKET
    // =====================================================

    reverb.connection.bind(
        'error',
        function (error) {

            console.error(
                '🔴 Erreur WebSocket :',
                error
            );

        }
    );


    // =====================================================
    // NOUVEAU POINTAGE
    // =====================================================

    pointageChannel.bind(
        'pointage.enregistre',
        function (data) {

            console.log(
                '🟢 NOUVEAU POINTAGE REÇU :',
                data
            );


            // =================================================
            // VÉRIFICATION DE LA STRUCTURE
            // =================================================

            if (
                !data ||
                !data.pointage
            ) {

                console.error(
                    '❌ Payload pointage invalide :',
                    data
                );

                return;
            }


            const pointage =
                data.pointage;

                // =====================================================
// MISE À JOUR DU GRAPHIQUE EN TEMPS RÉEL
// =====================================================
//
// Laravel fournit directement les 7 valeurs à jour.
// On ne fait aucun calcul côté navigateur.
//
// =====================================================

if (
    attendanceChart &&
    Array.isArray(pointage.chart_data)
) {

    // Remplacer les données du graphique
    attendanceChart.data.datasets[0].data =
        pointage.chart_data;


    // Remplacer les labels si Laravel les fournit
    if (
        Array.isArray(pointage.chart_labels)
    ) {

        attendanceChart.data.labels =
            pointage.chart_labels;
    }


    // Rafraîchir immédiatement
    attendanceChart.update();


    console.log(
        '📊 Graphique temps réel mis à jour :',
        pointage.chart_data
    );
}


            console.log(
                '📦 Données du pointage :',
                pointage
            );


            console.log(
                '👤 Nom :',
                pointage.nom
            );


            console.log(
                '👤 Prénom :',
                pointage.prenom
            );


            console.log(
                '🪪 Matricule :',
                pointage.matricule
            );


            console.log(
                '🖼️ Photo :',
                pointage.photo_url
            );


            // =================================================
            // STATISTIQUE PRÉSENTS
            // =================================================

            const presentStat =
                document.getElementById(
                    'presentEmployeesStat'
                );

            const percentageStat =
                document.getElementById(
                    'presencePercentage'
                );


            if (presentStat) {

                let presentCount =
                    parseInt(
                        presentStat.textContent.trim()
                    ) || 0;


                if (
                    pointage.type === 'ENTREE'
                ) {

                    presentCount++;
                }


                if (
                    pointage.type === 'SORTIE'
                ) {

                    presentCount--;
                }


                presentCount =
                    Math.max(
                        0,
                        presentCount
                    );


                presentStat.textContent =
                    presentCount;


                const totalEmployees =
                    {{ $totalEmployees }};


                if (
                    percentageStat &&
                    totalEmployees > 0
                ) {

                    const percentage =
                        (
                            presentCount /
                            totalEmployees
                        ) * 100;


                    percentageStat.innerHTML = `
                        <i class="bi bi-check-circle"></i>
                        ${percentage
                            .toFixed(1)
                            .replace('.', ',')
                        }% du personnel
                    `;
                }
            }


            // =================================================
            // STATISTIQUE RETARDS
            // =================================================

            const lateStat =
                document.getElementById(
                    'lateTodayStat'
                );


            if (
                lateStat &&
                pointage.status === 'RETARD'
            ) {

                let lateCount =
                    parseInt(
                        lateStat.textContent.trim()
                    ) || 0;


                lateCount++;


                lateStat.textContent =
                    lateCount;
            }


            // =================================================
            // SUPPRIMER "AUCUN POINTAGE"
            // =================================================

            const emptyRow =
                table.querySelector(
                    'td[colspan="5"]'
                );


            if (emptyRow) {

                emptyRow
                    .closest('tr')
                    .remove();
            }


            // =================================================
            // TYPE
            // =================================================

            const typeHtml =
                pointage.type === 'ENTREE'

                ? `
                    <span class="badge-entry">
                        Entrée
                    </span>
                  `

                : `
                    <span class="badge-exit">
                        Sortie
                    </span>
                  `;


            // =================================================
            // STATUT
            // =================================================

            const statusHtml =
                pointage.status === 'RETARD'

                ? `
                    <span
                        class="text-warning"
                        style="font-size:10px;"
                    >
                        <i class="bi bi-clock-fill"></i>
                        Retard
                    </span>
                  `

                : `
                    <span
                        class="text-success"
                        style="font-size:10px;"
                    >
                        <i class="bi bi-check-circle-fill"></i>
                        Validé
                    </span>
                  `;


            // =================================================
            // PHOTO
            // =================================================

            let photoHtml = `
                <div
                    class="
                        employee-photo
                        bg-light
                        d-flex
                        align-items-center
                        justify-content-center
                    "
                >
                    <i
                        class="
                            bi
                            bi-person
                            text-secondary
                        "
                    ></i>
                </div>
            `;


            if (
                pointage.photo_url
            ) {

                photoHtml = `
                    <img
                        src="${pointage.photo_url}"
                        class="employee-photo"
                        alt="Photo employé"
                        onerror="
                            this.style.display='none';
                        "
                    >
                `;
            }


            // =================================================
            // MATRICULE
            // =================================================

            const matricule =
                pointage.matricule
                ? pointage.matricule
                : '—';


            // =================================================
            // SERVICE
            // =================================================

            const service =
                pointage.service
                ? pointage.service
                : 'Service non renseigné';


            // =================================================
            // CRÉER LA LIGNE
            // =================================================

            const row =
                document.createElement('tr');


            row.innerHTML = `

                <td>

                    <div
                        class="
                            d-flex
                            align-items-center
                            gap-2
                        "
                    >

                        ${photoHtml}

                        <div>

                            <div
                                class="employee-name"
                            >
                                ${pointage.prenom ?? ''}
                                ${pointage.nom ?? ''}
                            </div>

                            <div
                                class="employee-service"
                            >
                                ${service}
                            </div>

                        </div>

                    </div>

                </td>


                <td>
                    ${matricule}
                </td>


                <td>
                    ${pointage.heure ?? '--:--:--'}
                </td>


                <td>
                    ${typeHtml}
                </td>


                <td>
                    ${statusHtml}
                </td>

            `;


            // =================================================
            // INSÉRER EN PREMIÈRE POSITION
            // =================================================

            table.prepend(row);


            console.log(
                '✅ Ligne ajoutée au tableau en temps réel.'
            );

        }
    );
});
</script>

@endpush