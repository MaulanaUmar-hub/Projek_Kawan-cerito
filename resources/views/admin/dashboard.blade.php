<x-app-layout>
    <x-slot name="dashboardRole">admin</x-slot>
    <x-slot name="headerTitle">Dashboard Admin</x-slot>

    @php
        $formatDate = fn($value) => \Illuminate\Support\Carbon::parse($value)->translatedFormat('d M Y');

        $quickActions = [
            [
                'label'       => 'Kelola Pengguna',
                'description' => 'Lihat, cari, dan ubah data pengguna.',
                'href'        => route('admin.users.index'),
                'icon'        => 'bi bi-people',
                'color'       => 'indigo',
            ],
            [
                'label'       => 'Kelola Konselor',
                'description' => 'Pantau data konselor aktif.',
                'href'        => route('admin.konselor.index'),
                'icon'        => 'bi bi-person-badge',
                'color'       => 'purple',
            ],
            [
                'label'       => 'Lihat Activity Log',
                'description' => 'Pantau aktivitas penting sistem.',
                'href'        => route('admin.activity-log.index'),
                'icon'        => 'bi bi-clock-history',
                'color'       => 'sky',
            ],
        ];
    @endphp

    <section class="space-y-6">

        {{-- ── HEADER ─────────────────────────────────────────────────────────── --}}
        <header class="pt-2">
            <h1 class="text-3xl font-black tracking-tight text-slate-900">
                Dashboard Admin
            </h1>
            <p class="mt-1 text-sm text-slate-500">
                Kelola pengajuan konselor, pengguna, dan aktivitas sistem Kawan Cerito.
            </p>
        </header>

        {{-- ── FLASH MESSAGE ────────────────────────────────────────────────────── --}}
        @if (session('success'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-700">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            </div>
        @endif

        {{-- ── STAT CARDS ───────────────────────────────────────────────────────── --}}
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ($stats as $stat)
                <x-dashboard.stat-card
                    :label="$stat['label']"
                    :value="$stat['value']"
                    :icon="$stat['icon']"
                    :color="$stat['color']"
                />
            @endforeach
        </div>

        {{-- ── RINGKASAN KONSELING BERDASARKAN PERIODE ─────────────────────────── --}}
        <article class="kc-card p-5">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <p class="text-sm font-semibold text-indigo-600">Monitoring Konseling</p>
                    <h2 class="mt-1 text-xl font-bold text-kc-heading">Konseling dalam Periode Tertentu</h2>
                    <p class="mt-1 text-sm text-slate-500">
                        Pantau berapa banyak konseli dan sesi konseling pada rentang tanggal yang dipilih.
                    </p>
                </div>

                <form method="GET" action="{{ route('admin.dashboard') }}" class="grid gap-3 sm:grid-cols-[1fr_1fr_auto]">
                    <div>
                        <label for="mulai" class="mb-1 block text-xs font-semibold text-slate-500">Mulai</label>
                        <input type="date" id="mulai" name="mulai"
                            value="{{ request('mulai', $konselingPeriode['mulai']->toDateString()) }}"
                            class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm outline-none transition focus:border-indigo-400 focus:ring-4 focus:ring-indigo-100">
                    </div>
                    <div>
                        <label for="selesai" class="mb-1 block text-xs font-semibold text-slate-500">Selesai</label>
                        <input type="date" id="selesai" name="selesai"
                            value="{{ request('selesai', $konselingPeriode['akhir']->toDateString()) }}"
                            class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm outline-none transition focus:border-indigo-400 focus:ring-4 focus:ring-indigo-100">
                    </div>
                    <button type="submit"
                        class="self-end rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-indigo-700">
                        Terapkan
                    </button>
                </form>
            </div>

            <div class="mt-5 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                @foreach ([
                    ['label' => 'Orang Konseling', 'value' => $konselingPeriode['konseli'], 'icon' => 'bi bi-person-hearts', 'color' => 'bg-indigo-50 text-indigo-600'],
                    ['label' => 'Total Sesi', 'value' => $konselingPeriode['total'], 'icon' => 'bi bi-chat-dots-fill', 'color' => 'bg-sky-50 text-sky-600'],
                    ['label' => 'Sedang Berjalan', 'value' => $konselingPeriode['berlangsung'], 'icon' => 'bi bi-play-circle-fill', 'color' => 'bg-emerald-50 text-emerald-600'],
                    ['label' => 'Selesai', 'value' => $konselingPeriode['sesi_selesai'], 'icon' => 'bi bi-check-circle-fill', 'color' => 'bg-cyan-50 text-cyan-600'],
                ] as $item)
                    <div class="rounded-xl border border-slate-100 bg-slate-50/60 p-4">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="text-xs font-semibold text-slate-500">{{ $item['label'] }}</p>
                                <p class="mt-2 text-2xl font-bold text-kc-heading">{{ $item['value'] }}</p>
                            </div>
                            <span class="grid h-10 w-10 place-items-center rounded-lg {{ $item['color'] }}">
                                <i class="{{ $item['icon'] }}"></i>
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </article>

        {{-- ── ALUR AKSI CEPAT (gaya 3-langkah konseli) ───────────────────────── --}}
        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">

            {{-- Kelola Pengguna (green) --}}
            <a href="{{ route('admin.users.index') }}"
                class="group relative flex flex-col justify-between bg-[#E6F5EA] rounded-2xl p-5 border border-green-200/80 transition-all duration-300 hover:-translate-y-0.5 hover:shadow-md hover:bg-[#D4EEDC]">
                <div>
                    <div class="flex items-center justify-between">
                        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-white text-green-700 shadow-sm">
                            <i class="bi bi-people text-lg"></i>
                        </span>
                        <span class="text-[11px] font-bold tracking-wider text-green-800 bg-green-200 px-2.5 py-0.5 rounded-full">Pengguna</span>
                    </div>
                    <h2 class="mt-4 text-sm font-bold text-slate-900 group-hover:text-green-800">Kelola Pengguna</h2>
                    <p class="mt-1 text-[11px] text-slate-600 leading-relaxed">Lihat, cari, dan ubah data akun pengguna terdaftar.</p>
                </div>
                <div class="mt-4 flex items-center justify-between border-t border-green-300/30 pt-3">
                    <span class="text-[11px] font-bold text-green-700">Buka Daftar</span>
                    <i class="bi bi-arrow-right text-xs text-green-700 transition-transform group-hover:translate-x-1"></i>
                </div>
            </a>

            {{-- Kelola Konselor (purple) --}}
            <a href="{{ route('admin.konselor.index') }}"
                class="group relative flex flex-col justify-between bg-[#F3E8FF] rounded-2xl p-5 border border-purple-200/80 transition-all duration-300 hover:-translate-y-0.5 hover:shadow-md hover:bg-[#E9D5FF]">
                <div>
                    <div class="flex items-center justify-between">
                        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-white text-purple-700 shadow-sm">
                            <i class="bi bi-person-badge text-lg"></i>
                        </span>
                        <span class="text-[11px] font-bold tracking-wider text-purple-800 bg-purple-200 px-2.5 py-0.5 rounded-full">Konselor</span>
                    </div>
                    <h2 class="mt-4 text-sm font-bold text-slate-900 group-hover:text-purple-800">Kelola Konselor</h2>
                    <p class="mt-1 text-[11px] text-slate-600 leading-relaxed">Pantau, setujui, atau tolak pengajuan konselor baru.</p>
                </div>
                <div class="mt-4 flex items-center justify-between border-t border-purple-300/30 pt-3">
                    <span class="text-[11px] font-bold text-purple-700">Buka Daftar</span>
                    <i class="bi bi-arrow-right text-xs text-purple-700 transition-transform group-hover:translate-x-1"></i>
                </div>
            </a>

            {{-- Activity Log (blue) --}}
            <a href="{{ route('admin.activity-log.index') }}"
                class="group relative flex flex-col justify-between bg-[#E0F2FE] rounded-2xl p-5 border border-sky-200/80 transition-all duration-300 hover:-translate-y-0.5 hover:shadow-md hover:bg-[#BAE6FD]">
                <div>
                    <div class="flex items-center justify-between">
                        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-white text-sky-700 shadow-sm">
                            <i class="bi bi-clock-history text-lg"></i>
                        </span>
                        <span class="text-[11px] font-bold tracking-wider text-sky-800 bg-sky-200 px-2.5 py-0.5 rounded-full">Log</span>
                    </div>
                    <h2 class="mt-4 text-sm font-bold text-slate-900 group-hover:text-sky-800">Activity Log</h2>
                    <p class="mt-1 text-[11px] text-slate-600 leading-relaxed">Pantau seluruh aktivitas penting dari pengguna dan konselor.</p>
                </div>
                <div class="mt-4 flex items-center justify-between border-t border-sky-300/40 pt-3">
                    <span class="text-[11px] font-bold text-sky-700">Lihat Log</span>
                    <i class="bi bi-arrow-right text-xs text-sky-700 transition-transform group-hover:translate-x-1"></i>
                </div>
            </a>

        </div>

        {{-- ── LAYOUT DUA KOLOM ─────────────────────────────────────────────────── --}}
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

            {{-- ── KOLOM KIRI (tabel utama) ────────────────────────────────────── --}}
            <div class="lg:col-span-2 space-y-5">

                {{-- Pengajuan Konselor Menunggu --}}
                <article class="bg-white rounded-2xl border border-slate-200 shadow-[0_2px_8px_rgba(0,0,0,0.01)] overflow-hidden">
                    <div class="px-5 py-4 border-b border-slate-100 flex flex-wrap items-center justify-between gap-3">
                        <div>
                            <h2 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                               
                                Pengajuan Konselor Menunggu
                            </h2>
                            <p class="mt-0.5 text-xs text-slate-500">Tinjau calon konselor sebelum memberi akses dashboard.</p>
                        </div>
                        <span class="rounded-full bg-amber-50 px-3 py-1 text-[11px] font-bold text-amber-700 border border-amber-200">
                            {{ $konselorPending->count() }} menunggu tinjauan
                        </span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[860px] text-left text-xs">
                            <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider">
                                <tr>
                                    <th class="px-5 py-3 font-semibold">Nama</th>
                                    <th class="px-5 py-3 font-semibold">Email</th>
                                    <th class="px-5 py-3 font-semibold">Spesialisasi</th>
                                    <th class="px-5 py-3 font-semibold">No HP</th>
                                    <th class="px-5 py-3 font-semibold">Tanggal</th>
                                    <th class="px-5 py-3 font-semibold">Status</th>
                                    <th class="px-5 py-3 font-semibold">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse ($konselorPending as $item)
                                    <tr class="hover:bg-slate-50/40 transition-colors">
                                        <td class="px-5 py-3 font-semibold text-slate-900">{{ $item['nama'] }}</td>
                                        <td class="px-5 py-3 text-slate-600">{{ $item['email'] }}</td>
                                        <td class="px-5 py-3 text-slate-600">{{ $item['asal'] }}</td>
                                        <td class="px-5 py-3 text-slate-600">{{ $item['no_hp'] }}</td>
                                        <td class="px-5 py-3 text-slate-600">{{ $formatDate($item['tanggal']) }}</td>
                                        <td class="px-5 py-3"><x-dashboard.status-badge :status="$item['status']" /></td>
                                        <td class="px-5 py-3">
                                            <div class="flex flex-wrap gap-2">
                                                <a href="{{ route('admin.konselor.show', $item['id']) }}"
                                                    class="inline-flex items-center gap-1 rounded-lg border border-slate-200 px-2.5 py-1 text-[11px] font-semibold text-slate-600 transition hover:bg-slate-50">
                                                    <i class="bi bi-eye"></i> Detail
                                                </a>
                                                <form method="POST" action="{{ route('admin.konselor.approve', $item['id']) }}">
                                                    @csrf
                                                    <button type="submit"
                                                        class="inline-flex items-center gap-1 rounded-lg bg-emerald-500 px-2.5 py-1 text-[11px] font-semibold text-white transition hover:bg-emerald-600">
                                                        <i class="bi bi-check-lg"></i> Setujui
                                                    </button>
                                                </form>
                                                <form method="POST" action="{{ route('admin.konselor.reject', $item['id']) }}">
                                                    @csrf
                                                    <button type="submit"
                                                        class="inline-flex items-center gap-1 rounded-lg bg-red-500 px-2.5 py-1 text-[11px] font-semibold text-white transition hover:bg-red-600">
                                                        <i class="bi bi-x-lg"></i> Tolak
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-5 py-10 text-center text-xs text-slate-400">
                                            <i class="bi bi-inbox text-2xl block mb-2 text-slate-300"></i>
                                            Tidak ada pengajuan konselor yang sedang menunggu.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </article>

                {{-- Konselor Aktif --}}
                <article class="bg-white rounded-2xl border border-slate-200 shadow-[0_2px_8px_rgba(0,0,0,0.01)] overflow-hidden">
                    <div class="flex flex-col gap-3 border-b border-slate-100 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h2 class="flex items-center gap-2 text-sm font-bold text-slate-900">
                                <span class="grid h-8 w-8 place-items-center rounded-xl bg-emerald-50 text-emerald-500">
                                    <i class="bi bi-patch-check-fill"></i>
                                </span>
                                Konselor Aktif
                            </h2>
                            <p class="mt-1 text-xs text-slate-500">Konselor yang sudah disetujui dan dapat menangani sesi.</p>
                        </div>
                        <span class="inline-flex w-fit items-center rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-600">
                            {{ $konselorAktif->count() }} aktif
                        </span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[860px] text-left text-sm">
                            <colgroup>
                                <col class="w-[28%]">
                                <col class="w-[30%]">
                                <col class="w-[24%]">
                                <col class="w-[10%]">
                                <col class="w-[8%]">
                            </colgroup>
                            <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                                <tr>
                                    <th class="px-5 py-3 font-semibold">Nama Konselor</th>
                                    <th class="px-5 py-3 font-semibold">Email</th>
                                    <th class="px-5 py-3 font-semibold">Spesialisasi</th>
                                    <th class="px-5 py-3 font-semibold">Status</th>
                                    <th class="px-5 py-3 font-semibold">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse ($konselorAktif as $item)
                                    <tr class="hover:bg-slate-50/40 transition-colors">
                                        <td class="px-5 py-4">
                                            <div class="flex min-w-0 items-center gap-3">
                                                <div class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-indigo-50 text-sm font-bold text-indigo-600">
                                                    {{ strtoupper(mb_substr($item['nama'], 0, 1)) }}
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="truncate font-semibold text-slate-900">{{ $item['nama'] }}</p>
                                                    <p class="mt-0.5 text-xs text-slate-400">Konselor terverifikasi</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-5 py-4 text-slate-600">{{ $item['email'] }}</td>
                                        <td class="px-5 py-4 text-slate-600">{{ $item['spesialisasi'] }}</td>
                                        <td class="px-5 py-4"><x-dashboard.status-badge :status="$item['status']" /></td>
                                        <td class="px-5 py-4 text-right">
                                            <a href="{{ route('admin.konselor.show', $item['id']) }}"
                                                class="inline-flex items-center justify-center gap-1.5 whitespace-nowrap rounded-lg border border-indigo-200 px-3 py-2 text-xs font-semibold text-indigo-600 transition hover:bg-indigo-50">
                                                <i class="bi bi-eye"></i> Lihat Detail
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-5 py-8 text-center text-sm text-slate-400">
                                            Belum ada konselor aktif.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </article>

                {{-- Konselor Ditolak --}}
                <article class="bg-white rounded-2xl border border-slate-200 shadow-[0_2px_8px_rgba(0,0,0,0.01)] overflow-hidden">
                    <div class="px-5 py-4 border-b border-slate-100">
                        <h2 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                            <i class="bi bi-x-circle text-red-400"></i>
                            Konselor Ditolak
                        </h2>
                        <p class="mt-0.5 text-xs text-slate-500">Riwayat pengajuan konselor yang belum memenuhi kriteria.</p>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[600px] text-left text-xs">
                            <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider">
                                <tr>
                                    <th class="px-5 py-3 font-semibold">Nama</th>
                                    <th class="px-5 py-3 font-semibold">Email</th>
                                    <th class="px-5 py-3 font-semibold">Tanggal Pengajuan</th>
                                    <th class="px-5 py-3 font-semibold">Status</th>
                                    <th class="px-5 py-3 font-semibold">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach ($konselorDitolak as $item)
                                    <tr class="hover:bg-slate-50/40 transition-colors">
                                        <td class="px-5 py-3 font-semibold text-slate-900">{{ $item['nama'] }}</td>
                                        <td class="px-5 py-3 text-slate-600">{{ $item['email'] }}</td>
                                        <td class="px-5 py-3 text-slate-600">{{ $formatDate($item['tanggal']) }}</td>
                                        <td class="px-5 py-3"><x-dashboard.status-badge :status="$item['status']" /></td>
                                        <td class="px-5 py-3">
                                            <a href="{{ route('admin.konselor.show', $item['id']) }}"
                                                class="inline-flex items-center gap-1 whitespace-nowrap rounded-lg border border-slate-200 px-2.5 py-1 text-[11px] font-semibold text-slate-600 transition hover:bg-slate-50">
                                                <i class="bi bi-eye"></i> Lihat Detail
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </article>

            </div>

            {{-- ── KOLOM KANAN (side panel) ─────────────────────────────────────── --}}
            <div class="space-y-4">

                {{-- Ringkasan Pengajuan (amber — selaras jadwal konseli) --}}
                <article class="bg-[#FFF3D6] rounded-2xl p-4 border border-amber-200">
                    <div class="flex items-center justify-between">
                        <p class="text-[11px] font-bold text-amber-800 uppercase tracking-wider">Pengajuan Masuk</p>
                        <i class="bi bi-hourglass-split text-amber-700"></i>
                    </div>
                    <div class="mt-2">
                        <h3 class="text-2xl font-black text-slate-900">{{ $konselorPending->count() }}</h3>
                        <p class="text-xs text-slate-700 mt-0.5">pengajuan konselor menunggu tindakan.</p>
                    </div>
                    <div class="mt-3 pt-2 border-t border-amber-300/40 flex justify-between items-center">
                        <span class="text-[10px] text-amber-800 font-medium">Perlu ditinjau segera</span>
                        <a href="#approval-konselor"
                            class="text-[10px] font-bold text-amber-800 hover:underline">Tinjau →</a>
                    </div>
                </article>

                {{-- Ringkasan Status Konselor --}}
                <article class="bg-white rounded-2xl border border-slate-200 p-4">
                    <h2 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Ringkasan Konselor</h2>
                    <div class="space-y-2.5">
                        @foreach ([
                            ['Menunggu',  'pending',  '#d97706'],
                            ['Aktif',     'aktif',    '#16a34a'],
                            ['Ditolak',   'ditolak',  '#dc2626'],
                        ] as [$label, $key, $color])
                            @php
                                $value = match($key) {
                                    'pending' => $konselorPending->count(),
                                    'aktif'   => $konselorAktif->count(),
                                    'ditolak' => $konselorDitolak->count(),
                                    default   => 0,
                                };
                                $total = max(1, $konselorPending->count() + $konselorAktif->count() + $konselorDitolak->count());
                                $width = round(($value / $total) * 100);
                            @endphp
                            <div class="flex items-center gap-3">
                                <span class="text-[11px] text-slate-600 w-16 text-left whitespace-nowrap font-medium">{{ $label }}</span>
                                <div class="h-1.5 flex-1 rounded-full bg-slate-100 overflow-hidden">
                                    <div class="h-1.5 rounded-full transition-all duration-500"
                                        style="width: {{ $width }}%; background: {{ $color }};"></div>
                                </div>
                                <span class="text-[11px] font-bold text-slate-900 w-4 text-right">{{ $value }}</span>
                            </div>
                        @endforeach
                    </div>
                </article>

                {{-- Aktivitas Sistem Terbaru --}}
                <article class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
                    <div class="px-4 py-3 border-b border-slate-100 flex items-center justify-between">
                        <h2 class="text-xs font-bold uppercase tracking-wider text-slate-400">
                            Aktivitas Terbaru
                        </h2>
                        <a href="{{ route('admin.activity-log.index') }}"
                            class="text-[10px] font-bold text-[#5B67F1] hover:text-indigo-800 transition-colors">
                            Lihat Semua →
                        </a>
                    </div>
                    <div class="divide-y divide-slate-100">
                        @foreach ($activityLogs->take(4) as $log)
                            <div class="flex gap-3 px-4 py-3">
                                <span class="mt-1.5 h-2 w-2 flex-none rounded-full bg-indigo-500"></span>
                                <div>
                                    <p class="text-xs font-semibold text-slate-800">{{ $log['aktivitas'] }}</p>
                                    <p class="mt-0.5 text-[10px] text-slate-400">
                                        {{ $log['user'] }} · {{ ucfirst($log['role']) }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </article>

                {{-- Log Aktivitas Mini --}}
                <div class="px-2">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Aksi Cepat</h3>
                    <div class="space-y-2">
                        @foreach ($quickActions as $action)
                            <a href="{{ $action['href'] }}"
                                class="flex items-center gap-3 rounded-xl border border-slate-100 px-4 py-3 transition hover:border-indigo-100 hover:bg-indigo-50">
                                <span class="flex h-8 w-8 flex-none items-center justify-center rounded-lg bg-slate-100 text-slate-600 text-sm">
                                    <i class="{{ $action['icon'] }}"></i>
                                </span>
                                <div>
                                    <p class="text-xs font-semibold text-slate-800">{{ $action['label'] }}</p>
                                    <p class="text-[10px] text-slate-400 leading-snug">{{ $action['description'] }}</p>
                                </div>
                                <i class="bi bi-chevron-right ml-auto text-[10px] text-slate-400"></i>
                            </a>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
    </section>
</x-app-layout>
