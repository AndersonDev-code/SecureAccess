<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'SecureAccess')</title>

    {{-- Bootstrap --}}
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    {{-- Bootstrap Icons --}}
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
        rel="stylesheet"
    >

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    {{-- Pusher JS pour Laravel Reverb --}}
    <!-- <script src="{{ asset('js/pusher.min.js') }}"></script> -->
     <script src="https://js.pusher.com/8.4.0/pusher.min.js"></script>

    {{-- Font --}}
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

    <style>

        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --sidebar: #111827;
            --sidebar-hover: #1f2937;
            --background: #f5f7fb;
            --white: #ffffff;
            --text: #111827;
            --muted: #6b7280;
            --border: #e5e7eb;
            --success: #16a34a;
            --danger: #dc2626;
            --warning: #f59e0b;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background: var(--background);
            color: var(--text);
        }

        /* =========================
           SIDEBAR
        ========================= */

        .sidebar {
    position: fixed;
    top: 0;
    left: 0;
    width: 255px;
    height: 100vh;
    background: var(--sidebar);
    color: white;
    z-index: 1000;
    display: flex;
    flex-direction: column;
    overflow-y: auto;
    overflow-x: hidden;
}

        .brand {
            height: 75px;
            display: flex;
            align-items: center;
            padding: 0 25px;
            border-bottom: 1px solid rgba(255,255,255,.08);
        }

        .brand-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            font-size: 21px;
        }

        .brand-name {
            font-size: 18px;
            font-weight: 800;
        }

        .brand-subtitle {
            display: block;
            font-size: 9px;
            color: #9ca3af;
            letter-spacing: .5px;
        }

        .sidebar-menu {
            padding: 20px 12px;
            flex: 1;
            min-height: 0;
        }

        .menu-title {
            color: #6b7280;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 0 13px;
            margin: 12px 0 10px;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 13px;
            color: #9ca3af;
            padding: 12px 13px;
            border-radius: 9px;
            margin-bottom: 4px;
            font-size: 13px;
            text-decoration: none;
            transition: .2s;
        }

        .sidebar-link i {
            font-size: 17px;
        }

        .sidebar-link:hover {
            color: white;
            background: var(--sidebar-hover);
        }

        .sidebar-link.active {
            color: white;
            background: var(--primary);
        }

        .sidebar-footer {
            padding: 15px;
            border-top: 1px solid rgba(255,255,255,.08);
        }

        .sidebar::-webkit-scrollbar {
    width: 4px;
}

.sidebar::-webkit-scrollbar-track {
    background: transparent;
}

.sidebar::-webkit-scrollbar-thumb {
    background: rgba(156, 163, 175, 0.35);
    border-radius: 10px;
}

.sidebar::-webkit-scrollbar-thumb:hover {
    background: rgba(156, 163, 175, 0.55);
}

        .system-status {
            background: #1f2937;
            border-radius: 10px;
            padding: 12px;
        }

        .status-dot {
            width: 8px;
            height: 8px;
            background: #22c55e;
            border-radius: 50%;
            display: inline-block;
            margin-right: 7px;
        }

        /* =========================
           MAIN
        ========================= */

        .main {
            margin-left: 255px;
            min-height: 100vh;
        }

        /* =========================
           NAVBAR
        ========================= */

        .topbar {
            height: 75px;
            background: white;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
        }

        .page-title {
            font-size: 20px;
            font-weight: 700;
            margin: 0;
        }

        .page-subtitle {
            font-size: 12px;
            color: var(--muted);
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .notification {
            width: 38px;
            height: 38px;
            border: 1px solid var(--border);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--muted);
            position: relative;
        }

        .notification-badge {
            position: absolute;
            top: -4px;
            right: -4px;
            width: 17px;
            height: 17px;
            font-size: 9px;
            background: var(--danger);
            color: white;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .admin-profile {
            display: flex;
            align-items: center;
            gap: 10px;
            padding-left: 15px;
            border-left: 1px solid var(--border);
        }

        .admin-avatar {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: #dbeafe;
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
        }

        .admin-name {
            font-size: 13px;
            font-weight: 600;
        }

        .admin-role {
            font-size: 10px;
            color: var(--muted);
        }

        /* =========================
           CONTENT
        ========================= */

        .content {
            padding: 28px 30px;
        }

        /* =========================
           STAT CARDS
        ========================= */

        .stat-card {
            background: white;
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 20px;
            height: 100%;
            transition: .2s;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(15,23,42,.06);
        }

        .stat-icon {
            width: 46px;
            height: 46px;
            border-radius: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .stat-label {
            color: var(--muted);
            font-size: 12px;
            margin-bottom: 6px;
        }

        .stat-number {
            font-size: 26px;
            font-weight: 800;
        }

        .stat-change {
            font-size: 10px;
            margin-top: 4px;
        }

        /* =========================
           CARDS
        ========================= */

        .dashboard-card {
            background: white;
            border: 1px solid var(--border);
            border-radius: 14px;
            overflow: hidden;
        }

        .card-header-custom {
            padding: 17px 20px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-title-custom {
            font-size: 14px;
            font-weight: 700;
            margin: 0;
        }

        .card-body-custom {
            padding: 20px;
        }

        /* =========================
           LIVE
        ========================= */

        .live-badge {
            background: #dcfce7;
            color: #15803d;
            padding: 5px 9px;
            border-radius: 20px;
            font-size: 9px;
            font-weight: 700;
        }

        .live-dot {
            width: 6px;
            height: 6px;
            background: #16a34a;
            border-radius: 50%;
            display: inline-block;
            margin-right: 4px;
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {

            0% {
                box-shadow: 0 0 0 0 rgba(22,163,74,.5);
            }

            70% {
                box-shadow: 0 0 0 7px rgba(22,163,74,0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(22,163,74,0);
            }

        }

        /* =========================
           TABLE
        ========================= */

        .attendance-table th {
            font-size: 10px;
            text-transform: uppercase;
            color: var(--muted);
            font-weight: 700;
            padding: 13px 20px;
            background: #f9fafb;
        }

        .attendance-table td {
            font-size: 12px;
            padding: 13px 20px;
            vertical-align: middle;
        }

        .employee-photo {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            object-fit: cover;
        }

        .employee-name {
            font-weight: 600;
        }

        .employee-service {
            color: var(--muted);
            font-size: 10px;
        }

        .badge-entry {
            background: #dcfce7;
            color: #15803d;
        }

        .badge-exit {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .badge-entry,
        .badge-exit {
            padding: 5px 9px;
            border-radius: 6px;
            font-size: 10px;
            font-weight: 600;
        }

        .new-attendance {
            animation: newRow .7s ease;
        }

        @keyframes newRow {

            from {
                opacity: 0;
                transform: translateY(-15px);
                background: #dcfce7;
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }

        }

        /* =========================
           FRAUD
        ========================= */

        .fraud-item {
            display: flex;
            gap: 12px;
            padding: 14px 0;
            border-bottom: 1px solid var(--border);
        }

        .fraud-item:last-child {
            border-bottom: none;
        }

        .fraud-photo {
            width: 48px;
            height: 48px;
            object-fit: cover;
            border-radius: 9px;
        }

        .fraud-title {
            font-size: 12px;
            font-weight: 700;
        }

        .fraud-info {
            font-size: 10px;
            color: var(--muted);
        }

        /* =========================
           SYSTEM
        ========================= */

        .system-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid var(--border);
        }

        .system-item:last-child {
            border-bottom: none;
        }

        .system-name {
            font-size: 11px;
            font-weight: 600;
        }

        .system-online {
            font-size: 10px;
            color: var(--success);
            font-weight: 600;
        }

        /* =========================
           RESPONSIVE
        ========================= */

        
        /* =========================
   RESPONSIVE
========================= */

/* ---------- TABLETTE ---------- */

@media (max-width: 1100px) {

    .sidebar {
        width: 220px;
    }

    .main {
        margin-left: 220px;
    }

    .brand {
        padding: 0 20px;
    }

    .sidebar-menu {
        padding-left: 10px;
        padding-right: 10px;
    }

    .sidebar-link {
        font-size: 13px;
        padding: 11px 12px;
    }

    .topbar {
        padding: 0 20px;
    }

    .content {
        padding: 24px 20px;
    }

}


/* ---------- PETITE TABLETTE ---------- */

@media (max-width: 900px) {

    .sidebar {
        width: 75px;
    }

    .brand {
        justify-content: center;
        padding: 0;
    }

    .brand img {
        width: 45px !important;
        height: 45px;
        object-fit: contain;
    }

    .brand-name,
    .brand-subtitle,
    .sidebar-link span,
    .menu-title,
    .system-status {
        display: none;
    }

    .sidebar-link {
        justify-content: center;
        padding: 12px 8px;
    }

    .sidebar-link i {
        font-size: 18px;
    }

    .main {
        margin-left: 75px;
    }

    .topbar {
        padding: 0 20px;
    }

}


/* ---------- MOBILE ---------- */

@media (max-width: 768px) {

        .mobile-menu-toggle {
            display: flex !important;
        }

    .sidebar {
        width: 260px;
        transform: translateX(-100%);
        transition: transform 0.25s ease;
        box-shadow: 8px 0 25px rgba(0, 0, 0, 0.12);
        overflow-y: auto;
    }

    .sidebar.mobile-open {
        transform: translateX(0);
    }

    .brand {
        height: 70px;
        justify-content: flex-start;
        padding: 0 20px;
    }

    .brand img {
        width: 165px !important;
        height: auto;
    }

    .brand-name,
    .brand-subtitle,
    .sidebar-link span,
    .menu-title {
        display: block;
    }

    .sidebar-link {
        justify-content: flex-start;
        padding: 11px 13px;
    }

    .sidebar-link i {
        font-size: 17px;
    }

    .sidebar-menu {
        padding: 15px 12px;
    }

    .sidebar-footer {
        padding: 12px;
    }

    .main {
        margin-left: 0;
        width: 100%;
    }

    .topbar {
        height: auto;
        min-height: 70px;
        padding: 12px 15px;
        gap: 12px;
    }

    .page-title {
        font-size: 18px;
    }

    .page-subtitle {
        font-size: 11px;
    }

    .topbar-actions {
        gap: 8px;
    }

    .notification {
        width: 36px;
        height: 36px;
    }

    .admin-profile {
        padding-left: 8px;
        gap: 7px;
    }

    .admin-name,
    .admin-role {
        display: none;
    }

    .content {
        padding: 20px 15px;
    }

}


/* ---------- PETIT MOBILE ---------- */

@media (max-width: 480px) {

    .topbar {
        padding: 10px 12px;
    }

    .page-title {
        font-size: 17px;
    }

    .page-subtitle {
        font-size: 10px;
    }

    .admin-avatar {
        width: 34px;
        height: 34px;
        font-size: 12px;
    }

    .notification {
        width: 34px;
        height: 34px;
    }

    .content {
        padding: 16px 12px;
    }

}

        /* pour le bouton de deconnexion */
        .logout-link {
            color: inherit;
            transition: all 0.2s ease;
        }

        .logout-link:hover {
            color: #dc3545 !important;
            background-color: rgba(220, 53, 69, 0.10) !important;
        }

        .logout-link:hover i {
            color: #dc3545 !important;
        }

/* =========================
   BOUTON MENU MOBILE
========================= */

.mobile-menu-toggle {
    display: none !important;
    width: 38px;
    height: 38px;
    border: 1px solid var(--border);
    border-radius: 10px;
    background: white;
    color: var(--text);
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
    cursor: pointer;
}

.mobile-menu-toggle:hover {
    background: #f9fafb;
}

/* =========================
   OVERLAY MOBILE
========================= */

.mobile-overlay {
    display: none;
}

@media (max-width: 768px) {

    .mobile-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.35);
        z-index: 999;
        display: none;
    }

    .mobile-overlay.active {
        display: block;
    }

}

/* =========================
   AFFICHAGE DU MENU MOBILE
========================= */

@media (max-width: 768px) {

    .mobile-menu-toggle {
        display: flex !important;
    }

}
    </style>

    @stack('styles')

</head>

<body>

    @include('components.admin-sidebar')

    <div class="mobile-overlay" id="mobileOverlay"></div>
    <main class="main">

        @include('components.admin-navbar')

        @yield('content')

    </main>


    <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>


<script>

    document.addEventListener('DOMContentLoaded', function () {

    const menuButton = document.getElementById('mobileMenuToggle');
    const sidebar = document.querySelector('.sidebar');
    const overlay = document.getElementById('mobileOverlay');

    if (!menuButton || !sidebar || !overlay) {
        return;
    }

    menuButton.addEventListener('click', function () {

        sidebar.classList.toggle('mobile-open');
        overlay.classList.toggle('active');

    });


    overlay.addEventListener('click', function () {

        sidebar.classList.remove('mobile-open');
        overlay.classList.remove('active');

    });

});

</script>


@stack('scripts')

</body>

</html>