<x-app-layout>
    <x-slot name="dashboardRole">admin</x-slot>
    <x-slot name="headerTitle">Dashboard Admin</x-slot>

    @php
        $formatDate = fn ($value) => \Illuminate\Support\Carbon::parse($value)->translatedFormat('d M Y');

        $quickActions = [
            ['label' => 'Kelola Pengguna', 'description' => 'Lihat, cari, dan ubah data pengguna.', 'href' => route('admin.users.index')],
            ['label' => 'Kelola Konselor', 'description' => 'Pantau data konselor aktif.', 'href' => route('admin.konselor.index')],
            ['label' => 'Kelola Jadwal', 'description' => 'Cek jadwal konseling mendatang.', 'href' => route('admin.jadwal.index')],
            ['label' => 'Lihat Activity Log', 'description' => 'Pantau aktivitas penting sistem.', 'href' => route('admin.activity-log.index')],
        ];
    @endphp

    <section class="space-y-6">
        <x-dashboard.page-header
            title="Dashboard Admin"
            subtitle="Kelola pengajuan konselor, pengguna, dan aktivitas sistem Kawan Cerito."
        />

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ($stats as $stat)
                <x-dashboard.stat-card :label="$stat['label']" :value="$stat['value']" :icon="$stat['icon']" :color="$stat['color']" />
            @endforeach
        </div>

        <article id="approval-konselor" class="kc-card">
            <div class="flex flex-wrap items-center justify-between gap-4 border-b border-slate-100 px-6 py-5">
                <div>
                    <h2 class="text-lg font-semibold text-kc-heading">Pengajuan Konselor Pending</h2>
                    <p class="mt-1 text-sm text-slate-500">Tinjau calon konselor sebelum memberi akses dashboard konselor.</p>
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
                            <th class="px-6 py-4 font-semibold">Asal</th>
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
                                        <a href="{{ route('admin.konselor.show') }}" class="rounded-md border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-600 transition hover:bg-slate-50">
                                            Detail
                                        </a>
                                        <form method="POST" action="{{ route('admin.konselor.approve') }}">
                                            @csrf
                                            <button type="submit" class="rounded-md bg-emerald-500 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-emerald-600">
                                                Setujui
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.konselor.reject') }}">
                                            @csrf
                                            <button type="submit" class="rounded-md bg-red-500 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-red-600">
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

        <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
            <article id="kelola-konselor" class="kc-card xl:col-span-2">
                <div class="border-b border-slate-100 px-6 py-5">
                    <h2 class="text-lg font-semibold text-kc-heading">Konselor Aktif</h2>
                    <p class="mt-1 text-sm text-slate-500">Konselor yang sudah disetujui dan dapat menangani sesi.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[720px] text-left text-sm">
                        <thead class="text-xs uppercase tracking-wide text-slate-400">
                            <tr>
                                <th class="px-6 py-4 font-semibold">Nama Konselor</th>
                                <th class="px-6 py-4 font-semibold">Email</th>
                                <th class="px-6 py-4 font-semibold">Spesialisasi</th>
                                <th class="px-6 py-4 font-semibold">Status</th>
                                <th class="px-6 py-4 font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($konselorAktif as $item)
                                <tr>
                                    <td class="px-6 py-4 font-semibold text-kc-heading">{{ $item['nama'] }}</td>
                                    <td class="px-6 py-4 text-slate-500">{{ $item['email'] }}</td>
                                    <td class="px-6 py-4 text-slate-500">{{ $item['spesialisasi'] }}</td>
                                    <td class="px-6 py-4"><x-dashboard.status-badge :status="$item['status']" /></td>
                                    <td class="px-6 py-4">
                                        <a href="{{ route('admin.konselor.show') }}" class="rounded-md border border-indigo-200 px-3 py-1.5 text-xs font-semibold text-indigo-600 transition hover:bg-indigo-50">
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
                <div class="mt-5 grid gap-3">
                    @foreach ($quickActions as $action)
                        <a href="{{ $action['href'] }}" class="rounded-lg border border-slate-100 p-4 transition hover:border-indigo-100 hover:bg-indigo-50 hover:shadow-[0_6px_18px_rgba(105,108,255,0.16)]">
                            <p class="font-semibold text-kc-heading">{{ $action['label'] }}</p>
                            <p class="mt-1 text-sm leading-6 text-slate-500">{{ $action['description'] }}</p>
                        </a>
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
                                    <a href="{{ route('admin.konselor.show') }}" class="rounded-md border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-600 transition hover:bg-slate-50">
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
{{-- peler --}}