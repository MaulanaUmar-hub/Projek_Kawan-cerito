<x-app-layout>
    <x-slot name="dashboardRole">konselor</x-slot>
    <x-slot name="headerTitle">Jadwal Konseling</x-slot>

    @php
        $jadwal = collect($jadwalHariIni);
        $totalJadwal = $jadwal->count();
        $aktif = $jadwal->whereIn('status', ['aktif', 'disetujui', 'berlangsung'])->count();
        $menunggu = $jadwal->whereIn('status', ['menunggu', 'pending', 'baru'])->count();
    @endphp

    <section class="space-y-6">
        <div class="kc-card overflow-hidden">
            <div class="grid gap-6 p-6 lg:grid-cols-[1fr_280px] lg:p-8">
                <div>
                    <p class="text-sm font-semibold text-indigo-500">Agenda Konselor</p>
                    <h2 class="mt-3 text-2xl font-bold text-kc-heading md:text-3xl">Jadwal Konseling</h2>
                    <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-500">
                        Pantau konseling hari ini, cek statusnya, dan siapkan catatan pendek sebelum bertemu konseli.
                    </p>
                </div>
                <div class="rounded-xl border border-indigo-100 bg-indigo-50 p-5">
                    <p class="text-sm font-semibold text-indigo-600">Konseling Hari Ini</p>
                    <p class="mt-2 text-3xl font-bold text-kc-heading">{{ $totalJadwal }}</p>
                    <p class="mt-1 text-sm leading-6 text-slate-500">Agenda yang perlu dipantau hari ini.</p>
                </div>
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-3">
            <article class="kc-card p-5">
                <p class="text-sm text-slate-400">Total Jadwal</p>
                <p class="mt-3 text-2xl font-bold text-kc-heading">{{ $totalJadwal }}</p>
                <p class="mt-2 text-sm text-slate-500">Konseling terdaftar hari ini.</p>
            </article>
            <article class="kc-card p-5">
                <p class="text-sm text-slate-400">Siap Berlangsung</p>
                <p class="mt-3 text-2xl font-bold text-indigo-500">{{ $aktif }}</p>
                <p class="mt-2 text-sm text-slate-500">Sudah disetujui atau aktif.</p>
            </article>
            <article class="kc-card p-5">
                <p class="text-sm text-slate-400">Menunggu</p>
                <p class="mt-3 text-2xl font-bold text-amber-500">{{ $menunggu }}</p>
                <p class="mt-2 text-sm text-slate-500">Masih perlu konfirmasi.</p>
            </article>
        </div>

        <div class="grid gap-6 xl:grid-cols-[1fr_320px]">
            <article class="kc-card p-6">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-kc-heading">Agenda Hari Ini</h2>
                        <p class="mt-1 text-sm text-slate-500">Urutkan fokus dari konseling terdekat dan pastikan catatan siap.</p>
                    </div>
                    <a href="{{ route('konselor.pengajuan') }}"
                        class="inline-flex justify-center rounded-xl border border-indigo-100 px-4 py-2 text-sm font-semibold text-indigo-600 transition hover:bg-indigo-50">
                        Lihat Pengajuan
                    </a>
                </div>

                <div class="mt-5 grid grid-cols-1 gap-4 lg:grid-cols-2">
                    @forelse ($jadwalHariIni as $jadwal)
                        <article class="rounded-xl border border-slate-100 p-5">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="text-sm text-slate-400">Konseli</p>
                                    <h3 class="mt-2 text-lg font-semibold text-kc-heading">{{ $jadwal['nama'] }}</h3>
                                    <p class="mt-3 text-2xl font-bold text-indigo-500">{{ $jadwal['jam'] }}</p>
                                </div>
                                <x-dashboard.status-badge :status="$jadwal['status']" />
                            </div>
                            <button type="button"
                                class="mt-5 w-full rounded-md bg-indigo-500 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-600">
                                Lihat Detail
                            </button>
                        </article>
                    @empty
                        <div class="rounded-xl border border-slate-100 px-6 py-10 text-center text-slate-400 lg:col-span-2">
                            Tidak ada jadwal konseling hari ini.
                        </div>
                    @endforelse
                </div>
            </article>

            <aside class="kc-card p-6">
                <h2 class="text-lg font-semibold text-kc-heading">Persiapan Konseling</h2>
                <div class="mt-5 space-y-4">
                    <div class="rounded-xl bg-slate-50 p-4">
                        <p class="text-sm font-semibold text-kc-heading">Cek konteks konseli</p>
                        <p class="mt-2 text-sm leading-6 text-slate-500">Baca pengajuan dan keluhan awal sebelum jadwal dimulai.</p>
                    </div>
                    <div class="rounded-xl bg-slate-50 p-4">
                        <p class="text-sm font-semibold text-kc-heading">Siapkan catatan</p>
                        <p class="mt-2 text-sm leading-6 text-slate-500">Tulis poin observasi agar hasil konseling lebih mudah diisi.</p>
                    </div>
                    <div class="rounded-xl bg-slate-50 p-4">
                        <p class="text-sm font-semibold text-kc-heading">Tutup dengan arahan</p>
                        <p class="mt-2 text-sm leading-6 text-slate-500">Berikan langkah kecil yang jelas setelah konseling selesai.</p>
                    </div>
                </div>
            </aside>
        </div>
    </section>
</x-app-layout>
