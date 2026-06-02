<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Kawan Cerito' }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

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
                ['label' => 'Dashboard', 'icon' => 'D', 'route' => 'konseli.dashboard'],
                ['label' => 'Ajukan Konseling', 'icon' => '+', 'url' => '#ajukan-konseling'],
                ['label' => 'Riwayat Konseling', 'icon' => 'R', 'url' => '#riwayat-konseling'],
                ['label' => 'Profil Saya', 'icon' => 'P', 'route' => 'profile.edit'],
            ],
            'konselor' => [
                ['label' => 'Dashboard', 'icon' => 'D', 'route' => 'konselor.dashboard'],
                ['label' => 'Pengajuan Konseling', 'icon' => 'P', 'url' => '#pengajuan-konseling'],
                ['label' => 'Jadwal Konseling', 'icon' => 'J', 'url' => '#jadwal-konseling'],
                ['label' => 'Riwayat Konseling', 'icon' => 'R', 'url' => '#riwayat-konseling'],
                ['label' => 'Profil', 'icon' => 'P', 'route' => 'profile.edit'],
            ],
            'admin' => [
                ['label' => 'Dashboard', 'icon' => 'D', 'route' => 'admin.dashboard'],
                ['label' => 'Kelola Pengguna', 'icon' => 'U', 'url' => '#kelola-pengguna'],
                ['label' => 'Kelola Konselor', 'icon' => 'K', 'url' => '#kelola-konselor'],
                ['label' => 'Monitoring Sistem', 'icon' => 'M', 'url' => '#monitoring-sistem'],
                ['label' => 'Profil', 'icon' => 'P', 'route' => 'profile.edit'],
            ],
        ];

        $activeMenus = $menus[$role] ?? $menus['konseli'];
        $brandHref = collect($activeMenus)
            ->first(fn ($menu) => isset($menu['route']) && Route::has($menu['route']))['route'] ?? 'dashboard';
    @endphp

    <div class="dashboard-shell">
        <aside class="dashboard-sidebar">
            <a href="{{ route($brandHref) }}" class="dashboard-brand">
                <span class="brand-mark">KC</span>
                <span>Kawan Cerito</span>
            </a>

            <nav class="dashboard-menu" aria-label="Menu {{ ucfirst($role) }}">
                @foreach ($activeMenus as $menu)
                    @php
                        $hasRoute = isset($menu['route']) && Route::has($menu['route']);
                        $href = $hasRoute ? route($menu['route']) : ($menu['url'] ?? '#');
                        $active = $hasRoute && request()->routeIs($menu['route']);
                    @endphp
                    <a href="{{ $href }}" class="menu-item {{ $active ? 'active' : '' }}">
                        <span class="menu-icon">{{ $menu['icon'] }}</span>
                        <span>{{ $menu['label'] }}</span>
                    </a>
                @endforeach

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="menu-logout">
                        <span class="menu-icon">L</span>
                        <span>Logout</span>
                    </button>
                </form>
            </nav>
        </aside>

        <main class="dashboard-main">
            <div class="dashboard-content">
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
