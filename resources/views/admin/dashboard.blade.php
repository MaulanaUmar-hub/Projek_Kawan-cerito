<x-app-layout>

    <x-slot name="dashboardRole">admin</x-slot>
    <x-slot name="headerTitle">Dashboard Administrator</x-slot>

    @php
        $formatDate = fn ($value) => \Illuminate\Support\Carbon::parse($value)->translatedFormat('d M Y');
        $formatDateTime = fn ($value) => \Illuminate\Support\Carbon::parse($value)->translatedFormat('d M Y H:i');
        $totalPlatform = max(1, collect($statistikPlatform)->sum('value'));
    @endphp

    <section class="space-y-6">
        <div class="kc-card p-6 lg:p-8">
            <p class="text-sm font-semibold text-indigo-500">Control Center</p>
            <h2 class="mt-3 text-2xl font-bold text-kc-heading md:text-3xl">Dashboard Administrator</h2>
            <p class="mt-3 max-w-3xl text-sm leading-6 text-slate-500">
                Kelola seluruh aktivitas dan pengguna dalam sistem Kawan Cerito.
            </p>

        </div>

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ($stats as $stat)
                <x-dashboard.stat-card :label="$stat['label']" :value="$stat['value']" :icon="$stat['icon']" :color="$stat['color']" />
            @endforeach
        </div>

        <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
            <article id="monitoring-sistem" class="kc-card xl:col-span-2">
                <div class="border-b border-slate-100 px-6 py-5">
                    <h2 class="text-lg font-semibold text-kc-heading">Monitoring Aktivitas Sistem</h2>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[780px] text-left text-sm">
                        <thead class="text-xs uppercase tracking-wide text-slate-400">
                            <tr>
                                <th class="px-6 py-4 font-semibold">Waktu</th>
                                <th class="px-6 py-4 font-semibold">User</th>
                                <th class="px-6 py-4 font-semibold">Role</th>
                                <th class="px-6 py-4 font-semibold">Aktivitas</th>
                                <th class="px-6 py-4 font-semibold">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($aktivitasSistem as $item)
                                <tr>
                                    <td class="px-6 py-4">{{ $formatDateTime($item['waktu']) }}</td>
                                    <td class="px-6 py-4 font-semibold text-kc-heading">{{ $item['user'] }}</td>
                                    <td class="px-6 py-4">{{ $item['role'] }}</td>
                                    <td class="px-6 py-4">{{ $item['aktivitas'] }}</td>
                                    <td class="px-6 py-4"><x-dashboard.status-badge :status="$item['status']" /></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </article>

            <article id="kelola-pengguna" class="kc-card p-6">
                <div class="flex items-center justify-between gap-4">
                    <h2 class="text-lg font-semibold text-kc-heading">Pengguna Terbaru</h2>
                    <a href="#detail-user" class="rounded-md bg-indigo-600 px-3 py-2 text-xs font-semibold text-white">Kelola</a>
                </div>
                <div class="mt-5 space-y-4">
                    @foreach ($penggunaTerbaru as $user)
                        <div class="flex items-center justify-between gap-4 rounded-lg border border-slate-100 p-4">
                            <div>
                                <p class="font-semibold text-kc-heading">{{ $user['nama'] }}</p>
                                <p class="mt-1 text-sm text-slate-400">{{ $user['role'] }} - {{ $formatDate($user['tanggal']) }}</p>
                            </div>
                            <a href="#masuk-sebagai-pengguna" class="rounded-md border border-indigo-200 px-3 py-1.5 text-xs font-semibold text-indigo-600">Masuk Sebagai Pengguna</a>
                        </div>
                    @endforeach
                </div>
            </article>
        </div>

        <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
            <article class="kc-card p-6 xl:col-span-2">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <h2 class="text-lg font-semibold text-kc-heading">Statistik Platform</h2>
                    <span class="rounded-md bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-600">Ringkasan operasional</span>
                </div>

                <div class="mt-6 space-y-5">
                    @foreach ($statistikPlatform as $item)
                        @php $width = round(($item['value'] / $totalPlatform) * 100); @endphp
                        <div>
                            <div class="mb-2 flex items-center justify-between text-sm">
                                <span>{{ $item['label'] }}</span>
                                <span class="font-semibold text-kc-heading">{{ $item['value'] }}</span>
                            </div>
                            <div class="h-3 rounded-full bg-slate-100">
                                <div class="h-3 rounded-full" style="width: {{ $width }}%; background: {{ $item['color'] }};"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </article>

            <article id="kelola-pengajuan" class="kc-card p-6">
                <h2 class="text-lg font-semibold text-kc-heading">Pengajuan Menunggu</h2>
                <div class="mt-5 space-y-4">
                    @foreach ($pengajuanMenunggu as $item)
                        <div class="rounded-lg border border-slate-100 p-4">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="font-semibold text-kc-heading">{{ $item['nama'] }}</p>
                                    <p class="mt-1 text-sm text-slate-400">{{ $item['konselor'] }}</p>
                                </div>
                                <x-dashboard.status-badge :status="$item['status']" />
                            </div>
                            <p class="mt-3 text-xs text-slate-400">{{ $formatDate($item['tanggal']) }}</p>
                        </div>
                    @endforeach
                </div>
            </article>
        </div>

        <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
            <article id="kelola-jadwal" class="kc-card xl:col-span-2">
                <div class="border-b border-slate-100 px-6 py-5">
                    <h2 class="text-lg font-semibold text-kc-heading">Jadwal Mendatang</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[680px] text-left text-sm">
                        <thead class="text-xs uppercase tracking-wide text-slate-400">
                            <tr>
                                <th class="px-6 py-4 font-semibold">Tanggal</th>
                                <th class="px-6 py-4 font-semibold">Jam</th>
                                <th class="px-6 py-4 font-semibold">Konseli</th>
                                <th class="px-6 py-4 font-semibold">Konselor</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($jadwalMendatang as $item)
                                <tr>
                                    <td class="px-6 py-4">{{ $formatDate($item['tanggal']) }}</td>
                                    <td class="px-6 py-4">{{ $item['jam'] }}</td>
                                    <td class="px-6 py-4 font-semibold text-kc-heading">{{ $item['konseli'] }}</td>
                                    <td class="px-6 py-4">{{ $item['konselor'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </article>

            <article id="activity-log" class="kc-card p-6">
                <h2 class="text-lg font-semibold text-kc-heading">Activity Log Filter</h2>
                <div class="mt-5 grid gap-3">
                    <input type="text" class="rounded-lg border border-slate-200 px-4 py-3 text-sm" placeholder="User">
                    <select class="rounded-lg border border-slate-200 px-4 py-3 text-sm">
                        <option>Semua Role</option>
                        <option>Admin</option>
                        <option>Konselor</option>
                        <option>Konseli</option>
                    </select>
                    <input type="text" class="rounded-lg border border-slate-200 px-4 py-3 text-sm" placeholder="Aktivitas">
                    <input type="date" class="rounded-lg border border-slate-200 px-4 py-3 text-sm">
                    <button class="rounded-lg bg-indigo-600 px-4 py-3 text-sm font-semibold text-white">Terapkan Filter</button>
                </div>
            </article>
        </div>

        <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">
            <article id="kelola-konselor" class="kc-card p-6">
                <h2 class="text-lg font-semibold text-kc-heading">Kelola Konselor</h2>
                <div class="mt-5 grid gap-3 sm:grid-cols-2">
                    <a href="#tambah-konselor" class="rounded-lg bg-indigo-600 px-4 py-3 text-center text-sm font-semibold text-white">Tambah Konselor</a>
                    <a href="#statistik-konselor" class="rounded-lg border border-slate-200 px-4 py-3 text-center text-sm font-semibold text-slate-600">Lihat Statistik Konselor</a>
                    <a href="#edit-konselor" class="rounded-lg border border-slate-200 px-4 py-3 text-center text-sm font-semibold text-slate-600">Edit Konselor</a>
                    <a href="#hapus-konselor" class="rounded-lg border border-red-200 px-4 py-3 text-center text-sm font-semibold text-red-600">Hapus Konselor</a>
                </div>
            </article>

            <article class="kc-card p-6">
                <h2 class="text-lg font-semibold text-kc-heading">User Management</h2>
                <div class="mt-5 grid gap-3 sm:grid-cols-2">
                    <input type="search" class="rounded-lg border border-slate-200 px-4 py-3 text-sm sm:col-span-2" placeholder="Cari pengguna">
                    <a href="#detail-pengguna" class="rounded-lg border border-slate-200 px-4 py-3 text-center text-sm font-semibold text-slate-600">Detail Pengguna</a>
                    <a href="#edit-pengguna" class="rounded-lg border border-slate-200 px-4 py-3 text-center text-sm font-semibold text-slate-600">Edit Pengguna</a>
                    <a href="#nonaktifkan-pengguna" class="rounded-lg border border-red-200 px-4 py-3 text-center text-sm font-semibold text-red-600">Nonaktifkan</a>
                    <a href="#aktifkan-pengguna" class="rounded-lg border border-emerald-200 px-4 py-3 text-center text-sm font-semibold text-emerald-600">Aktifkan</a>
                </div>
            </article>
        </div>
    </section>
</x-app-layout>
