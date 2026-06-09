<x-app-layout>
    <x-slot name="dashboardRole">konselor</x-slot>
    <x-slot name="headerTitle">Jadwal Konseling</x-slot>

    <section class="space-y-6">
        <x-dashboard.page-header title="Jadwal Konseling"
            subtitle="Pantau sesi hari ini dan persiapkan konseling yang akan berlangsung." />

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            @forelse ($jadwalHariIni as $jadwal)
                <article class="kc-card p-6">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-sm text-slate-400">Sesi Konseling</p>
                            <h2 class="mt-2 text-lg font-semibold text-kc-heading">{{ $jadwal['nama'] }}</h2>
                            <p class="mt-3 text-2xl font-bold text-indigo-500">{{ $jadwal['jam'] }}</p>
                        </div>
                        <x-dashboard.status-badge :status="$jadwal['status']" />
                    </div>
                    <button type="button"
                        class="mt-6 w-full rounded-md bg-indigo-500 px-4 py-2.5 text-sm font-semibold text-white shadow-sm">
                        Lihat Detail
                    </button>
                </article>
            @empty
                <div class="col-span-3 rounded-lg border border-slate-100 px-6 py-10 text-center text-slate-400">
                    Tidak ada jadwal konseling hari ini.
                </div>
            @endforelse
        </div>
    </section>
</x-app-layout>
