<x-app-layout>
    <x-slot name="dashboardRole">konselor</x-slot>
    <x-slot name="headerTitle">Dashboard Konselor</x-slot>

    @php
        $formatDate = fn($value) => \Illuminate\Support\Carbon::parse($value)->translatedFormat('d M Y');
        $formatTime = fn($value) => \Illuminate\Support\Carbon::parse($value)->format('H:i');
        $totalProduktifitas = max(1, collect($produktifitas)->sum('value'));
    @endphp

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <section class="space-y-6">
        @if (session('success'))
            <div class="rounded-2xl border border-green-200 bg-[#E6F5EA] p-4 text-xs font-semibold text-green-800 shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        <header class="pt-2">
            <p class="text-xs font-bold uppercase tracking-wider text-indigo-600">Workspace Utama</p>
            <h1 class="text-2xl font-black tracking-tight text-slate-900 mt-1">
                Selamat Datang, {{ $name }} !
            </h1>
            <p class="mt-0.5 text-xs text-slate-500">
                Berikut adalah ringkasan performa klinis dan agenda bimbingan telekonseling Anda.
            </p>
        </header>

        <div class="grid grid-cols-1 gap-5 lg:grid-cols-3">
            <div class="lg:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-4">
                <article class="bg-[#E0F2FE] rounded-[24px] p-5 border border-sky-200/70 shadow-2xs flex items-center justify-between gap-4">
                    <div class="space-y-1">
                        <p class="text-[11px] font-bold text-sky-800 uppercase tracking-wider">Total Konseli</p>
                        <p class="text-3xl font-black text-slate-900">
                            {{ $stats[0]['value'] }} <span class="text-xs font-bold text-sky-700/80">Siswa</span>
                        </p>
                    </div>
                    <div class="grid h-12 w-12 place-items-center rounded-2xl bg-white text-sky-600 shadow-sm text-lg flex-none">
                        <i class="bi bi-people-fill"></i>
                    </div>
                </article>

                <article class="bg-[#FFF3D6] rounded-[24px] p-5 border border-amber-200/70 shadow-2xs flex items-center justify-between gap-4">
                    <div class="space-y-1">
                        <p class="text-[11px] font-bold text-amber-800 uppercase tracking-wider">Pengajuan Baru</p>
                        <p class="text-3xl font-black text-slate-900">
                            {{ $stats[1]['value'] }} <span class="text-xs font-bold text-amber-700/80">Antrean</span>
                        </p>
                    </div>
                    <div class="grid h-12 w-12 place-items-center rounded-2xl bg-white text-amber-600 shadow-sm text-lg flex-none">
                        <i class="bi bi-chat-left-heart-fill"></i>
                    </div>
                </article>

                <article class="bg-[#F3E8FF] rounded-[24px] p-5 border border-purple-200/70 shadow-2xs flex items-center justify-between gap-4">
                    <div class="space-y-1">
                        <p class="text-[11px] font-bold text-purple-800 uppercase tracking-wider">Sesi Hari Ini</p>
                        <p class="text-3xl font-black text-slate-900">
                            {{ $stats[2]['value'] }} <span class="text-xs font-bold text-purple-700/80">Jadwal</span>
                        </p>
                    </div>
                    <div class="grid h-12 w-12 place-items-center rounded-2xl bg-white text-purple-700 shadow-sm text-lg flex-none">
                        <i class="bi bi-calendar2-week-fill"></i>
                    </div>
                </article>

                <article class="bg-[#E6F5EA] rounded-[24px] p-5 border border-green-200/70 shadow-2xs flex items-center justify-between gap-4">
                    <div class="space-y-1">
                        <p class="text-[11px] font-bold text-green-800 uppercase tracking-wider">Sesi Selesai</p>
                        <p class="text-3xl font-black text-slate-900">
                            {{ $stats[3]['value'] }} <span class="text-xs font-bold text-green-700/80">Kasus</span>
                        </p>
                    </div>
                    <div class="grid h-12 w-12 place-items-center rounded-2xl bg-white text-green-600 shadow-sm text-lg flex-none">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                </article>
            </div>

            <article class="bg-white rounded-[24px] border border-slate-200 p-5 shadow-xs flex flex-col justify-between min-h-[260px]">
                <div>
                    <h3 class="text-sm font-bold text-slate-900">Visualisasi Distribusi Sesi</h3>
                    <p class="text-[11px] text-slate-400 mt-0.5">Perbandingan kuantitas status pelayanan dari seluruh riwayat bimbingan.</p>
                </div>
                <div class="mt-4 flex-1 relative h-44">
                    <canvas id="riwayatBarChart"></canvas>
                </div>
            </article>
        </div>

        <div class="grid grid-cols-1 gap-5 xl:grid-cols-[1fr_380px]">
            <article id="pengajuan-konseling" class="bg-white rounded-[24px] border border-slate-200 shadow-xs p-5 space-y-4">
                <div class="flex items-center justify-between border-b border-slate-50 pb-3">
                    <div>
                        <h2 class="text-base font-bold text-slate-900">Antrean Pengajuan Masuk</h2>
                        <p class="text-[11px] text-slate-400 mt-0.5">Permohonan bimbingan dari siswa yang membutuhkan persetujuan.</p>
                    </div>
                    <a href="{{ route('konselor.pengajuan') }}" class="text-xs font-bold text-indigo-600 bg-indigo-50 px-3 py-1 rounded-xl hover:bg-indigo-100 transition">Lihat Semua</a>
                </div>

                <div class="space-y-2 @if(count($pengajuanTerbaru) > 0) max-h-[360px] overflow-y-auto pr-1 @endif">
                    @forelse ($pengajuanTerbaru as $p)
                        <div class="group rounded-2xl border border-slate-100 p-3.5 flex items-center justify-between gap-4 bg-white hover:bg-slate-50/50 transition duration-200">
                            <div class="flex items-center gap-3 min-w-0">
                                <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-purple-100 text-purple-700 font-black text-xs uppercase shadow-2xs">
                                    {{ strtoupper(mb_substr($p->konseli?->user?->nama ?? 'K', 0, 1)) }}
                                </span>
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <p class="text-sm font-bold text-slate-900 truncate">{{ $p->konseli?->user?->nama ?? '-' }}</p>
                                        <x-dashboard.status-badge :status="$p->status_pengajuan" />
                                    </div>
                                    <p class="text-[11px] text-slate-400 mt-0.5 truncate max-w-md italic">
                                        {{ $p->assessment?->keluhan ? '"' . \Illuminate\Support\Str::limit($p->assessment->keluhan, 65) . '"' : 'Tidak ada catatan keluhan tambahan.' }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center gap-2.5 shrink-0">
                                <span class="text-[10px] font-bold text-slate-400 bg-slate-100 px-2 py-1 rounded-lg hidden sm:inline-block">
                                    {{ $formatDate($p->created_at) }}
                                </span>
                                @if ($p->status_pengajuan === 'menunggu')
                                    <form method="POST" action="{{ route('konselor.pengajuan.setujui', $p->id_pengajuan) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="rounded-xl bg-green-600 px-3 py-1.5 text-xs font-bold text-white hover:bg-green-700 shadow-2xs transition" onclick="return confirm('Setujui pengajuan?')">Setujui</button>
                                    </form>
                                    <a href="{{ route('konselor.pengajuan') }}#p-{{ $p->id_pengajuan }}" class="rounded-xl bg-red-500 px-3 py-1.5 text-xs font-bold text-white hover:bg-red-600 shadow-2xs transition">Tolak</a>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-6 text-slate-400">
                            <i class="bi bi-inbox text-2xl text-slate-300"></i>
                            <p class="text-xs font-medium mt-1">Belum ada dokumen pengajuan bimbingan masuk.</p>
                        </div>
                    @endforelse
                </div>
            </article>

            <article id="jadwal-konseling" class="bg-[#FEF9EC] rounded-[24px] border border-amber-200/70 p-5 shadow-xs flex flex-col justify-between">
                <div class="w-full">
                    <div class="flex items-center justify-between border-b border-amber-200/30 pb-3">
                        <div>
                            <h2 class="text-sm font-bold text-amber-900">Agenda Hari Ini</h2>
                            <p class="text-[11px] text-amber-700/80 mt-0.5">Garis waktu sesi aktif.</p>
                        </div>
                        <a href="{{ route('konselor.jadwal') }}" class="text-xs font-bold text-amber-700 hover:text-amber-900 transition">Semua →</a>
                    </div>
                    
                    <div class="mt-4 space-y-3 @if(count($jadwalHariIni) > 0) max-h-[340px] overflow-y-auto pr-0.5 @endif relative">
                        @forelse ($jadwalHariIni as $jadwal)
                            <div class="flex items-start gap-3 relative group">
                                <div class="text-[11px] font-black text-amber-800 w-12 text-right pt-2 flex-none">
                                    {{ $jadwal['jam'] }}
                                </div>
                                <div class="flex flex-col items-center flex-none h-full pt-3">
                                    <span class="h-2.5 w-2.5 rounded-full bg-amber-500 ring-4 ring-white shadow-2xs"></span>
                                    <span class="w-0.5 h-12 bg-amber-200/50 block mt-1"></span>
                                </div>
                                <div class="flex-1 bg-white rounded-xl p-3 border border-amber-200/40 shadow-3xs">
                                    <p class="text-xs font-bold text-slate-900 truncate">{{ $jadwal['nama'] }}</p>
                                    <div class="flex items-center justify-between mt-1.5 flex-wrap gap-1">
                                        <span class="text-[10px] text-slate-400 font-medium"><i class="bi bi-clock-history"></i> Waktu Sesi</span>
                                        <x-dashboard.status-badge :status="$jadwal['status']" />
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-6">
                                <i class="bi bi-calendar-minus text-amber-400 text-2xl"></i>
                                <p class="text-xs text-amber-700/70 mt-1 font-medium">Sesi bimbingan hari ini kosong.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </article>
        </div>

        <article id="riwayat-konseling" class="bg-white rounded-[24px] border border-slate-200 shadow-xs overflow-hidden">
            <div class="border-b border-slate-100 px-5 py-4">
                <h2 class="text-sm font-bold text-slate-900">Riwayat Counseling Keseluruhan</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider">
                        <tr>
                            <th class="px-5 py-3 font-semibold">Tanggal</th>
                            <th class="px-5 py-3 font-semibold">Nama Konseli</th>
                            <th class="px-5 py-3 font-semibold">Status Sesi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($riwayatKonseling as $item)
                            <tr class="hover:bg-slate-50/40 transition-colors">
                                <td class="px-5 py-3 font-medium text-slate-900">{{ $formatDate($item['tanggal']) }}</td>
                                <td class="px-5 py-3 font-bold text-slate-800">{{ $item['nama'] }}</td>
                                <td class="px-5 py-3"><x-dashboard.status-badge :status="$item['status']" /></td>
                            </tr>
                        @endforeach
                        @if(count($riwayatKonseling) === 0)
                            <tr>
                                <td colspan="3" class="px-5 py-8 text-center text-slate-400">Belum ditemukan data riwayat arsip bimbingan.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </article>
    </section>

    @php
        $chartData = collect($riwayatKonseling)->groupBy('status')->map->count();
        $labels = ['Selesai', 'Berlangsung', 'Disetujui', 'Menunggu', 'Ditolak'];
        $counts = [];
        foreach($labels as $l) {
            $counts[] = $chartData->get(strtolower($l), 0);
        }
    @endphp

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const ctx = document.getElementById('riwayatBarChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($labels) !!},
                    datasets: [{
                        label: 'Jumlah Sesi',
                        data: {!! json_encode($counts) !!},
                        backgroundColor: [
                            'rgba(34, 197, 94, 0.15)',
                            'rgba(168, 85, 247, 0.15)',
                            'rgba(14, 165, 233, 0.15)',
                            'rgba(245, 158, 11, 0.15)',
                            'rgba(239, 68, 68, 0.15)'
                        ],
                        borderColor: [
                            '#16a34a',
                            '#a855f7',
                            '#0284c7',
                            '#d97706',
                            '#dc2626'
                        ],
                        borderWidth: 1.5,
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                color: '#94a3b8',
                                font: { size: 10 }
                            },
                            grid: { color: '#f8fafc' }
                        },
                        x: {
                            ticks: {
                                color: '#64748b',
                                font: { size: 10, weight: '700' }
                            },
                            grid: { display: false }
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>