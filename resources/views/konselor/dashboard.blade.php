<x-app-layout>
    <x-slot name="dashboardRole">konselor</x-slot>
    <x-slot name="headerTitle">Dashboard Konselor</x-slot>

    @php
        $formatDate = fn($value) => \Illuminate\Support\Carbon::parse($value)->translatedFormat('d M Y');
        $formatTime = fn($value) => \Illuminate\Support\Carbon::parse($value)->format('H:i');
        $totalProduktifitas = max(1, collect($produktifitas)->sum('value'));
    @endphp

    <section class="space-y-6">
        @if (session('success'))
            <div class="kc-card border-emerald-100 bg-emerald-50 p-4 text-sm font-medium text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="kc-card overflow-hidden">
            <div class="grid gap-6 p-6 lg:grid-cols-[1fr_320px] lg:p-8">
                <div>
                    <p class="text-sm font-semibold text-indigo-500">Workspace Konselor</p>
                    <h2 class="mt-3 text-2xl font-bold text-kc-heading md:text-3xl">
                        Selamat Datang, {{ $name }}
                    </h2>
                    <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-500">
                        Kelola jadwal, pengajuan, dan konseling Anda melalui dashboard ini.
                    </p>
                </div>
                <div class="rounded-lg border border-indigo-100 bg-indigo-50 p-5">
                    <p class="text-sm text-indigo-500">Prioritas hari ini</p>
                    <p class="mt-2 text-3xl font-bold text-kc-heading">{{ $stats[2]['value'] }}</p>
                    <p class="mt-1 text-sm text-slate-500">Konseling hari ini perlu dipantau.</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ($stats as $stat)
                <x-dashboard.stat-card :label="$stat['label']" :value="$stat['value']" :icon="$stat['icon']" :color="$stat['color']" />
            @endforeach
        </div>

        <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
            {{-- Pengajuan terbaru --}}
            <article id="pengajuan-konseling" class="kc-card xl:col-span-2">
                <div class="flex items-center justify-between border-b border-slate-100 px-6 py-5">
                    <h2 class="text-lg font-semibold text-kc-heading">Pengajuan Konseling Terbaru</h2>
                    <a href="{{ route('konselor.pengajuan') }}"
                        class="text-xs font-semibold text-indigo-500 hover:underline">Lihat Semua →</a>
                </div>

                <div class="divide-y divide-slate-100">
                    @forelse ($pengajuanTerbaru as $p)
                        <div class="p-5">
                            <div class="flex flex-wrap items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <p class="font-semibold text-kc-heading">{{ $p->konseli?->user?->nama ?? '-' }}
                                        </p>
                                        <x-dashboard.status-badge :status="$p->status_pengajuan" />
                                    </div>
                                    <p class="mt-1 text-xs text-slate-400">
                                        {{ $formatDate($p->created_at) }}
                                    </p>
                                    @if ($p->assessment?->keluhan)
                                        <p class="mt-2 text-xs leading-5 text-slate-500">
                                            {{ \Illuminate\Support\Str::limit($p->assessment->keluhan, 100) }}
                                        </p>
                                    @endif
                                </div>

                                {{-- Aksi berdasarkan status --}}
                                <div class="flex shrink-0 flex-wrap gap-2">
                                    @if ($p->status_pengajuan === 'menunggu')
                                        {{-- Setujui --}}
                                        <form method="POST"
                                            action="{{ route('konselor.pengajuan.setujui', $p->id_pengajuan) }}">
                                            @csrf
                                            <button type="submit"
                                                class="rounded-md bg-emerald-500 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-emerald-600"
                                                onclick="return confirm('Setujui pengajuan dari {{ $p->konseli?->user?->nama ?? 'konseli ini' }}?')">
                                                ✓ Setujui
                                            </button>
                                        </form>
                                        {{-- Tolak (redirect ke halaman pengajuan untuk isi alasan) --}}
                                        <a href="{{ route('konselor.pengajuan') }}#p-{{ $p->id_pengajuan }}"
                                            class="rounded-md bg-red-500 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-red-600">
                                            ✕ Tolak
                                        </a>
                                    @endif

                                    {{-- Detail / Lihat pengajuan — selalu tampil --}}
                                    <a href="{{ route('konselor.pengajuan') }}"
                                        class="rounded-md border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-600 transition hover:bg-slate-50">
                                        Detail →
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="px-6 py-10 text-center text-sm text-slate-400">Belum ada pengajuan masuk.</p>
                    @endforelse
                </div>
            </article>

            {{-- Jadwal hari ini --}}
            <article id="jadwal-konseling" class="kc-card p-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-kc-heading">Jadwal Hari Ini</h2>
                    <a href="{{ route('konselor.jadwal') }}"
                        class="text-xs font-semibold text-indigo-500 hover:underline">Semua →</a>
                </div>
                <div class="mt-5 space-y-4">
                    @forelse ($jadwalHariIni as $jadwal)
                        <div class="flex items-center justify-between gap-4 rounded-lg border border-slate-100 p-4">
                            <div>
                                <p class="font-semibold text-kc-heading">{{ $jadwal['nama'] }}</p>
                                <p class="mt-1 text-sm text-slate-400">{{ $jadwal['jam'] }}</p>
                            </div>
                            <x-dashboard.status-badge :status="$jadwal['status']" />
                        </div>
                    @empty
                        <p class="text-center text-sm text-slate-400">Tidak ada jadwal hari ini.</p>
                    @endforelse
                </div>
            </article>
        </div>

        <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
            <article class="kc-card p-6">
                <h2 class="text-lg font-semibold text-kc-heading">Aktivitas Konseling</h2>
                <div class="mt-5 space-y-5">
                    @forelse ($aktivitas as $item)
                        <div class="flex gap-3">
                            <span class="mt-1 h-2.5 w-2.5 flex-none rounded-full bg-indigo-500"></span>
                            <div>
                                <p class="text-sm font-semibold text-kc-heading">{{ $item['aktivitas'] }}</p>
                                <p class="mt-1 text-xs text-slate-400">
                                    {{ $formatDate($item['waktu']) }} - {{ $formatTime($item['waktu']) }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-slate-400">Belum ada aktivitas.</p>
                    @endforelse
                </div>
            </article>

            <article class="kc-card p-6 xl:col-span-2">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <h2 class="text-lg font-semibold text-kc-heading">Ringkasan Produktivitas</h2>
                    <span class="rounded-md bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-600">Bulan
                        ini</span>
                </div>
                <div class="mt-6 grid gap-5 md:grid-cols-3">
                    @foreach ($produktifitas as $item)
                        @php $width = round(($item['value'] / $totalProduktifitas) * 100); @endphp
                        <div>
                            <div class="flex items-end justify-between gap-3">
                                <div>
                                    <p class="text-sm text-slate-400">{{ $item['label'] }}</p>
                                    <p class="mt-1 text-2xl font-bold text-kc-heading">{{ $item['value'] }}</p>
                                </div>
                                <span class="text-xs font-semibold text-slate-400">{{ $width }}%</span>
                            </div>
                            <div class="mt-4 h-3 rounded-full bg-slate-100">
                                <div class="h-3 rounded-full"
                                    style="width: {{ $width }}%; background: {{ $item['color'] }};"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </article>
        </div>

        {{-- Riwayat konseling --}}
        <article id="riwayat-konseling" class="kc-card">
            <div class="border-b border-slate-100 px-6 py-5">
                <h2 class="text-lg font-semibold text-kc-heading">Riwayat Konseling</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[760px] text-left text-sm">
                    <thead class="text-xs uppercase tracking-wide text-slate-400">
                        <tr>
                            <th class="px-6 py-4 font-semibold">Tanggal</th>
                            <th class="px-6 py-4 font-semibold">Nama Konseli</th>
                            <th class="px-6 py-4 font-semibold">Status</th>
                            <th class="px-6 py-4 font-semibold">Hasil Konseling</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($riwayatKonseling as $item)
                            <tr>
                                <td class="px-6 py-4">{{ $formatDate($item['tanggal']) }}</td>
                                <td class="px-6 py-4 font-semibold text-kc-heading">{{ $item['nama'] }}</td>
                                <td class="px-6 py-4"><x-dashboard.status-badge :status="$item['status']" /></td>
                                <td class="px-6 py-4 text-slate-500">
                                    {{ $item['hasil'] !== '-' ? \Illuminate\Support\Str::limit($item['hasil'], 80) : '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-10 text-center text-slate-400">Belum ada riwayat
                                    konseling.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </article>
    </section>
</x-app-layout>
