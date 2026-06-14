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
        {{-- Header Page --}}
        <x-dashboard.page-header title="Riwayat Konseling"
            subtitle="Telusuri rekam jejak konseling setiap konseli dan pantau tindak lanjut sesi." />

        {{-- Statistik Cards --}}
        <div class="grid gap-4 md:grid-cols-4">
            <article class="kc-card p-5">
                <p class="text-sm text-slate-400">Total Riwayat</p>
                <p class="mt-2 text-2xl font-bold text-kc-heading">{{ $totalRiwayat }}</p>
            </article>
            <article class="kc-card p-5">
                <p class="text-sm text-slate-400">Selesai</p>
                <p class="mt-2 text-2xl font-bold text-emerald-600">{{ $selesai }}</p>
            </article>
            <article class="kc-card p-5">
                <p class="text-sm text-slate-400">Berlangsung</p>
                <p class="mt-2 text-2xl font-bold text-indigo-600">{{ $berlangsung }}</p>
            </article>
            <article class="kc-card p-5">
                <p class="text-sm text-slate-400">Menunggu</p>
                <p class="mt-2 text-2xl font-bold text-amber-500">{{ $menunggu }}</p>
            </article>
        </div>

        {{-- Main Table Area --}}
        <article class="kc-card">
            <div class="flex flex-wrap items-center justify-between gap-4 border-b border-slate-100 px-6 py-5">
                <h2 class="text-lg font-semibold text-kc-heading">Daftar Riwayat Konseling</h2>
                <input type="text" id="searchRiwayat" placeholder="Cari nama konseli..."
                    oninput="filterRiwayat(this.value)"
                    class="w-64 rounded-lg border border-slate-200 px-4 py-2 text-sm outline-none transition focus:border-indigo-400 focus:ring-4 focus:ring-indigo-100" />
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[800px] text-left text-sm">
                    <thead class="text-xs uppercase tracking-wide text-slate-400">
                        <tr>
                            <th class="px-6 py-4 font-semibold">Tanggal</th>
                            <th class="px-6 py-4 font-semibold">Konseli</th>
                            <th class="px-6 py-4 font-semibold">Status</th>
                            <th class="px-6 py-4 font-semibold">Catatan Sesi</th>
                            <th class="px-6 py-4 font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($riwayatKonseling as $p)
                            <tr class="kc-riwayat-row hover:bg-slate-50 transition">
                                <td class="px-6 py-4 text-slate-500">
                                    {{ \Illuminate\Support\Carbon::parse($p->created_at)->translatedFormat('d M Y') }}
                                </td>
                                <td class="px-6 py-4 font-semibold text-kc-heading">
                                    {{ $p->konseli?->user?->nama ?? '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    <x-dashboard.status-badge :status="$p->status_pengajuan" />
                                </td>
                                <td class="px-6 py-4 text-slate-500 max-w-[250px] truncate">
                                    {{ $p->hasil?->catatan_konseling ?? '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    <button type="button"
                                        onclick="document.getElementById('detail-{{ $p->id_pengajuan }}').classList.toggle('hidden')"
                                        class="rounded-lg border border-indigo-100 bg-indigo-50 px-3 py-1.5 text-xs font-semibold text-indigo-700 transition hover:bg-indigo-100">
                                        Lihat Detail
                                    </button>
                                </td>
                            </tr>

                            {{-- Detail Row --}}
                            <tr id="detail-{{ $p->id_pengajuan }}" class="hidden bg-slate-50">
                                <td colspan="5" class="px-6 py-6">
                                    <div class="grid gap-6 md:grid-cols-2">
                                        <div>
                                            <p class="mb-2 text-xs font-bold uppercase tracking-wide text-slate-400">Keluhan Awal</p>
                                            <div class="rounded-lg border border-slate-200 bg-white p-4 text-sm text-slate-600">
                                                {{ $p->assessment?->keluhan ?? 'Tidak ada data.' }}
                                            </div>
                                        </div>
                                        <div>
                                            <p class="mb-2 text-xs font-bold uppercase tracking-wide text-sky-600">Rekomendasi / Tindak Lanjut</p>
                                            <div class="rounded-lg border border-sky-100 bg-white p-4 text-sm text-slate-600">
                                                {{ $p->hasil?->rekomendasi ?? '-' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-slate-400">Belum ada riwayat konseling.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </article>
    </section>

    <script>
        function filterRiwayat(query) {
            const q = query.toLowerCase().trim();
            document.querySelectorAll('tr.kc-riwayat-row').forEach(row => {
                const name = row.cells[1].textContent.toLowerCase();
                const isVisible = name.includes(q);
                row.style.display = isVisible ? '' : 'none';
                
                const detailRow = row.nextElementSibling;
                if (detailRow && detailRow.id.startsWith('detail-')) {
                    detailRow.style.display = isVisible ? (detailRow.classList.contains('hidden') ? 'none' : '') : 'none';
                }
            });
        }
    </script>
</x-app-layout>