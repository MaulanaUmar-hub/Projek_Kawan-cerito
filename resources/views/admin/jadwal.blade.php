<x-app-layout>
    <x-slot name="dashboardRole">admin</x-slot>
    <x-slot name="headerTitle">Kelola Jadwal</x-slot>

    @php
        $formatDate = fn ($value) => \Illuminate\Support\Carbon::parse($value)->translatedFormat('d M Y');
    @endphp

    <section class="space-y-6">
        <x-dashboard.page-header
            title="Kelola Jadwal"
            subtitle="Pantau jadwal konseling yang tersedia, aktif, dan sedang menunggu."
        />

        <article class="kc-card">
            <div class="border-b border-slate-100 px-6 py-5">
                <h2 class="text-lg font-semibold text-kc-heading">Jadwal Konseling</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[860px] text-left text-sm">
                    <thead class="text-xs uppercase tracking-wide text-slate-400">
                        <tr>
                            <th class="px-6 py-4 font-semibold">Tanggal</th>
                            <th class="px-6 py-4 font-semibold">Jam</th>
                            <th class="px-6 py-4 font-semibold">Konseli</th>
                            <th class="px-6 py-4 font-semibold">Konselor</th>
                            <th class="px-6 py-4 font-semibold">Tipe</th>
                            <th class="px-6 py-4 font-semibold">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($jadwal as $item)
                            <tr>
                                <td class="px-6 py-4 text-slate-500">{{ $formatDate($item['tanggal']) }}</td>
                                <td class="px-6 py-4 font-semibold text-kc-heading">{{ $item['jam'] }}</td>
                                <td class="px-6 py-4 text-slate-500">{{ $item['konseli'] }}</td>
                                <td class="px-6 py-4 text-slate-500">{{ $item['konselor'] }}</td>
                                <td class="px-6 py-4 text-slate-500">{{ $item['tipe'] }}</td>
                                <td class="px-6 py-4"><x-dashboard.status-badge :status="$item['status']" /></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </article>
    </section>
</x-app-layout>
