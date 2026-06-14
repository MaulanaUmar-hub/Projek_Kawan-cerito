<x-app-layout>
    <x-slot name="headerTitle">Dashboard Konseli</x-slot>

    @php
    $formatDate = fn($value) => $value ? \Illuminate\Support\Carbon::parse($value)->translatedFormat('d M Y') : '-';
    $formatTime = fn($value) => $value ? \Illuminate\Support\Carbon::parse($value)->format('H:i') : '-';
    $totalStatus = max(1, array_sum($statusCounts));
    @endphp

    <section class="space-y-6">

        @if ($isFallback)
        <div class="p-3 text-xs text-amber-800 rounded-xl bg-amber-50 border border-amber-200">
            Data konseling belum tersedia di database. Menampilkan data contoh sementara.
        </div>
        @endif

        {{-- HEADER SAPAAN TANPA CARD (UKURAN BESAR & TEGAS) --}}
        <header class="pt-2">
            <h1 class="text-3xl font-black tracking-tight text-slate-900">
                Halo, {{ Auth::user()->nama ?? 'Rani' }}!
            </h1>
            <p class="mt-1 text-sm text-slate-500">
                Langkah mudah untuk memulai dan memantau sesi telekonseling kesehatan mentalmu.
            </p>
        </header>

        {{-- TAHAPAN ALUR (UKURAN LEBAR MAKSIMAL) --}}
        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">

            {{-- LANGKAH 1: ASSESSMENT (GREEN) --}}
            <a href="{{ route('konseli.assessment') }}"
                class="group relative flex flex-col justify-between bg-[#E6F5EA] rounded-2xl p-5 border border-green-200/80 transition-all duration-300 hover:-translate-y-0.5 hover:shadow-md hover:bg-[#D4EEDC]">
                <div>
                    <div class="flex items-center justify-between">
                        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-white text-green-700 shadow-sm">
                            <i class="bi bi-file-earmark-text text-lg"></i>
                        </span>
                        <span class="text-[11px] font-bold tracking-wider text-green-800 bg-green-200 px-2.5 py-0.5 rounded-full">Langkah 1</span>
                    </div>
                    <h2 class="mt-4 text-sm font-bold text-slate-900 group-hover:text-green-800">Buat Assessment</h2>
                    <p class="mt-1 text-[11px] text-slate-600 leading-relaxed">Isi kuesioner singkat kondisi psikologismu sebelum mengajukan sesi.</p>
                </div>
                <div class="mt-4 flex items-center justify-between border-t border-green-300/30 pt-3">
                    <span class="text-[11px] font-bold text-green-700">Mulai Isi</span>
                    <i class="bi bi-arrow-right text-xs text-green-700 transition-transform group-hover:translate-x-1"></i>
                </div>
            </a>

            {{-- LANGKAH 2: PENGAJUAN (PURPLE) --}}
            <a href="{{ route('konseli.pengajuan') }}"
                class="group relative flex flex-col justify-between bg-[#F3E8FF] rounded-2xl p-5 border border-purple-200/80 transition-all duration-300 hover:-translate-y-0.5 hover:shadow-md hover:bg-[#E9D5FF]">
                <div>
                    <div class="flex items-center justify-between">
                        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-white text-purple-700 shadow-sm">
                            <i class="bi bi-chat-heart text-lg"></i>
                        </span>
                        <span class="text-[11px] font-bold tracking-wider text-purple-800 bg-purple-200 px-2.5 py-0.5 rounded-full">Langkah 2</span>
                    </div>
                    <h2 class="mt-4 text-sm font-bold text-slate-900 group-hover:text-purple-800">Ajukan Konseling</h2>
                    <p class="mt-1 text-[11px] text-slate-600 leading-relaxed">Kirim permohonan dengan memilih preferensi konselor yang cocok.</p>
                </div>
                <div class="mt-4 flex items-center justify-between border-t border-purple-300/30 pt-3">
                    <span class="text-[11px] font-bold text-purple-700">Ajukan Sekarang</span>
                    <i class="bi bi-arrow-right text-xs text-purple-700 transition-transform group-hover:translate-x-1"></i>
                </div>
            </a>

            {{-- LANGKAH 3: JADWAL (BLUE) --}}
            <a href="{{ route('konseli.jadwal') }}"
                class="group relative flex flex-col justify-between bg-[#E0F2FE] rounded-2xl p-5 border border-sky-200/80 transition-all duration-300 hover:-translate-y-0.5 hover:shadow-md hover:bg-[#BAE6FD]">
                <div>
                    <div class="flex items-center justify-between">
                        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-white text-sky-700 shadow-sm">
                            <i class="bi bi-calendar4-event text-lg"></i>
                        </span>
                        <span class="text-[11px] font-bold tracking-wider text-sky-800 bg-sky-200 px-2.5 py-0.5 rounded-full">Langkah 3</span>
                    </div>
                    <h2 class="mt-4 text-sm font-bold text-slate-900 group-hover:text-sky-800">Pilih & Lihat Jadwal</h2>
                    <p class="mt-1 text-[11px] text-slate-600 leading-relaxed Tentukan jam temu setelah disetujui, dan pantau sesi aktifmu di sini."></p>
                </div>
                <div class="mt-4 flex items-center justify-between border-t border-sky-300/40 pt-3">
                    <span class="text-[11px] font-bold text-sky-700">Lihat Jadwal</span>
                    <i class="bi bi-arrow-right text-xs text-sky-700 transition-transform group-hover:translate-x-1"></i>
                </div>
            </a>

        </div>

        {{-- LAYOUT DUA KOLOM (MAKSIMAL SAMPAI UJUNG LAYAR KANAN KIRI) --}}
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

            {{-- KOLOM KIRI (RIWAYAT & ASSESSMENT TERAKHIR) --}}
            <div class="lg:col-span-2 space-y-5">

                {{-- RIWAYAT KONSUL --}}
                <article class="bg-white rounded-2xl border border-slate-200 shadow-[0_2px_8px_rgba(0,0,0,0.01)] overflow-hidden">
                    <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                        <h2 class="text-sm font-bold text-slate-900">Riwayat Konseling Terbaru</h2>

                        @foreach ($stats as $stat)
                        @if($stat['label'] == 'Total Konseling')
                        <span class="text-xs text-slate-500">Total: <strong class="text-slate-900 font-extrabold">{{ $stat['value'] }} Sesi</strong></span>
                        @endif
                        @endforeach
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider">
                                <tr>
                                    <th class="px-5 py-3 font-semibold">Tanggal</th>
                                    <th class="px-5 py-3 font-semibold">Konselor</th>
                                    <th class="px-5 py-3 font-semibold">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse ($riwayatTerbaru as $item)
                                @php
                                $status = strtolower($item->status_pengajuan ?? ($item->status ?? 'menunggu'));
                                $tanggal = $item->tanggal ?? ($item->created_at ?? null);
                                $konselor = $item->konselor?->user?->nama ?? ($item->konselor?->nama ?? ($item->nama_konselor ?? 'Belum ditentukan'));
                                @endphp
                                <tr class="hover:bg-slate-50/40 transition-colors">
                                    <td class="px-5 py-3 font-medium text-slate-900">{{ $formatDate($tanggal) }}</td>
                                    <td class="px-5 py-3 text-slate-700">{{ $konselor }}</td>
                                    <td class="px-5 py-3"><x-dashboard.status-badge :status="$status" /></td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="px-5 py-8 text-center text-slate-400">Belum ada riwayat konseling.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </article>

                {{-- INFO ASSESSMENT TERAKHIR --}}
                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div class="space-y-0.5">
                        <div class="flex items-center gap-2 text-xs font-bold text-slate-400 uppercase tracking-wider">
                            <i class="bi bi-journal-check"></i> <span>Assessment Terakhir</span>
                            <span class="text-slate-900 normal-case font-black">({{ $formatDate($assessmentTerakhir->created_at ?? null) }})</span>
                        </div>
                        <p class="text-xs text-slate-600 leading-relaxed line-clamp-1">
                            {{ $assessmentTerakhir->ringkasan_hasil ?? ($assessmentTerakhir->hasil ?? ($assessmentTerakhir->keluhan ?? 'Belum ada catatan.')) }}
                        </p>
                    </div>
                    <a href="{{ route('konseli.assessment') }}"
                        class="text-xs font-bold text-[#5B67F1] hover:text-indigo-800 transition-colors whitespace-nowrap">
                        Lihat Detail →
                    </a>
                </div>

            </div>

            {{-- KOLOM KANAN (SIDE PANEL) --}}
            <div class="space-y-4">

                {{-- CARD JADWAL (BLUE) --}}
                {{-- CARD JADWAL (DIUBAH JADI KUNING/AMBER PASTEL TEGAS) --}}
                <article class="bg-[#FFF3D6] rounded-2xl p-4 border border-amber-200">
                    <div class="flex items-center justify-between">
                        <p class="text-[11px] font-bold text-amber-800 uppercase tracking-wider">Jadwal Terdekat</p>
                        <i class="bi bi-calendar-check text-amber-700"></i>
                    </div>
                    <div class="mt-2">
                        <h3 class="text-base font-black text-slate-900">
                            {{ $formatDate($jadwalBerikutnya->jadwal->tanggal ?? ($jadwalBerikutnya->tanggal ?? null)) }}
                        </h3>
                        <p class="text-xs text-slate-700 mt-0.5">
                            Jam: <span class="font-bold">{{ $jadwalBerikutnya->jadwal->jam ?? ($jadwalBerikutnya->jam ?? '-') }}</span>
                            • Bersama: <span class="font-bold">{{ $jadwalBerikutnya->konselor?->user?->nama ?? ($jadwalBerikutnya->konselor?->nama ?? 'Belum ditentukan') }}</span>
                        </p>
                    </div>
                    <div class="mt-3 pt-2 border-t border-amber-300/40 flex justify-between items-center">
                        <span class="text-[10px] text-amber-800 font-medium">Status Pengajuan</span>
                        @php
                        $jadwalStatus = strtolower($jadwalBerikutnya->status_pengajuan ?? ($jadwalBerikutnya->jadwal->status_jadwal ?? ($jadwalBerikutnya->status ?? 'menunggu')));
                        @endphp
                        <x-dashboard.status-badge :status="$jadwalStatus" />
                    </div>
                </article>
                {{-- RINGKASAN PROGRESS --}}
                <article class="bg-white rounded-2xl border border-slate-200 p-4">
                    <h2 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Ringkasan Status</h2>
                    <div class="space-y-2.5">
                        @foreach ([['Menunggu', 'menunggu', '#d97706'], ['Disetujui', 'disetujui', '#16a34a'], ['Berlangsung', 'berlangsung', '#7c3aed'], ['Selesai', 'selesai', '#0284c7']] as [$label, $key, $color])
                        @php
                        $value = $statusCounts[$key] ?? ($key === 'menunggu' ? $statusCounts['pending'] ?? 0 : 0);
                        $width = round(($value / $totalStatus) * 100);
                        @endphp
                        <div class="flex items-center gap-3">
                            {{-- Mengubah w-16 menjadi w-20 dan menghapus truncate agar tulisan Berlangsung aman --}}
                            <span class="text-[11px] text-slate-600 w-20 text-left whitespace-nowrap font-medium">{{ $label }}</span>

                            <div class="h-1.5 flex-1 rounded-full bg-slate-100 overflow-hidden">
                                <div class="h-1.5 rounded-full transition-all duration-500" style="width: {{ $width }}%; background: {{ $color }};"></div>
                            </div>
                            <span class="text-[11px] font-bold text-slate-900 w-4 text-right">{{ $value }}</span>
                        </div>
                        @endforeach
                    </div>
                </article>

                {{-- LOG AKTIVITAS --}}
                <div class="px-2">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Aktivitas Terakhir</h3>
                    <div class="space-y-3">
                        @forelse ($aktivitas->take(2) as $activity)
                        <div class="text-xs leading-normal">
                            <p class="font-semibold text-slate-800">{{ $activity->aktivitas ?? 'Aktivitas pengguna' }}</p>
                            <p class="text-[10px] text-slate-400">{{ $formatDate($activity->created_at ?? null) }}</p>
                        </div>
                        @empty
                        <p class="text-xs text-slate-400">Belum ada aktivitas terbaru.</p>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    </section>
</x-app-layout>