<x-app-layout>
    <x-slot name="dashboardRole">konselor</x-slot>
    <x-slot name="headerTitle">Jadwal Konseling</x-slot>

    <section class="space-y-6">
        <x-dashboard.page-header
            title="Jadwal Konseling"
            subtitle="Pantau sesi hari ini dan persiapkan konseling yang akan berlangsung."
        />

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            @foreach ($jadwalHariIni as $jadwal)
                <article class="kc-card p-6">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-sm text-slate-400">Sesi Konseling</p>
                            <h2 class="mt-2 text-lg font-semibold text-kc-heading">{{ $jadwal['nama'] }}</h2>
                            <p class="mt-3 text-2xl font-bold text-indigo-500">{{ $jadwal['jam'] }}</p>
                        </div>
                        <x-dashboard.status-badge :status="$jadwal['status']" />
                    </div>
                    <button type="button" class="mt-6 w-full rounded-md bg-indigo-500 px-4 py-2.5 text-sm font-semibold text-white shadow-sm">
                        Lihat Detail
                    </button>
                </article>
            @endforeach
        </div>

        <x-dashboard.form-card title="Catatan Jadwal" description="Gunakan halaman ini sebagai ringkasan jadwal konseling konselor. Data backend dapat dihubungkan bertahap tanpa mengubah layout.">
            <div class="rounded-lg border border-indigo-100 bg-indigo-50 px-4 py-3 text-sm text-indigo-700">
                Jadwal yang sedang ditampilkan masih menggunakan data sementara agar frontend dapat diuji dengan nyaman.
            </div>
        </x-dashboard.form-card>
    </section>
</x-app-layout>
