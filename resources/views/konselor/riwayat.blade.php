<x-app-layout>
    <x-slot name="dashboardRole">konselor</x-slot>
    <x-slot name="headerTitle">Riwayat Konseling</x-slot>

    @php
        $totalRiwayat = $riwayatKonseling->count();
        $selesai = $riwayatKonseling->where('status_pengajuan', 'selesai')->count();
        $berlangsung = $riwayatKonseling->whereIn('status_pengajuan', ['disetujui'])->count();
        $menunggu = $riwayatKonseling->whereIn('status_pengajuan', ['menunggu', 'reschedule'])->count();
    @endphp

    <section class="space-y-6">
        <div class="kc-card overflow-hidden">
            <div class="grid gap-6 p-6 lg:grid-cols-[1fr_280px] lg:p-8">
                <div>
                    <p class="text-sm font-semibold text-indigo-500">Dokumentasi Konseling</p>
                    <h2 class="mt-3 text-2xl font-bold text-kc-heading md:text-3xl">Riwayat Konseling</h2>
                    <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-500">
                        Telusuri rekam jejak konseling setiap konseli. Klik "Lihat Detail" untuk melihat catatan sesi,
                        rekomendasi, dan keluhan awal.
                    </p>
                </div>
                <div class="rounded-xl border border-sky-100 bg-sky-50 p-5">
                    <p class="text-sm font-semibold text-sky-600">Konseling Selesai</p>
                    <p class="mt-2 text-3xl font-bold text-kc-heading">{{ $selesai }}</p>
                    <p class="mt-1 text-sm leading-6 text-slate-500">Sesi yang sudah memiliki hasil konseling.</p>
                </div>
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-4">
            <article class="kc-card p-5">
                <p class="text-sm text-slate-400">Total Riwayat</p>
                <p class="mt-3 text-2xl font-bold text-kc-heading">{{ $totalRiwayat }}</p>
            </article>
            <article class="kc-card p-5">
                <p class="text-sm text-slate-400">Selesai</p>
                <p class="mt-3 text-2xl font-bold text-sky-500">{{ $selesai }}</p>
            </article>
            <article class="kc-card p-5">
                <p class="text-sm text-slate-400">Berlangsung</p>
                <p class="mt-3 text-2xl font-bold text-indigo-500">{{ $berlangsung }}</p>
            </article>
            <article class="kc-card p-5">
                <p class="text-sm text-slate-400">Menunggu / Reschedule</p>
                <p class="mt-3 text-2xl font-bold text-amber-500">{{ $menunggu }}</p>
            </article>
        </div>

        <div class="grid gap-6 xl:grid-cols-[1fr_320px]">
            <article class="kc-card">
                <div class="flex flex-wrap items-center justify-between gap-4 border-b border-slate-100 px-6 py-5">
                    <div>
                        <h2 class="text-lg font-semibold text-kc-heading">Daftar Riwayat Konseling</h2>
                        <p class="mt-1 text-sm text-slate-500">Klik "Lihat Detail" untuk melihat rekam jejak lengkap per
                            konseli.</p>
                    </div>
                    <div class="relative">
                        <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-slate-400">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z" />
                            </svg>
                        </span>
                        <input type="text" id="searchRiwayat" placeholder="Cari nama konseli..."
                            oninput="filterRiwayat(this.value)"
                            class="w-56 rounded-lg border border-slate-200 py-2 pl-9 pr-4 text-sm outline-none transition focus:border-indigo-400 focus:ring-4 focus:ring-indigo-100" />
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[800px] text-left text-sm">
                        <thead class="text-xs uppercase tracking-wide text-slate-400">
                            <tr>
                                <th class="px-6 py-4 font-semibold">Tanggal Pengajuan</th>
                                <th class="px-6 py-4 font-semibold">Nama Konseli</th>
                                <th class="px-6 py-4 font-semibold">Status</th>
                                <th class="px-6 py-4 font-semibold">Catatan Sesi</th>
                                <th class="px-6 py-4 font-semibold">Rekomendasi / Tindak Lanjut</th>
                                <th class="px-6 py-4 font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">

                            @forelse ($riwayatKonseling as $p)
                                {{-- Baris utama --}}
                                <tr class="kc-riwayat-row hover:bg-slate-50 transition">
                                    <td class="px-6 py-4 text-slate-500">
                                        {{ \Illuminate\Support\Carbon::parse($p->created_at)->translatedFormat('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 font-semibold text-kc-heading">
                                        {{ $p->konseli?->user?->nama ?? '-' }}
                                        @if ($p->konseli?->no_hp)
                                            <p class="mt-0.5 text-xs font-normal text-slate-400">
                                                {{ $p->konseli->no_hp }}</p>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <x-dashboard.status-badge :status="$p->status_pengajuan" />
                                    </td>
                                    <td class="px-6 py-4 text-slate-500 max-w-[200px]">
                                        @if ($p->hasil?->catatan_konseling)
                                            {{ \Illuminate\Support\Str::limit($p->hasil->catatan_konseling, 60) }}
                                        @else
                                            <span class="italic text-slate-300">Belum ada catatan</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-slate-500 max-w-[180px]">
                                        @if ($p->hasil?->rekomendasi)
                                            {{ \Illuminate\Support\Str::limit($p->hasil->rekomendasi, 60) }}
                                        @else
                                            <span class="italic text-slate-300">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <button type="button"
                                            onclick="document.getElementById('detail-{{ $p->id_pengajuan }}').classList.toggle('hidden')"
                                            class="rounded-md border border-indigo-200 px-3 py-1.5 text-xs font-semibold text-indigo-600 transition hover:bg-indigo-50">
                                            Lihat Detail
                                        </button>
                                    </td>
                                </tr>

                                {{-- Expandable: rekam jejak lengkap --}}
                                <tr id="detail-{{ $p->id_pengajuan }}" class="kc-riwayat-detail hidden">
                                    <td colspan="6" class="bg-slate-50 px-8 py-6">
                                        <div class="grid gap-6 md:grid-cols-2">

                                            {{-- Keluhan awal dari assessment --}}
                                            <div>
                                                <p
                                                    class="mb-2 text-xs font-bold uppercase tracking-wide text-slate-400">
                                                    Keluhan Awal</p>
                                                <div
                                                    class="rounded-lg border border-slate-200 bg-white p-4 text-sm leading-6 text-slate-600">
                                                    {{ $p->assessment?->keluhan ?? 'Tidak ada data assessment.' }}
                                                </div>
                                            </div>

                                            {{-- Info jadwal --}}
                                            <div>
                                                <p
                                                    class="mb-2 text-xs font-bold uppercase tracking-wide text-slate-400">
                                                    Info Sesi</p>
                                                <div
                                                    class="rounded-lg border border-slate-200 bg-white p-4 text-sm space-y-2">
                                                    <div class="flex justify-between">
                                                        <span class="text-slate-400">Usulan awal</span>
                                                        <span class="font-semibold text-kc-heading">
                                                            {{ $p->tanggal_usulan ? \Illuminate\Support\Carbon::parse($p->tanggal_usulan)->translatedFormat('d M Y') : '-' }},
                                                            {{ $p->jam_usulan ? \Illuminate\Support\Carbon::parse($p->jam_usulan)->format('H:i') : '-' }}
                                                        </span>
                                                    </div>
                                                    @if ($p->jadwal)
                                                        <div class="flex justify-between">
                                                            <span class="text-slate-400">Jadwal resmi</span>
                                                            <span class="font-semibold text-kc-heading">
                                                                {{ \Illuminate\Support\Carbon::parse($p->jadwal->tanggal)->translatedFormat('d M Y') }},
                                                                {{ \Illuminate\Support\Carbon::parse($p->jadwal->jam)->format('H:i') }}
                                                            </span>
                                                        </div>
                                                    @endif
                                                    <div class="flex justify-between">
                                                        <span class="text-slate-400">Tipe</span>
                                                        <span
                                                            class="font-semibold text-kc-heading">{{ ucfirst($p->tipe_konseling_usulan ?? '-') }}</span>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Catatan sesi (full) --}}
                                            @if ($p->hasil?->catatan_konseling)
                                                <div class="md:col-span-2">
                                                    <p
                                                        class="mb-2 text-xs font-bold uppercase tracking-wide text-slate-400">
                                                        Catatan Sesi</p>
                                                    <div
                                                        class="rounded-lg border border-slate-200 bg-white p-4 text-sm leading-6 text-slate-600">
                                                        {{ $p->hasil->catatan_konseling }}
                                                    </div>
                                                </div>
                                            @endif

                                            {{-- Rekomendasi / Tindak lanjut (full) --}}
                                            @if ($p->hasil?->rekomendasi)
                                                <div class="md:col-span-2">
                                                    <p
                                                        class="mb-2 text-xs font-bold uppercase tracking-wide text-sky-500">
                                                        Rekomendasi / Tindak Lanjut</p>
                                                    <div
                                                        class="rounded-lg border border-sky-100 bg-sky-50 p-4 text-sm leading-6 text-slate-600">
                                                        {{ $p->hasil->rekomendasi }}
                                                    </div>
                                                </div>
                                            @endif

                                            @if (!$p->hasil)
                                                <div class="md:col-span-2">
                                                    <p class="text-sm italic text-slate-400">Sesi belum diselesaikan —
                                                        belum ada catatan maupun rekomendasi.</p>
                                                </div>
                                            @endif
                                        </div>

                                        {{-- Footer: tanggal selesai --}}
                                        @if ($p->hasil)
                                            <p class="mt-4 text-xs text-slate-400">
                                                Sesi diselesaikan pada
                                                {{ \Illuminate\Support\Carbon::parse($p->hasil->created_at)->translatedFormat('d M Y, H:i') }}
                                            </p>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-10 text-center text-slate-400">
                                        Belum ada riwayat konseling.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </article>

            <aside class="space-y-6">
                <article class="kc-card p-6">
                    <h2 class="text-lg font-semibold text-kc-heading">Tindak Lanjut</h2>
                    <div class="mt-5 space-y-3">
                        <a href="{{ route('konselor.jadwal') }}"
                            class="block rounded-xl border border-indigo-100 bg-indigo-50 px-4 py-3 text-sm font-semibold text-indigo-600 transition hover:-translate-y-0.5 hover:shadow-md hover:shadow-indigo-100">
                            Cek Jadwal Berikutnya
                        </a>
                        <a href="{{ route('konselor.pengajuan') }}"
                            class="block rounded-xl border border-slate-100 px-4 py-3 text-sm font-semibold text-kc-heading transition hover:-translate-y-0.5 hover:bg-slate-50">
                            Lihat Pengajuan Baru
                        </a>
                    </div>
                </article>

                <article class="kc-card p-6">
                    <h2 class="text-lg font-semibold text-kc-heading">Catatan Kualitas</h2>
                    <p class="mt-3 text-sm leading-6 text-slate-500">
                        Riwayat membantu konselor menjaga kesinambungan pendampingan dan melihat pola kebutuhan konseli
                        dari waktu ke waktu.
                    </p>
                </article>
            </aside>
        </div>
    </section>

    <script>
        function filterRiwayat(q) {
            const query = q.toLowerCase().trim();
            const rows = document.querySelectorAll('tr.kc-riwayat-row');

            rows.forEach(row => {
                // Ambil text nama dari kolom ke-2 (index 1)
                const nama = row.cells[1]?.textContent.trim().toLowerCase() ?? '';
                const match = query === '' || nama.includes(query);

                row.style.display = match ? '' : 'none';

                // Sembunyikan juga expandable detail row-nya
                const id = row.querySelector('[id^="detail-"]');
                if (!id) {
                    // cari sibling detail row
                    const detailId = row.nextElementSibling;
                    if (detailId && detailId.classList.contains('kc-riwayat-detail')) {
                        detailId.style.display = match ? '' : 'none';
                    }
                }
            });

            // Sembunyikan detail row yang rownya hidden
            document.querySelectorAll('tr.kc-riwayat-detail').forEach(detail => {
                const prev = detail.previousElementSibling;
                if (prev && prev.style.display === 'none') {
                    detail.style.display = 'none';
                }
            });
        }
    </script>


    <script>
        function filterRiwayat(query) {
            const q = query.trim().toLowerCase();
            const rows = document.querySelectorAll('tr.kc-riwayat-row');

            rows.forEach(function(row) {
                // Kolom ke-2 (index 1) adalah nama konseli
                const nameCell = row.querySelectorAll('td')[1];
                const name = nameCell ? nameCell.textContent.trim().toLowerCase() : '';
                const match = q === '' || name.includes(q);

                row.style.display = match ? '' : 'none';

                // Sembunyikan juga expandable detail row-nya
                const detailId = row.nextElementSibling;
                if (detailId && detailId.classList.contains('kc-riwayat-detail')) {
                    if (!match) detailId.style.display = 'none';
                    // Biarkan detail tetap hidden/shown sesuai toggle jika match
                }
            });
        }
    </script>
</x-app-layout>
