<x-app-layout>
    <x-slot name="headerTitle">Dashboard Konseli</x-slot>

    @php
        $formatDate = fn ($value) => $value ? \Illuminate\Support\Carbon::parse($value)->translatedFormat('d M Y') : '-';
        $formatTime = fn ($value) => $value ? \Illuminate\Support\Carbon::parse($value)->format('H:i') : '-';
        $totalStatus = max(1, array_sum($statusCounts));
    @endphp

    <section class="space-y-6">
        @if ($isFallback)
            <div class="kc-card p-4 text-sm text-amber-700" style="background:#fff8e1;border-color:#ffe0a3;">
                Data konseling belum tersedia di database. Dashboard menampilkan data contoh sementara agar struktur tampilan tetap dapat diuji.
            </div>

        @endif

        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
            <a href="{{ route('konseli.assessment') }}" class="kc-card p-5 transition hover:-translate-y-0.5 hover:shadow-lg">
                <p class="text-sm text-slate-400">Akses Cepat</p>
                <h2 class="mt-2 text-lg font-semibold text-kc-heading">Buat Assessment</h2>
                <p class="mt-2 text-sm leading-6 text-slate-500">Ceritakan kondisi awalmu sebelum mengajukan konseling.</p>
            </a>

            <a href="{{ route('konseli.pengajuan') }}" class="kc-card p-5 transition hover:-translate-y-0.5 hover:shadow-lg">
                <p class="text-sm text-slate-400">Akses Cepat</p>
                <h2 class="mt-2 text-lg font-semibold text-kc-heading">Ajukan Konseling</h2>
                <p class="mt-2 text-sm leading-6 text-slate-500">Pilih konselor dan jadwal yang paling sesuai.</p>
            </a>

            <a href="{{ route('konseli.jadwal') }}" class="kc-card p-5 transition hover:-translate-y-0.5 hover:shadow-lg">
                <p class="text-sm text-slate-400">Akses Cepat</p>
                <h2 class="mt-2 text-lg font-semibold text-kc-heading">Lihat Jadwal</h2>
                <p class="mt-2 text-sm leading-6 text-slate-500">Cek sesi berikutnya dan status jadwalmu.</p>
            </a>
        </div>

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ($stats as $stat)
                @php
                    $colorMap = [
                        'primary' => ['bg' => 'var(--kc-primary-soft)', 'text' => 'var(--kc-primary)'],
                        'warning' => ['bg' => 'var(--kc-warning-soft)', 'text' => 'var(--kc-warning)'],
                        'success' => ['bg' => 'var(--kc-success-soft)', 'text' => 'var(--kc-success)'],
                        'info' => ['bg' => 'var(--kc-info-soft)', 'text' => 'var(--kc-info)'],
                    ];
                    $color = $colorMap[$stat['color']] ?? $colorMap['primary'];
                @endphp
                <article class="kc-card p-5">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-sm text-slate-400">{{ $stat['label'] }}</p>
                            <p class="mt-2 text-3xl font-bold text-kc-heading">{{ $stat['value'] }}</p>
                        </div>
                        <div class="grid h-11 w-11 place-items-center rounded-lg text-xl" style="background:{{ $color['bg'] }};color:{{ $color['text'] }};">
                            {{ match ($stat['label']) {
                                'Total Konseling' => 'T',
                                'Pengajuan Menunggu' => 'P',
                                'Konseling Disetujui' => 'A',
                                default => 'S',
                            } }}
                        </div>
                    </div>
                </article>
            @endforeach

        </div>

        <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
            <article id="riwayat-konseling" class="kc-card xl:col-span-2">
                <div class="border-b border-slate-100 px-6 py-5">
                    <h2 class="text-lg font-semibold text-kc-heading">Riwayat Konseling Terbaru</h2>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[640px] text-left text-sm">
                        <thead class="text-xs uppercase tracking-wide text-slate-400">
                            <tr>
                                <th class="px-6 py-4 font-semibold">Tanggal</th>
                                <th class="px-6 py-4 font-semibold">Konselor</th>
                                <th class="px-6 py-4 font-semibold">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($riwayatTerbaru as $item)
                                @php
                                    $status = strtolower($item->status_pengajuan ?? $item->status ?? 'menunggu');
                                    $tanggal = $item->tanggal ?? $item->created_at ?? null;
                                    $konselor = $item->konselor?->user?->nama
                                        ?? $item->konselor?->nama
                                        ?? $item->nama_konselor
                                        ?? 'Belum ditentukan';
                                @endphp
                                <tr>
                                    <td class="px-6 py-4 text-kc-heading">{{ $formatDate($tanggal) }}</td>
                                    <td class="px-6 py-4">{{ $konselor }}</td>
                                    <td class="px-6 py-4">
                                        <x-dashboard.status-badge :status="$status" />
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-8 text-center text-slate-400">
                                        Belum ada riwayat konseling.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </article>

            <article class="kc-card p-6">
                <h2 class="text-lg font-semibold text-kc-heading">Ringkasan Status</h2>
                <div class="mt-6 space-y-5">
                    @foreach ([['Menunggu', 'menunggu', '#ffab00'], ['Disetujui', 'disetujui', '#71dd37'], ['Berlangsung', 'berlangsung', '#696cff'], ['Selesai', 'selesai', '#03c3ec'], ['Ditolak', 'ditolak', '#ff5b5c']] as [$label, $key, $color])
                        @php
                            $value = $statusCounts[$key] ?? 0;
                            $width = round(($value / $totalStatus) * 100);
                        @endphp
                        <div>
                            <div class="mb-2 flex items-center justify-between text-sm">
                                <span>{{ $label }}</span>
                                <span class="font-semibold text-kc-heading">{{ $value }}</span>
                            </div>
                            <div class="h-2 rounded-full bg-slate-100">
                                <div class="h-2 rounded-full" style="width: {{ $width }}%; background: {{ $color }};"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </article>
        </div>

        <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
            <article id="assessment-awal" class="kc-card p-6">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-sm text-slate-400">Assessment Terakhir</p>
                        <h2 class="mt-1 text-lg font-semibold text-kc-heading">
                            {{ $formatDate($assessmentTerakhir->created_at ?? null) }}
                        </h2>
                    </div>
                    <span class="grid h-10 w-10 place-items-center rounded-lg bg-indigo-50 text-indigo-600">A</span>
                </div>
                <p class="mt-5 text-sm leading-6">
                    {{ $assessmentTerakhir->ringkasan_hasil ?? $assessmentTerakhir->hasil ?? $assessmentTerakhir->keluhan ?? 'Belum ada assessment yang tercatat.' }}
                </p>
                <a href="{{ route('konseli.assessment') }}" class="mt-5 inline-flex rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white">
                    Lihat Detail
                </a>
            </article>

            <article id="jadwal-konseling" class="kc-card p-6">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-sm text-slate-400">Konseling Berikutnya</p>
                        <h2 class="mt-1 text-lg font-semibold text-kc-heading">
                            {{ $formatDate($jadwalBerikutnya->jadwal->tanggal ?? $jadwalBerikutnya->tanggal ?? null) }}
                        </h2>
                    </div>
                    <span class="grid h-10 w-10 place-items-center rounded-lg bg-sky-50 text-sky-600">J</span>
                </div>
                <div class="mt-5 space-y-3 text-sm">
                    <div class="flex justify-between gap-4">
                        <span class="text-slate-400">Jam</span>
                        <span class="font-semibold text-kc-heading">{{ $jadwalBerikutnya->jadwal->jam ?? $jadwalBerikutnya->jam ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between gap-4">
                        <span class="text-slate-400">Konselor</span>
                        <span class="text-right font-semibold text-kc-heading">
                            {{ $jadwalBerikutnya->konselor?->user?->nama ?? $jadwalBerikutnya->konselor?->nama ?? 'Belum ditentukan' }}
                        </span>
                    </div>
                    <div class="flex justify-between gap-4">
                        <span class="text-slate-400">Status</span>
                        @php
                            $jadwalStatus = strtolower($jadwalBerikutnya->status_pengajuan ?? $jadwalBerikutnya->jadwal->status_jadwal ?? $jadwalBerikutnya->status ?? 'menunggu');
                        @endphp
                        <x-dashboard.status-badge :status="$jadwalStatus" />
                    </div>
                </div>
            </article>

            <article class="kc-card p-6">
                <h2 class="text-lg font-semibold text-kc-heading">Aktivitas Terbaru</h2>
                <div class="mt-5 space-y-5">
                    @forelse ($aktivitas as $activity)
                        <div class="flex gap-3">
                            <span class="mt-1 h-2.5 w-2.5 flex-none rounded-full bg-indigo-500"></span>
                            <div>
                                <p class="text-sm font-semibold text-kc-heading">
                                    {{ $activity->aktivitas ?? $activity->description ?? 'Aktivitas pengguna' }}
                                </p>
                                <p class="mt-1 text-xs text-slate-400">
                                    {{ $formatDate($activity->created_at ?? null) }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-slate-400">Belum ada aktivitas terbaru.</p>
                    @endforelse
                </div>
            </article>
        </div>
    </section>
</x-app-layout>
