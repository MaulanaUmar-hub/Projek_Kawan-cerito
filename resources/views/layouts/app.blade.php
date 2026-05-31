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

    <style>
        :root {
            --kc-sidebar: #ffffff;
            --kc-bg: #f5f5f9;
            --kc-text: #566a7f;
            --kc-heading: #2b2c40;
            --kc-muted: #a1acb8;
            --kc-border: #eceef1;
            --kc-primary: #696cff;
            --kc-primary-soft: #e7e7ff;
            --kc-warning: #ffab00;
            --kc-warning-soft: #fff2d6;
            --kc-success: #71dd37;
            --kc-success-soft: #e8fadf;
            --kc-info: #03c3ec;
            --kc-info-soft: #d7f5fc;
        }

        body {
            background: var(--kc-bg);
            color: var(--kc-text);
        }

        .dashboard-shell {
            min-height: 100vh;
            display: flex;
        }

        .dashboard-sidebar {
            width: 260px;
            flex: 0 0 260px;
            min-height: 100vh;
            background: var(--kc-sidebar);
            border-right: 1px solid var(--kc-border);
            position: sticky;
            top: 0;
            align-self: flex-start;
            z-index: 20;
        }

        .dashboard-brand {
            height: 76px;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0 24px;
            color: var(--kc-heading);
            font-weight: 800;
            letter-spacing: 0;
        }

        .brand-mark {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            display: inline-grid;
            place-items: center;
            background: var(--kc-primary);
            color: #fff;
            font-weight: 800;
        }

        .dashboard-menu {
            padding: 8px 14px 24px;
        }

        .menu-item,
        .menu-logout {
            width: 100%;
            min-height: 44px;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            border-radius: 8px;
            color: var(--kc-text);
            font-size: 15px;
            font-weight: 500;
            transition: background .2s ease, color .2s ease, box-shadow .2s ease;
        }

        .menu-item:hover,
        .menu-logout:hover,
        .menu-item.active {
            background: var(--kc-primary-soft);
            color: var(--kc-primary);
        }

        .menu-item.active {
            box-shadow: 0 2px 8px rgba(105, 108, 255, .18);
        }

        .menu-icon {
            width: 22px;
            display: inline-flex;
            justify-content: center;
            font-size: 18px;
            line-height: 1;
        }

        .dashboard-main {
            min-width: 0;
            flex: 1;
            padding: 24px;
        }

        .dashboard-navbar {
            min-height: 64px;
            background: rgba(255, 255, 255, .92);
            border: 1px solid var(--kc-border);
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(67, 89, 113, .08);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 12px 20px;
            margin-bottom: 24px;
        }

        .navbar-title {
            color: var(--kc-heading);
            font-size: 20px;
            font-weight: 700;
            margin: 0;
        }

        .navbar-subtitle {
            color: var(--kc-muted);
            font-size: 13px;
            margin: 2px 0 0;
        }

        .user-chip {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 0;
        }

        .user-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: var(--kc-primary-soft);
            color: var(--kc-primary);
            display: grid;
            place-items: center;
            font-weight: 800;
        }

        .dashboard-content {
            max-width: 1440px;
            margin: 0 auto;
        }

        .kc-card {
            background: #fff;
            border: 1px solid var(--kc-border);
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(67, 89, 113, .08);
            transition: border-color .2s ease, box-shadow .2s ease, background .2s ease;
        }

        .text-kc-heading {
            color: var(--kc-heading);
        }

        [id] {
            scroll-margin-top: 28px;
        }

        .dashboard-section-active {
            border-color: rgba(105, 108, 255, .45);
            box-shadow: 0 0 0 4px rgba(105, 108, 255, .08), 0 8px 24px rgba(67, 89, 113, .12);
            background: linear-gradient(0deg, rgba(105, 108, 255, .025), rgba(105, 108, 255, .025)), #fff;
        }

        @media (max-width: 991px) {
            .dashboard-shell {
                display: block;
            }

            .dashboard-sidebar {
                position: relative;
                width: 100%;
                min-height: auto;
                border-right: 0;
                border-bottom: 1px solid var(--kc-border);
            }

            .dashboard-brand {
                height: 64px;
            }

            .dashboard-menu {
                display: flex;
                gap: 8px;
                overflow-x: auto;
                padding: 0 16px 16px;
            }

            .menu-item,
            .menu-logout {
                flex: 0 0 auto;
                width: auto;
                white-space: nowrap;
            }

            .dashboard-main {
                padding: 16px;
            }
        }

        @media (max-width: 640px) {
            .dashboard-navbar {
                align-items: flex-start;
                flex-direction: column;
            }
        }
    </style>
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
                    <a href="{{ $href }}" class="menu-item {{ $active ? 'active' : '' }}" data-menu-link="{{ $href }}">
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

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const links = Array.from(document.querySelectorAll('[data-menu-link]'));
            const dashboardLink = links.find((link) => !(link.getAttribute('href') || '').includes('#'));
            const sectionLinks = links
                .map((link) => {
                    const href = link.getAttribute('href') || '';
                    const hash = href.includes('#') ? '#' + href.split('#')[1] : '';
                    const section = hash ? document.querySelector(hash) : null;
                    const highlightTarget = section
                        ? (section.classList.contains('kc-card') ? section : section.closest('.kc-card') || section)
                        : null;

                    return { link, hash, section, highlightTarget };
                })
                .filter((item) => item.section);

            const activate = (activeLink) => {
                links.forEach((link) => link.classList.toggle('active', link === activeLink));

                sectionLinks.forEach((item) => {
                    if (item.highlightTarget) {
                        item.highlightTarget.classList.toggle('dashboard-section-active', item.link === activeLink);
                    }
                });
            };

            const setActiveByScroll = () => {
                const offset = 140;
                let activeItem = null;

                sectionLinks.forEach((item) => {
                    const rect = item.section.getBoundingClientRect();

                    if (rect.top <= offset) {
                        activeItem = item;
                    }
                });

                if (!activeItem) {
                    activeItem = sectionLinks
                        .map((item) => ({
                            ...item,
                            distance: Math.abs(item.section.getBoundingClientRect().top - offset),
                        }))
                        .sort((first, second) => first.distance - second.distance)[0];
                }

                activate(activeItem?.link || dashboardLink);
            };

            links.forEach((link) => {
                link.addEventListener('click', () => {
                    window.setTimeout(setActiveByScroll, 120);
                });
            });

            window.addEventListener('hashchange', setActiveByScroll);
            window.addEventListener('scroll', setActiveByScroll, { passive: true });
            window.addEventListener('resize', setActiveByScroll);
            setActiveByScroll();
        });
    </script>

</body>
</html>
