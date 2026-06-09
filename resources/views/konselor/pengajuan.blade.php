<x-app-layout>
    <x-slot name="dashboardRole">konselor</x-slot>
    <x-slot name="headerTitle">Pengajuan Konseling</x-slot>

    @php
        $formatDate = fn($value) => \Illuminate\Support\Carbon::parse($value)->translatedFormat('d M Y');
        $totalPengajuan = collect($pengajuanTerbaru)->count();
        $menunggu = collect($pengajuanTerbaru)->whereIn('status', ['menunggu', 'pending', 'baru'])->count();
        $disetujui = collect($pengajuanTerbaru)->whereIn('status', ['disetujui', 'aktif'])->count();
        $ditolak = collect($pengajuanTerbaru)->where('status', 'ditolak')->count();

        $summaryCards = [
            ['label' => 'Total Pengajuan', 'value' => $totalPengajuan, 'hint' => 'Semua pengajuan yang masuk', 'color' => 'text-indigo-500'],
            ['label' => 'Menunggu', 'value' => $menunggu, 'hint' => 'Perlu ditinjau terlebih dahulu', 'color' => 'text-amber-500'],
            ['label' => 'Disetujui', 'value' => $disetujui, 'hint' => 'Siap masuk ke jadwal konseling', 'color' => 'text-emerald-500'],
            ['label' => 'Ditolak', 'value' => $ditolak, 'hint' => 'Pengajuan yang tidak dilanjutkan', 'color' => 'text-red-500'],
        ];
    @endphp

    <section class="space-y-6">
        <div class="kc-card overflow-hidden">
            <div class="grid gap-6 p-6 lg:grid-cols-[1fr_280px] lg:p-8">
                <div>
                    <p class="text-sm font-semibold text-indigo-500">Workspace Pengajuan</p>
                    <h2 class="mt-3 text-2xl font-bold text-kc-heading md:text-3xl">Pengajuan Konseling</h2>
                    <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-500">
                        Tinjau kebutuhan awal konseli, pilih pengajuan yang perlu diprioritaskan, lalu lanjutkan ke jadwal konseling yang sesuai.
                    </p>
                </div>
                <div class="rounded-xl border border-amber-100 bg-amber-50 p-5">
                    <p class="text-sm font-semibold text-amber-600">Butuh Tinjauan</p>
                    <p class="mt-2 text-3xl font-bold text-kc-heading">{{ $menunggu }}</p>
                    <p class="mt-1 text-sm leading-6 text-slate-500">Pengajuan menunggu keputusan Anda.</p>
                </div>
            </div>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ($summaryCards as $card)
                <article class="kc-card p-5">
                    <p class="text-sm text-slate-400">{{ $card['label'] }}</p>
                    <p class="mt-3 text-2xl font-bold {{ $card['color'] }}">{{ $card['value'] }}</p>
                    <p class="mt-2 text-sm leading-6 text-slate-500">{{ $card['hint'] }}</p>
                </article>
            @endforeach
        </div>

        <div class="grid gap-6 xl:grid-cols-[1fr_320px]">
            <article class="kc-card p-6">
                <h2 class="text-lg font-semibold text-kc-heading">Alur Kerja Konselor</h2>
                <div class="mt-5 grid gap-4 md:grid-cols-3">
                    <div class="rounded-xl border border-slate-100 p-4">
                        <p class="text-sm font-semibold text-kc-heading">1. Baca Keluhan</p>
                        <p class="mt-2 text-sm leading-6 text-slate-500">Pahami ringkasan kondisi awal konseli sebelum mengambil keputusan.</p>
                    </div>
                    <div class="rounded-xl border border-slate-100 p-4">
                        <p class="text-sm font-semibold text-kc-heading">2. Tentukan Aksi</p>
                        <p class="mt-2 text-sm leading-6 text-slate-500">Gunakan tombol detail, setujui, atau tolak sesuai kesiapan jadwal.</p>
                    </div>
                    <div class="rounded-xl border border-slate-100 p-4">
                        <p class="text-sm font-semibold text-kc-heading">3. Pantau Jadwal</p>
                        <p class="mt-2 text-sm leading-6 text-slate-500">Pengajuan yang disetujui dapat dilanjutkan ke konseling terjadwal.</p>
                    </div>
                </div>
            </article>

            <article class="kc-card p-6">
                <h2 class="text-lg font-semibold text-kc-heading">Aksi Cepat</h2>
                <div class="mt-5 space-y-3">
                    <a href="{{ route('konselor.jadwal') }}"
                        class="block rounded-xl border border-indigo-100 bg-indigo-50 px-4 py-3 text-sm font-semibold text-indigo-600 transition hover:-translate-y-0.5 hover:shadow-md hover:shadow-indigo-100">
                        Lihat Jadwal Konseling
                    </a>
                    <a href="{{ route('konselor.riwayat') }}"
                        class="block rounded-xl border border-slate-100 px-4 py-3 text-sm font-semibold text-kc-heading transition hover:-translate-y-0.5 hover:bg-slate-50">
                        Cek Riwayat Konseling
                    </a>
                </div>
            </article>
        </div>

        <article class="kc-card">
            <div class="border-b border-slate-100 px-6 py-5">
                <h2 class="text-lg font-semibold text-kc-heading">Daftar Pengajuan Terbaru</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[760px] text-left text-sm">
                    <thead class="text-xs uppercase tracking-wide text-slate-400">
                        <tr>
                            <th class="px-6 py-4 font-semibold">Nama Konseli</th>
                            <th class="px-6 py-4 font-semibold">Tanggal Pengajuan</th>
                            <th class="px-6 py-4 font-semibold">Keluhan Awal</th>
                            <th class="px-6 py-4 font-semibold">Status</th>
                            <th class="px-6 py-4 font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($pengajuanTerbaru as $item)
                            <tr>
                                <td class="px-6 py-4 font-semibold text-kc-heading">{{ $item['nama'] }}</td>
                                <td class="px-6 py-4 text-slate-500">{{ $formatDate($item['tanggal']) }}</td>
                                <td class="px-6 py-4 text-slate-500">{{ $item['keluhan'] }}</td>
                                <td class="px-6 py-4"><x-dashboard.status-badge :status="$item['status']" /></td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-2">
                                        <button type="button"
                                            class="rounded-md border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-600">Detail</button>
                                        <button type="button"
                                            class="rounded-md bg-emerald-500 px-3 py-1.5 text-xs font-semibold text-white">Setujui</button>
                                        <button type="button"
                                            class="rounded-md bg-red-500 px-3 py-1.5 text-xs font-semibold text-white">Tolak</button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-slate-400">
                                    Belum ada pengajuan masuk.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </article>
    </section>
</x-app-layout>
