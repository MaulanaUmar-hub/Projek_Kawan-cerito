<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Kawan Cerito' }}</title>
    <link rel="icon" type="image/png" sizes="64x64" href="{{ asset('assets/brand/kawan-cerito-favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('assets/brand/kawan-cerito-logo.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">


    <x-vite-assets :entries="[
        'resources/css/app.css',
        'resources/js/app.js'
    ]" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        @media (min-width: 992px) {
            .dashboard-shell.sidebar-collapsed .dashboard-sidebar:hover,
            .dashboard-shell.sidebar-collapsed .dashboard-sidebar:focus-within {
                width: 260px;
                flex-basis: 260px;
            }

            .dashboard-shell.sidebar-collapsed .dashboard-sidebar:hover .dashboard-brand-row,
            .dashboard-shell.sidebar-collapsed .dashboard-sidebar:focus-within .dashboard-brand-row {
                padding: 0 24px;
                justify-content: flex-start;
            }

            .dashboard-shell.sidebar-collapsed .dashboard-sidebar:hover .dashboard-brand,
            .dashboard-shell.sidebar-collapsed .dashboard-sidebar:focus-within .dashboard-brand {
                flex: 1;
            }

            .dashboard-shell.sidebar-collapsed .dashboard-sidebar:hover .brand-text,
            .dashboard-shell.sidebar-collapsed .dashboard-sidebar:focus-within .brand-text,
            .dashboard-shell.sidebar-collapsed .dashboard-sidebar:hover .menu-label,
            .dashboard-shell.sidebar-collapsed .dashboard-sidebar:focus-within .menu-label {
                width: auto;
                opacity: 1;
                overflow: visible;
                pointer-events: auto;
            }

            .dashboard-shell.sidebar-collapsed .dashboard-sidebar:hover .menu-item,
            .dashboard-shell.sidebar-collapsed .dashboard-sidebar:focus-within .menu-item,
            .dashboard-shell.sidebar-collapsed .dashboard-sidebar:hover .menu-logout,
            .dashboard-shell.sidebar-collapsed .dashboard-sidebar:focus-within .menu-logout {
                justify-content: flex-start;
                gap: 12px;
            }
        }
    </style>
</head>

<body>
    @php
    $user = auth()->user();
    $previewRole = trim((string) ($dashboardRole ?? ''));
    $role = $user->role ?? ($previewRole ?: 'konseli');
    $name = $user->nama ?? $user->name ?? 'Preview ' . ucfirst($role);
    $initial = strtoupper(mb_substr($name, 0, 1));
    $photoPath = null;

    if ($user && $role === 'konseli') {
    $photoPath = $user->konseli?->foto;
    }

    if ($user && $role === 'konselor') {
    $photoPath = $user->konselor?->foto;
    }

    $photoUrl = $photoPath && \Illuminate\Support\Facades\Storage::disk('public')->exists($photoPath)
    ? \Illuminate\Support\Facades\Storage::disk('public')->url($photoPath)
    : null;

    $menus = [
    'konseli' => [
    ['label' => 'Dashboard', 'icon' => 'bi bi-bar-chart-line-fill', 'route' => 'konseli.dashboard'],
    ['label' => 'Assessment Awal', 'icon' => 'bi bi-clipboard-check-fill', 'route' => 'konseli.assessment'],
    ['label' => 'Ajukan Konseling', 'icon' => 'bi bi-chat-dots-fill', 'route' => 'konseli.pengajuan'],
    ['label' => 'Jadwal Konseling', 'icon' => 'bi bi-calendar-event-fill', 'route' => 'konseli.jadwal'],
    ['label' => 'Riwayat Konseling', 'icon' => 'bi bi-clock-history', 'route' => 'konseli.riwayat'],
    ['label' => 'Profil', 'icon' => 'bi bi-person-circle', 'route' => 'konseli.profil'],
    ],
    'konselor' => [
    ['label' => 'Dashboard', 'icon' => 'bi bi-bar-chart-line-fill', 'route' => 'konselor.dashboard'],
    ['label' => 'Pengajuan Konseling', 'icon' => 'bi bi-envelope-paper-fill', 'route' => 'konselor.pengajuan'],
    ['label' => 'Jadwal Konseling', 'icon' => 'bi bi-calendar-event-fill', 'route' => 'konselor.jadwal'],
    ['label' => 'Riwayat Konseling', 'icon' => 'bi bi-clock-history', 'route' => 'konselor.riwayat'],
    ['label' => 'Profil', 'icon' => 'bi bi-person-circle', 'route' => 'konselor.profil'],
    ],
    'admin' => [
    ['label' => 'Dashboard', 'icon' => 'bi bi-bar-chart-line-fill', 'route' => 'admin.dashboard'],
    ['label' => 'Approval Konselor', 'icon' => 'bi bi-patch-check-fill', 'route' => 'admin.approval-konselor.index'],
    ['label' => 'Kelola Pengguna', 'icon' => 'bi bi-people-fill', 'route' => 'admin.users.index'],
    ['label' => 'Kelola Konselor', 'icon' => 'bi bi-person-workspace', 'route' => 'admin.konselor.index'],
    ['label' => 'Activity Log', 'icon' => 'bi bi-clock-history', 'route' => 'admin.activity-log.index'],
    ['label' => 'Profil', 'icon' => 'bi bi-person-circle', 'route' => 'profile.edit'],
    ],
    ];

    $activeMenus = $menus[$role] ?? $menus['konseli'];
    $brandHref = collect($activeMenus)
    ->first(fn ($menu) => isset($menu['route']) && Route::has($menu['route']))['route'] ?? 'dashboard';
    $profileRoute = collect($activeMenus)
    ->first(fn ($menu) => ($menu['label'] ?? '') === 'Profil' && isset($menu['route']) && Route::has($menu['route']))['route'] ?? null;
    $dashboardMenu = collect($activeMenus)->first(fn ($menu) => ($menu['label'] ?? '') === 'Dashboard');
    $currentMenu = collect($activeMenus)->first(function ($menu) {
    return isset($menu['route'])
    && Route::has($menu['route'])
    && request()->routeIs($menu['route'], $menu['route'] . '.*');
    }) ?? $dashboardMenu;
    @endphp

    <div class="dashboard-shell sidebar-collapsed">
        <aside class="dashboard-sidebar">
            <div class="dashboard-brand-row">
                <a href="{{ route($brandHref) }}" class="dashboard-brand">
                    <img
                        src="{{ asset('assets/brand/kawan-cerito-logo.png') }}"
                        alt="Logo Kawan Cerito"
                        class="brand-logo"
                        style="width: 38px; height: 38px; border-radius: 10px; object-fit: cover; box-shadow: 0 4px 14px rgba(105, 108, 255, 0.16);">
                    <span class="brand-text">Kawan Cerito</span>
                </a>
            </div>

            <nav class="dashboard-menu" aria-label="Menu {{ ucfirst($role) }}">
                @foreach ($activeMenus as $menu)
                @php
                $hasRoute = isset($menu['route']) && Route::has($menu['route']);
                $href = $hasRoute ? route($menu['route']) : ($menu['url'] ?? '#');
                $active = $hasRoute && request()->routeIs($menu['route'], $menu['route'] . '.*');
                @endphp
                <a href="{{ $href }}" class="menu-item {{ $active ? 'active' : '' }}" title="{{ $menu['label'] }}">
                    <span class="menu-icon"> <i class="{{ $menu['icon'] }}"></i> </span>
                    <span class="menu-label">{{ $menu['label'] }}</span>
                </a>
                @endforeach

                <form id="logout-form" method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="button" class="menu-logout" onclick="confirmLogout()">
                        <span class="menu-icon"> <i class="bi bi-box-arrow-right"></i></span>
                        <span class="menu-label">Logout</span>
                    </button>
                </form>
            </nav>
        </aside>

        <main class="dashboard-main">
            <div class="dashboard-content">
                @if ($dashboardMenu)
                <nav class="dashboard-breadcrumb" aria-label="Breadcrumb">
                    <a href="{{ route($dashboardMenu['route']) }}">Dashboard</a>
                    @if (($currentMenu['label'] ?? 'Dashboard') !== 'Dashboard')
                    <span>/</span>
                    <span>{{ $currentMenu['label'] }}</span>
                    @endif
                </nav>
                @endif

                <header class="dashboard-navbar">
                    <div>
                        <h1 class="navbar-title">
                            {{ $headerTitle ?? ($header ?? 'Dashboard') }}
                        </h1>
                        <p class="navbar-subtitle">
                            {{ ucfirst($role) }} Area Kawan Cerito
                        </p>
                    </div>

                    <div class="user-menu" data-user-menu>
                        <button type="button" class="user-chip" data-user-menu-toggle aria-expanded="false">
                            <div class="text-right">
                                <div class="text-sm font-semibold text-kc-heading">{{ $name }}</div>
                                <div class="text-xs text-slate-400">{{ ucfirst($role) }}</div>
                            </div>
                            <div class="user-avatar" style="position: relative; overflow: hidden;">
                                <span class="user-avatar-fallback" style="position: relative; z-index: 1;">{{ $initial }}</span>
                                @if ($photoUrl)
                                <img
                                    src="{{ $photoUrl }}"
                                    alt="Foto profil {{ $name }}"
                                    class="user-avatar-image"
                                    style="position: absolute; inset: 0; z-index: 2; width: 100%; height: 100%; object-fit: cover;"
                                    onerror="this.style.display='none'">
                                @endif
                            </div>
                        </button>

                        <div class="user-dropdown" data-user-menu-dropdown>
                            <div class="user-dropdown-header">
                                <div class="user-avatar user-avatar-sm" style="position: relative; overflow: hidden;">
                                    <span class="user-avatar-fallback" style="position: relative; z-index: 1;">{{ $initial }}</span>
                                    @if ($photoUrl)
                                    <img
                                        src="{{ $photoUrl }}"
                                        alt="Foto profil {{ $name }}"
                                        class="user-avatar-image"
                                        style="position: absolute; inset: 0; z-index: 2; width: 100%; height: 100%; object-fit: cover;"
                                        onerror="this.style.display='none'">
                                    @endif
                                </div>
                                <div>
                                    <p class="user-dropdown-name">{{ $name }}</p>
                                    <p class="user-dropdown-role">{{ ucfirst($role) }}</p>
                                </div>
                            </div>

                            @if ($profileRoute)
                            <a href="{{ route($profileRoute) }}" class="user-dropdown-link">
                                <i class="bi bi-person-circle"></i>
                                <span>Profil Saya</span>
                            </a>
                            @endif

                            <form method="POST" action="{{ route('logout') }}" data-confirm-logout>
                                @csrf
                                <button type="submit" class="user-dropdown-link user-dropdown-logout">
                                    <i class="bi bi-box-arrow-right"></i>
                                    <span>Logout</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </header>

                {{ $slot }}
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const userMenu = document.querySelector('[data-user-menu]');
            const userMenuToggle = document.querySelector('[data-user-menu-toggle]');

            if (userMenu && userMenuToggle) {
                userMenuToggle.addEventListener('click', (event) => {
                    event.stopPropagation();
                    const isOpen = userMenu.classList.toggle('is-open');
                    userMenuToggle.setAttribute('aria-expanded', String(isOpen));
                });

                document.addEventListener('click', (event) => {
                    if (!userMenu.contains(event.target)) {
                        userMenu.classList.remove('is-open');
                        userMenuToggle.setAttribute('aria-expanded', 'false');
                    }
                });

                document.addEventListener('keydown', (event) => {
                    if (event.key === 'Escape') {
                        userMenu.classList.remove('is-open');
                        userMenuToggle.setAttribute('aria-expanded', 'false');
                    }
                });
            }

            document.querySelectorAll('[data-confirm-logout]').forEach((form) => {
                form.addEventListener('submit', (event) => {
                    event.preventDefault();
                    showLogoutConfirmation(() => form.submit());
                });
            });
        });
    </script>
    <script>
        function showLogoutConfirmation(onConfirm) {
            Swal.fire({
                title: 'Konfirmasi Logout',
                text: 'Apakah Anda yakin ingin keluar dari akun?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Logout',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#696cff'
            }).then((result) => {
                if (result.isConfirmed) {
                    onConfirm();
                }
            });
        }

        function confirmLogout() {
            showLogoutConfirmation(() => document.getElementById('logout-form').submit());
        }
    </script>
    @stack('scripts')
</body>

</html>
