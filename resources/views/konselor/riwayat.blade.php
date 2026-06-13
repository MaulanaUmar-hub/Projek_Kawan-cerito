<x-app-layout>
    <x-slot name="dashboardRole">konselor</x-slot>
    <x-slot name="headerTitle">Riwayat Konseling</x-slot>

    @php
        $formatDate = fn ($value) => \Illuminate\Support\Carbon::parse($value)->translatedFormat('d M Y');
        $riwayat = collect($riwayatKonseling);
        $totalRiwayat = $riwayat->count();
        $selesai = $riwayat->where('status', 'selesai')->count();
        $berlangsung = $riwayat->whereIn('status', ['aktif', 'berlangsung', 'disetujui'])->count();
        $menunggu = $riwayat->whereIn('status', ['menunggu', 'pending', 'baru'])->count();
    @endphp

    <section class="space-y-6">
        <div class="kc-card overflow-hidden">
            <div class="grid gap-6 p-6 lg:grid-cols-[1fr_280px] lg:p-8">
                <div>
                    <p class="text-sm font-semibold text-indigo-500">Dokumentasi Konseling</p>
                    <h2 class="mt-3 text-2xl font-bold text-kc-heading md:text-3xl">Rekap Konseling</h2>
                    <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-500">
                        Telusuri konseling yang pernah ditangani, cek statusnya, dan gunakan hasil konseling sebagai bahan tindak lanjut.
                    </p>
                </div>
                <div class="rounded-xl border border-sky-100 bg-sky-50 p-5">
                    <p class="text-sm font-semibold text-sky-600">Konseling Selesai</p>
                    <p class="mt-2 text-3xl font-bold text-kc-heading">{{ $selesai }}</p>
                    <p class="mt-1 text-sm leading-6 text-slate-500">Data yang sudah memiliki akhir proses.</p>
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
                <p class="text-sm text-slate-400">Menunggu</p>
                <p class="mt-3 text-2xl font-bold text-amber-500">{{ $menunggu }}</p>
            </article>
        </div>

        <div class="grid gap-6 xl:grid-cols-[1fr_320px]">
            <article class="kc-card">
                <div class="border-b border-slate-100 px-6 py-5">
                    <h2 class="text-lg font-semibold text-kc-heading">Daftar Riwayat Konseling</h2>
                    <p class="mt-1 text-sm text-slate-500">Ringkasan konseling yang pernah atau sedang Anda tangani.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[760px] text-left text-sm">
                        <thead class="text-xs uppercase tracking-wide text-slate-400">
                            <tr>
                                <th class="px-6 py-4 font-semibold">Tanggal</th>
                                <th class="px-6 py-4 font-semibold">Nama Konseli</th>
                                <th class="px-6 py-4 font-semibold">Status</th>
                                <th class="px-6 py-4 font-semibold">Hasil Konseling</th>
                                <th class="px-6 py-4 font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($riwayatKonseling as $item)
                                <tr>
                                    <td class="px-6 py-4 text-slate-500">{{ $formatDate($item['tanggal']) }}</td>
                                    <td class="px-6 py-4 font-semibold text-kc-heading">{{ $item['nama'] }}</td>
                                    <td class="px-6 py-4"><x-dashboard.status-badge :status="$item['status']" /></td>
                                    <td class="px-6 py-4 text-slate-500">{{ $item['hasil'] }}</td>
                                    <td class="px-6 py-4">
                                        <button type="button"
                                            class="rounded-md border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-600 transition hover:bg-slate-50">
                                            Lihat Detail
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center text-slate-400">
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
                        Riwayat membantu konselor menjaga kesinambungan pendampingan dan melihat pola kebutuhan konseli dari waktu ke waktu.
                    </p>
                </article>
            </aside>
        </div>
    </section>
</x-app-layout>
