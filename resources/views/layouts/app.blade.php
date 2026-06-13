<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Kawan Cerito' }}</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/brand/kawan-cerito-favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('assets/brand/kawan-cerito-logo.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet"href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">


    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])
</head>
<body>
    @php
        $previewRole = trim((string) ($dashboardRole ?? ''));
        $role = auth()->user()->role ?? ($previewRole ?: 'konseli');
        $name = auth()->user()->nama ?? auth()->user()->name ?? 'Preview ' . ucfirst($role);
        $initial = strtoupper(mb_substr($name, 0, 1));

        $menus = [
            'konseli' => [
                ['label' => 'Dashboard', 'icon' => 'bi bi-bar-chart-line-fill', 'route' => 'konseli.dashboard'],
                ['label' => 'Assessment Awal', 'icon' => 'bi bi-clipboard-check-fill', 'route' => 'konseli.assessment'],
                ['label' => 'Ajukan Konseling', 'icon' => 'bi bi-chat-dots-fill', 'route' => 'konseli.pengajuan'],
                ['label' => 'Riwayat Konseling', 'icon' => 'bi bi-clock-history', 'route' => 'konseli.riwayat'],
                ['label' => 'Jadwal Konseling', 'icon' => 'bi bi-calendar-event-fill', 'route' => 'konseli.jadwal'],
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
        $dashboardMenu = collect($activeMenus)->first(fn ($menu) => ($menu['label'] ?? '') === 'Dashboard');
        $currentMenu = collect($activeMenus)->first(function ($menu) {
            return isset($menu['route'])
                && Route::has($menu['route'])
                && request()->routeIs($menu['route'], $menu['route'] . '.*');
        }) ?? $dashboardMenu;
    @endphp

    <div class="dashboard-shell">
        <aside class="dashboard-sidebar">
            <a href="{{ route($brandHref) }}" class="dashboard-brand">
                <img
                    src="{{ asset('assets/brand/kawan-cerito-logo.png') }}"
                    alt="Logo Kawan Cerito"
                    class="brand-logo"
                    style="width: 38px; height: 38px; border-radius: 10px; object-fit: cover; box-shadow: 0 4px 14px rgba(105, 108, 255, 0.16);"
                >
                <span>Kawan Cerito</span>
            </a>

            <nav class="dashboard-menu" aria-label="Menu {{ ucfirst($role) }}">
                @foreach ($activeMenus as $menu)
                    @php
                        $hasRoute = isset($menu['route']) && Route::has($menu['route']);
                        $href = $hasRoute ? route($menu['route']) : ($menu['url'] ?? '#');
                        $active = $hasRoute && request()->routeIs($menu['route'], $menu['route'] . '.*');
                    @endphp
                    <a href="{{ $href }}" class="menu-item {{ $active ? 'active' : '' }}">
                        <span class="menu-icon"> <i class="{{ $menu['icon'] }}"></i> </span>
                        <span>{{ $menu['label'] }}</span>
                    </a>
                @endforeach

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="menu-logout">
                        <span class="menu-icon"> <i class="bi bi-box-arrow-right"></i></span>
                        <span>Logout</span>
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

                    <div class="user-chip">
                        <div class="text-right">
                            <div class="text-sm font-semibold text-kc-heading">{{ $name }}</div>
                            <div class="text-xs text-slate-400">{{ ucfirst($role) }}</div>
                        </div>
                        <div class="user-avatar">{{ $initial }}</div>
                    </div>
                </header>

                {{ $slot }}
            </div>
        </main>
    </div>

</body>
</html>
