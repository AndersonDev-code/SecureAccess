<aside class="sidebar">

    <div class="brand">


        <img
            src="{{ asset('image/logo.svg') }}"
            alt="SecureAccess"
            style="width: 185px; height: auto;"
        />
        

        <!-- <div class="brand-icon">
            <i class="bi bi-shield-lock-fill"></i>
        </div>

        <div>

            <div class="brand-name">
                SecureAccess
            </div>

            <span class="brand-subtitle">
                BIOMETRIC SECURITY
            </span>

        </div> -->

    </div>


    <div class="sidebar-menu">

        <div class="menu-title">
            Principal
        </div>

        <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">

            <i class="bi bi-grid-1x2-fill"></i>

            <span>
                Tableau de bord
            </span>

        </a>


        <a href="{{ route('employees.index') }}" class="sidebar-link {{ request()->routeIs('employees.index') ? 'active' : '' }}">

            <i class="bi bi-people-fill"></i>

            <span>
                Employés
            </span>

        </a>


        <a href="{{ route('presences.index') }}" class="sidebar-link {{ request()->routeIs('presences.index') ? 'active' : '' }}">

            <i class="bi bi-calendar2-check-fill"></i>

            <span>
                Présences
            </span>

        </a>


        <a href="{{ route('rapports.index') }}"  class="sidebar-link {{ request()->routeIs('rapports.index') ? 'active' : '' }}">

            <i class="bi bi-bar-chart-fill"></i>

            <span>
                Rapports
            </span>

        </a>


        <div class="menu-title mt-4">
            Sécurité
        </div>


        <a href="{{ route('frauds.index') }}" class="sidebar-link {{ request()->routeIs('frauds.index') ? 'active' : '' }}">

            <i class="bi bi-shield-exclamation"></i>

            <span>
                Journal des fraudes
            </span>

        </a>


        <div class="menu-title mt-4">
            Informations personnelles
        </div>


        <a href="{{ route('settings.index') }}" class="sidebar-link {{ request()->routeIs('settings.index') ? 'active' : '' }}">

            <i class="bi bi-gear-fill"></i>

            <span style="text-decoration: none">
                Paramètres
            </span>

        </a>

    </div>


    <div class="sidebar-footer">

        <form action="{{ route('logout') }}" method="post">
             @csrf

            <button type="submit"
                class="sidebar-link  logout-link w-100 border-0"
                style="background: transparent; cursor: pointer;">

                <i class="bi bi-box-arrow-right"></i>

                <span>
                    Déconnexion
                </span>

            </button>
        </form>

    </div>

</aside>