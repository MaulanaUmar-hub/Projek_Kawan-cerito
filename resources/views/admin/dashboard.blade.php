<x-app-layout>
    <x-slot name="dashboardRole">admin</x-slot>
    <x-slot name="headerTitle">Dashboard Admin</x-slot>

    @php
        $formatDate = fn($value) => \Illuminate\Support\Carbon::parse($value)->translatedFormat('d M Y');

        $quickActions = [
            [
                'label' => 'Kelola Pengguna',
                'description' => 'Lihat, cari, dan ubah data pengguna.',
                'href' => route('admin.users.index'),
            ],
            [
                'label' => 'Kelola Konselor',
                'description' => 'Pantau data konselor aktif.',
                'href' => route('admin.konselor.index'),
            ],
            [
                'label' => 'Kelola Jadwal',
                'description' => 'Cek jadwal konseling mendatang.',
                'href' => route('admin.jadwal.index'),
            ],
            [
                'label' => 'Lihat Activity Log',
                'description' => 'Pantau aktivitas penting sistem.',
                'href' => route('admin.activity-log.index'),
            ],
        ];
    @endphp

    <section class="space-y-6">
        <x-dashboard.page-header title="Dashboard Admin"
            subtitle="Kelola pengajuan konselor, pengguna, dan aktivitas sistem Kawan Cerito." />

        {{-- Flash message --}}
        @if (session('success'))
            <div
                class="rounded-2xl border border-emerald-100 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-600">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ($stats as $stat)
                <x-dashboard.stat-card :label="$stat['label']" :value="$stat['value']" :icon="$stat['icon']" :color="$stat['color']" />
            @endforeach
        </div>

        <article id="approval-konselor" class="kc-card">
            <div class="flex flex-wrap items-center justify-between gap-4 border-b border-slate-100 px-6 py-5">
                <div>
                    <h2 class="text-lg font-semibold text-kc-heading">Pengajuan Konselor Pending</h2>
                    <p class="mt-1 text-sm text-slate-500">Tinjau calon konselor sebelum memberi akses dashboard
                        konselor.</p>
                </div>
                <span class="rounded-md bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-700">
                    {{ $konselorPending->count() }} menunggu review
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[980px] text-left text-sm">
                    <thead class="text-xs uppercase tracking-wide text-slate-400">
                        <tr>
                            <th class="px-6 py-4 font-semibold">Nama</th>
                            <th class="px-6 py-4 font-semibold">Email</th>
                            <th class="px-6 py-4 font-semibold">Spesialisasi</th>
                            <th class="px-6 py-4 font-semibold">No HP</th>
                            <th class="px-6 py-4 font-semibold">Tanggal Pengajuan</th>
                            <th class="px-6 py-4 font-semibold">Status</th>
                            <th class="px-6 py-4 font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($konselorPending as $item)
                            <tr>
                                <td class="px-6 py-4 font-semibold text-kc-heading">{{ $item['nama'] }}</td>
                                <td class="px-6 py-4 text-slate-500">{{ $item['email'] }}</td>
                                <td class="px-6 py-4 text-slate-500">{{ $item['asal'] }}</td>
                                <td class="px-6 py-4 text-slate-500">{{ $item['no_hp'] }}</td>
                                <td class="px-6 py-4 text-slate-500">{{ $formatDate($item['tanggal']) }}</td>
                                <td class="px-6 py-4"><x-dashboard.status-badge :status="$item['status']" /></td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-2">
                                        <a href="{{ route('admin.konselor.show', $item['id']) }}"
                                            class="rounded-md border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-600 transition hover:bg-slate-50">
                                            Detail
                                        </a>
                                        <form method="POST"
                                            action="{{ route('admin.konselor.approve', $item['id']) }}">
                                            @csrf
                                            <button type="submit"
                                                class="rounded-md bg-emerald-500 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-emerald-600">
                                                Setujui
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.konselor.reject', $item['id']) }}">
                                            @csrf
                                            <button type="submit"
                                                class="rounded-md bg-red-500 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-red-600">
                                                Tolak
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-10 text-center text-sm text-slate-500">
                                    Tidak ada pengajuan konselor yang sedang menunggu.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </article>

        <article id="kelola-konselor" class="kc-card">
            <div class="border-b border-slate-100 px-6 py-5">
                <h2 class="text-lg font-semibold text-kc-heading">Konselor Aktif</h2>
                <p class="mt-1 text-sm text-slate-500">Konselor yang sudah disetujui dan dapat menangani sesi.</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[860px] table-fixed text-left text-sm">
                    <colgroup>
                        <col class="w-[22%]">
                        <col class="w-[30%]">
                        <col class="w-[24%]">
                        <col class="w-[12%]">
                        <col class="w-[12%]">
                    </colgroup>
                    <thead class="text-xs uppercase tracking-wide text-slate-400">
                        <tr>
                            <th class="px-5 py-4 font-semibold">Nama Konselor</th>
                            <th class="px-5 py-4 font-semibold">Email</th>
                            <th class="px-5 py-4 font-semibold">Spesialisasi</th>
                            <th class="px-5 py-4 font-semibold">Status</th>
                            <th class="px-5 py-4 font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($konselorAktif as $item)
                            <tr>
                                <td class="px-5 py-4 font-semibold leading-6 text-kc-heading">{{ $item['nama'] }}</td>
                                <td class="px-5 py-4 leading-6 text-slate-500">{{ $item['email'] }}</td>
                                <td class="px-5 py-4 leading-6 text-slate-500">{{ $item['spesialisasi'] }}</td>
                                <td class="px-5 py-4"><x-dashboard.status-badge :status="$item['status']" /></td>
                                <td class="px-5 py-4">
                                    <a href="{{ route('admin.konselor.show', $item['id']) }}"
                                        class="inline-flex whitespace-nowrap rounded-md border border-indigo-200 px-3 py-1.5 text-xs font-semibold text-indigo-600 transition hover:bg-indigo-50">
                                        Lihat Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </article>

        <article class="kc-card p-6">
            <h2 class="text-lg font-semibold text-kc-heading">Quick Actions Admin</h2>
            <div class="mt-5 grid gap-3 md:grid-cols-2 xl:grid-cols-4">
                @foreach ($quickActions as $action)
                    <a href="{{ $action['href'] }}"
                        class="rounded-lg border border-slate-100 p-4 transition hover:border-indigo-100 hover:bg-indigo-50 hover:shadow-[0_6px_18px_rgba(105,108,255,0.16)]">
                        <p class="font-semibold text-kc-heading">{{ $action['label'] }}</p>
                        <p class="mt-1 text-sm leading-6 text-slate-500">{{ $action['description'] }}</p>
                    </a>
                @endforeach
            </div>
        </article>

        <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">
            <article class="kc-card">
                <div class="flex items-center justify-between gap-4 border-b border-slate-100 px-6 py-5">
                    <h2 class="text-lg font-semibold text-kc-heading">Aktivitas Sistem Terbaru</h2>
                    <a href="{{ route('admin.activity-log.index') }}"
                        class="text-xs font-semibold text-indigo-600">Lihat Semua</a>
                </div>
                <div class="divide-y divide-slate-100">
                    @foreach ($activityLogs->take(4) as $log)
                        <div class="flex gap-4 px-6 py-4">
                            <span class="mt-2 h-2.5 w-2.5 flex-none rounded-full bg-indigo-500"></span>
                            <div>
                                <p class="text-sm font-semibold text-kc-heading">{{ $log['aktivitas'] }}</p>
                                <p class="mt-1 text-xs text-slate-400">{{ $log['user'] }} -
                                    {{ ucfirst($log['role']) }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </article>

            <article class="kc-card">
                <div class="flex items-center justify-between gap-4 border-b border-slate-100 px-6 py-5">
                    <h2 class="text-lg font-semibold text-kc-heading">Jadwal Mendatang</h2>
                    <a href="{{ route('admin.jadwal.index') }}" class="text-xs font-semibold text-indigo-600">Kelola
                        Jadwal</a>
                </div>
                <div class="divide-y divide-slate-100">
                    @foreach ($jadwal->take(3) as $item)
                        <div class="flex flex-wrap items-center justify-between gap-4 px-6 py-4">
                            <div>
                                <p class="text-sm font-semibold text-kc-heading">{{ $item['konseli'] }}</p>
                                <p class="mt-1 text-xs text-slate-400">{{ $formatDate($item['tanggal']) }} -
                                    {{ $item['jam'] }} bersama {{ $item['konselor'] }}</p>
                            </div>
                            <x-dashboard.status-badge :status="$item['status']" />
                        </div>
                    @endforeach
                </div>
            </article>
        </div>

        <article class="kc-card">
            <div class="border-b border-slate-100 px-6 py-5">
                <h2 class="text-lg font-semibold text-kc-heading">Konselor Ditolak</h2>
                <p class="mt-1 text-sm text-slate-500">Riwayat pengajuan konselor yang belum memenuhi kriteria.</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[720px] text-left text-sm">
                    <thead class="text-xs uppercase tracking-wide text-slate-400">
                        <tr>
                            <th class="px-6 py-4 font-semibold">Nama</th>
                            <th class="px-6 py-4 font-semibold">Email</th>
                            <th class="px-6 py-4 font-semibold">Tanggal Pengajuan</th>
                            <th class="px-6 py-4 font-semibold">Status</th>
                            <th class="px-6 py-4 font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($konselorDitolak as $item)
                            <tr>
                                <td class="px-6 py-4 font-semibold text-kc-heading">{{ $item['nama'] }}</td>
                                <td class="px-6 py-4 text-slate-500">{{ $item['email'] }}</td>
                                <td class="px-6 py-4 text-slate-500">{{ $formatDate($item['tanggal']) }}</td>
                                <td class="px-6 py-4"><x-dashboard.status-badge :status="$item['status']" /></td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('admin.konselor.show', $item['id']) }}"
                                        class="inline-flex whitespace-nowrap rounded-md border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-600 transition hover:bg-slate-50">
                                        Lihat Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </article>
    </section>
</x-app-layout>
