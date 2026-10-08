<header class="topbar">

    {{-- Bouton menu mobile --}}
    <button
        type="button"
        class="mobile-menu-toggle"
        id="mobileMenuToggle"
        aria-label="Ouvrir le menu"
    >
        <i class="bi bi-list"></i>
    </button>


    <div class="topbar-title">

        <h1 class="page-title">
            Tableau de bord
        </h1>

        <div class="page-subtitle">
            Supervision des accès et présences
        </div>

    </div>


    <div class="topbar-actions">

        {{-- Date --}}

        <div class="d-none d-md-block text-end me-2">

            <div class="fw-semibold"
                 style="font-size:11px;">

                {{ now()->format('d/m/Y') }}

            </div>

            <div class="text-muted"
                 style="font-size:9px;">

                {{ now()->locale('fr')->translatedFormat('l') }}

            </div>

        </div>


        {{-- Notification --}}

        <button
            type="button"
            class="notification btn p-0"
        >

            <i class="bi bi-bell"></i>

            <span class="notification-badge">
                3
            </span>

        </button>


        {{-- Profile --}}

        <div class="admin-profile">

            <div class="admin-avatar">
                PA
            </div>

            <div>

                <div class="admin-name">
                    PENDA ANDERSON
                </div>

                <div class="admin-role">
                    Administrateur
                </div>

            </div>

        </div>

    </div>

</header>