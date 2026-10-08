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

@php
    $notificationCount = \App\Models\FraudLog::whereDate(
        'heure_fraude',
        today()
    )->count();

    $notifications = \App\Models\FraudLog::whereDate(
        'heure_fraude',
        today()
    )
    ->latest('heure_fraude')
    ->take(5)
    ->get();
@endphp

<div class="dropdown">

    <button
        type="button"
        class="notification btn p-0 position-relative"
        data-bs-toggle="dropdown"
        aria-expanded="false"
        title="Alertes de sécurité"
    >

        <i class="bi bi-bell"></i>

        @if($notificationCount > 0)
            <span class="notification-badge">
                {{ $notificationCount }}
            </span>
        @endif

    </button>


    <div class="dropdown-menu dropdown-menu-end notification-menu">

        {{-- En-tête --}}
        <div class="notification-header">

            <div>

                <div class="fw-semibold">
                    Alertes de sécurité
                </div>

                <div class="text-muted"
                     style="font-size:10px;">
                    Alertes enregistrées aujourd'hui
                </div>

            </div>

            @if($notificationCount > 0)

                <span class="badge bg-danger">
                    {{ $notificationCount }}
                </span>

            @endif

        </div>


        {{-- Liste des alertes --}}
        <div class="notification-list">

            @forelse($notifications as $notification)

                <div class="notification-item">

                    <div class="notification-icon">
                        <i class="bi bi-shield-exclamation"></i>
                    </div>


                    <div class="notification-content">

                        <div class="notification-title">

                            @switch($notification->type)

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


                        <div class="notification-description">
                            {{ $notification->description }}
                        </div>


                        <div class="notification-time">
                            {{ $notification->heure_fraude->format('d/m/Y · H:i') }}
                        </div>

                    </div>

                </div>

            @empty

                <div class="notification-empty">

                    <i class="bi bi-shield-check"></i>

                    <div>

                        <div class="fw-semibold">
                            Aucune alerte
                        </div>

                        <div class="text-muted">
                            Aucun événement de sécurité aujourd'hui.
                        </div>

                    </div>

                </div>

            @endforelse

        </div>


        {{-- Pied du panneau --}}
        <div class="notification-footer">

            <a href="{{ route('frauds.index') }}">
                Voir tout le journal
                <i class="bi bi-arrow-right"></i>
            </a>

        </div>

    </div>

</div>


{{-- Profile --}}

@auth

    @php
        $admin = auth()->user();

        $adminName = trim(
            ($admin->prenom ?? '') . ' ' . ($admin->nom ?? '')
        );

        if ($adminName === '') {
            $adminName = $admin->name ?? 'Administrateur';
        }

        $initials = '';

        if (!empty($admin->prenom)) {
            $initials .= strtoupper(substr($admin->prenom, 0, 1));
        }

        if (!empty($admin->nom)) {
            $initials .= strtoupper(substr($admin->nom, 0, 1));
        }

        if ($initials === '') {
            $initials = 'AD';
        }
    @endphp

    <div class="admin-profile">

        <div class="admin-avatar">
            {{ $initials }}
        </div>

        <div>

            <div class="admin-name">
                {{ $adminName }}
            </div>

            <div class="admin-role">
                {{ $admin->role ?? 'Administrateur' }}
            </div>

        </div>

    </div>

@endauth

    </div>

</header>