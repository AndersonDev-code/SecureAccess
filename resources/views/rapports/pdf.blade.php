<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">

    <title>Rapport SecureAccess</title>

    <style>

        @page {
            margin: 35px 35px 45px 35px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #111827;
            background: #ffffff;
        }


        /* =====================================================
           EN-TÊTE
        ===================================================== */

        .header {
            border-bottom: 2px solid #2563eb;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .logo {
            font-size: 22px;
            font-weight: bold;
            color: #2563eb;
        }

        .subtitle {
            color: #6b7280;
            font-size: 10px;
            margin-top: 4px;
        }

        .report-title {
            margin-top: 18px;
            font-size: 18px;
            font-weight: bold;
            color: #111827;
        }

        .period {
            margin-top: 5px;
            color: #6b7280;
            font-size: 9px;
        }


        /* =====================================================
           BLOC PROFIL + STATISTIQUES
        ===================================================== */

        .employee-dashboard {
            width: 100%;
            background: #f8fafc;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            margin-bottom: 20px;
            padding: 12px;
        }


        /* =====================================================
           PROFIL EMPLOYÉ
        ===================================================== */

        .employee-profile {
            width: 55%;
            vertical-align: middle;
            padding-right: 15px;
        }

        .profile-icon {
    width: 38px;
    height: 38px;
    background: #dbeafe;
    border-radius: 8px;
    text-align: center;
    vertical-align: middle;
    padding: 0;
}

/* =====================================================
   ICÔNE PERSONNE
===================================================== */

.person-icon {
    width: 22px;
    height: 28px;
    margin: 5px auto;
    position: relative;
}

/* Tête */

.person-head {
    width: 9px;
    height: 9px;
    background: #2563eb;
    border-radius: 50%;
    margin: 0 auto 2px auto;
}

/* Corps / épaules */

.person-body {
    width: 20px;
    height: 12px;
    background: #2563eb;
    border-radius: 10px 10px 4px 4px;
    margin: 0 auto;
}

        .employee-details {
            padding-left: 10px;
            vertical-align: middle;
        }

        .employee-name {
            font-size: 14px;
            font-weight: bold;
            color: #111827;
            margin-bottom: 5px;
        }

        .employee-info {
            color: #4b5563;
            font-size: 8.5px;
            line-height: 1.7;
        }

        .info-label {
            color: #6b7280;
        }


        /* =====================================================
           SÉPARATEUR
        ===================================================== */

        .vertical-separator {
            width: 1px;
            background: #e5e7eb;
        }


        /* =====================================================
           STATISTIQUES
        ===================================================== */

        .employee-stats {
            width: 45%;
            vertical-align: middle;
            padding-left: 15px;
        }

        .stats-title {
            font-size: 8px;
            font-weight: bold;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 7px;
        }

        .stats-grid {
            width: 100%;
            border-collapse: separate;
            border-spacing: 5px;
            margin: 0;
        }

        .stats-grid td {
            border: none;
            padding: 0;
        }

        .stat-card {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
        }

        .stat-card td {
            border: none;
            padding: 5px;
            vertical-align: middle;
        }


        /* =====================================================
           ICÔNES STATISTIQUES
        ===================================================== */

        .stat-icon {
            width: 25px;
            height: 25px;
            text-align: center;
            vertical-align: middle;

            border-radius: 6px;

            font-family: DejaVu Sans, sans-serif;
            font-size: 13px;
            font-weight: bold;
        }

        .stat-content {
            padding-left: 5px !important;
        }

        .stat-label {
            color: #6b7280;
            font-size: 6.5px;
            text-transform: uppercase;
            line-height: 1.2;
        }

        .stat-value {
            font-size: 12px;
            font-weight: bold;
            line-height: 1.2;
            margin-top: 2px;
        }


        /* =====================================================
           COULEURS DES STATISTIQUES
        ===================================================== */

        .blue-icon {
            background: #dbeafe;
            color: #2563eb;
        }

        .blue-value {
            color: #2563eb;
        }


        .green-icon {
            background: #dcfce7;
            color: #16a34a;
        }

        .green-value {
            color: #16a34a;
        }


        .indigo-icon {
            background: #e0e7ff;
            color: #4f46e5;
        }

        .indigo-value {
            color: #4f46e5;
        }


        .orange-icon {
            background: #fef3c7;
            color: #d97706;
        }

        .orange-value {
            color: #d97706;
        }


        /* =====================================================
           TABLEAU DES POINTAGES
        ===================================================== */

        .pointages-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .pointages-table th {
            background: #111827;
            color: white;
            padding: 8px 6px;
            text-align: left;
            font-size: 8px;
        }

        .pointages-table td {
            padding: 7px 6px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 8px;
        }

        .pointages-table tr:nth-child(even) {
            background: #f9fafb;
        }


        /* =====================================================
           BADGES
        ===================================================== */

        .badge {
            padding: 3px 6px;
            border-radius: 4px;
            font-size: 7px;
            font-weight: bold;
        }

        .entry {
            color: #15803d;
            background: #dcfce7;
        }

        .exit {
            color: #1d4ed8;
            background: #dbeafe;
        }

        .normal {
            color: #15803d;
            background: #dcfce7;
        }

        .late {
            color: #b45309;
            background: #fef3c7;
        }


        /* =====================================================
           AUCUN RÉSULTAT
        ===================================================== */

        .empty {
            text-align: center;
            padding: 25px;
            color: #6b7280;
        }


        /* =====================================================
           PIED DE PAGE
        ===================================================== */

        .footer {
            position: fixed;
            bottom: -25px;
            left: 0;
            right: 0;
            text-align: center;
            color: #9ca3af;
            font-size: 8px;
        }

    </style>
</head>


<body>


    {{-- =====================================================
         EN-TÊTE
    ===================================================== --}}

    <div class="header">

        <div class="logo">
            SecureAccess
        </div>

        <div class="subtitle">
            Système de contrôle d'accès et de gestion des présences
        </div>

        <div class="report-title">

            @switch($typeRapport)

                @case('personnel')
                    Rapport d'un personnel
                    @break

                @case('retard')
                    Rapport des retards
                    @break

                @case('entrees_sortie')
                    Rapport des entrées et sorties
                    @break

                @default
                    Rapport des pointages

            @endswitch

        </div>

        <div class="period">

            Période :
            {{ \Carbon\Carbon::parse($dateDebut)->format('d/m/Y') }}

            au

            {{ \Carbon\Carbon::parse($dateFin)->format('d/m/Y') }}

        </div>

    </div>


    {{-- =====================================================
         PROFIL + STATISTIQUES
    ===================================================== --}}

    @if($userSelectionne)

        <table class="employee-dashboard">

            <tr>

                {{-- =================================================
                     PROFIL EMPLOYÉ
                ================================================== --}}

                <td class="employee-profile">

                    <table style="width:100%; margin:0; border-collapse:collapse;">

                        <tr>

                            {{-- ICÔNE PROFIL --}}

                            <td
                                class="profile-icon"
                                style="width:38px;">

                                <div class="person-icon">

                                 <div class="person-head"></div>

                                    <div class="person-body"></div>

                                </div>

                            </td>


                            {{-- INFORMATIONS EMPLOYÉ --}}

                            <td class="employee-details">

                                <div class="employee-name">

                                    {{ $userSelectionne->nom }}
                                    {{ $userSelectionne->prenom }}

                                </div>

                                <div class="employee-info">

                                    <span class="info-label">
                                        Matricule :
                                    </span>

                                    {{ $userSelectionne->matricule ?? '—' }}

                                    &nbsp;&nbsp;

                                    <span class="info-label">
                                        Service :
                                    </span>

                                    {{ $userSelectionne->service ?? '—' }}

                                    <br>

                                    <span class="info-label">
                                        Email :
                                    </span>

                                    {{ $userSelectionne->email ?? '—' }}

                                </div>

                            </td>

                        </tr>

                    </table>

                </td>


                {{-- =================================================
                     SÉPARATEUR
                ================================================== --}}

                <td
                    class="vertical-separator"
                    style="width:1px;">

                </td>


                {{-- =================================================
                     STATISTIQUES
                ================================================== --}}

                <td class="employee-stats">

                    <div class="stats-title">
                        Synthèse de la période
                    </div>


                    <table class="stats-grid">

                        <tr>

                            {{-- PRÉSENTS --}}

                            <td style="width:50%;">

                                <table class="stat-card">

                                    <tr>

                                        <td
                                            class="stat-icon blue-icon"
                                            style="width:25px;">

                                            ✓

                                        </td>

                                        <td class="stat-content">

                                            <div class="stat-label">
                                                Présents
                                            </div>

                                            <div class="stat-value blue-value">
                                                {{ $totalPresents }}
                                            </div>

                                        </td>

                                    </tr>

                                </table>

                            </td>


                            {{-- ENTRÉES --}}

                            <td style="width:50%;">

                                <table class="stat-card">

                                    <tr>

                                        <td
                                            class="stat-icon green-icon"
                                            style="width:25px;">

                                            →

                                        </td>

                                        <td class="stat-content">

                                            <div class="stat-label">
                                                Entrées
                                            </div>

                                            <div class="stat-value green-value">
                                                {{ $totalEntrees }}
                                            </div>

                                        </td>

                                    </tr>

                                </table>

                            </td>

                        </tr>


                        <tr>

                            {{-- SORTIES --}}

                            <td style="width:50%;">

                                <table class="stat-card">

                                    <tr>

                                        <td
                                            class="stat-icon indigo-icon"
                                            style="width:25px;">

                                            ←

                                        </td>

                                        <td class="stat-content">

                                            <div class="stat-label">
                                                Sorties
                                            </div>

                                            <div class="stat-value indigo-value">
                                                {{ $totalSorties }}
                                            </div>

                                        </td>

                                    </tr>

                                </table>

                            </td>


                            {{-- RETARDS --}}

                            <td style="width:50%;">

                                <table class="stat-card">

                                    <tr>

                                        <td
                                            class="stat-icon orange-icon"
                                            style="width:25px;">

                                            ◷

                                        </td>

                                        <td class="stat-content">

                                            <div class="stat-label">
                                                Retards
                                            </div>

                                            <div class="stat-value orange-value">
                                                {{ $totalRetards }}
                                            </div>

                                        </td>

                                    </tr>

                                </table>

                            </td>

                        </tr>

                    </table>

                </td>

            </tr>

        </table>

    @endif


    {{-- =====================================================
         TABLEAU DES POINTAGES
    ===================================================== --}}

    <table class="pointages-table">

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

                            {{ $pointage->user->nom }}
                            {{ $pointage->user->prenom }}

                        @else

                            Utilisateur inconnu

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

                            <span class="badge entry">
                                Entrée
                            </span>

                        @else

                            <span class="badge exit">
                                Sortie
                            </span>

                        @endif

                    </td>


                    <td>

                        @if($pointage->status === 'RETARD')

                            <span class="badge late">
                                Retard
                            </span>

                        @else

                            <span class="badge normal">
                                Normal
                            </span>

                        @endif

                    </td>

                </tr>

            @empty

                <tr>

                    <td
                        colspan="6"
                        class="empty">

                        Aucun pointage ne correspond aux critères sélectionnés.

                    </td>

                </tr>

            @endforelse

        </tbody>

    </table>


    {{-- =====================================================
         PIED DE PAGE
    ===================================================== --}}

    <div class="footer">

        SecureAccess —
        Rapport généré le
        {{ now()->format('d/m/Y à H:i') }}

    </div>

</body>

</html>