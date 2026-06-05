<x-app-layout>
    <x-slot name="dashboardRole">konseli</x-slot>
    <x-slot name="headerTitle">Jadwal Konseling</x-slot>

    @php
        $formatDate = fn ($value) => \Illuminate\Support\Carbon::parse($value)->translatedFormat('d M Y');
    @endphp

    <section class="space-y-6">
        <x-dashboard.page-header
            title="Jadwal Konseling"
            subtitle="Lihat jadwal mendatang, jadwal selesai, dan jadwal yang masih menunggu konfirmasi."
        />

        <article class="kc-card">
            <div class="border-b border-slate-100 px-6 py-5">
                <h2 class="text-lg font-semibold text-kc-heading">Daftar Jadwal</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[720px] text-left text-sm">
                    <thead class="text-xs uppercase tracking-wide text-slate-400">
                        <tr>
                            <th class="px-6 py-4 font-semibold">Tanggal</th>
                            <th class="px-6 py-4 font-semibold">Jam</th>
                            <th class="px-6 py-4 font-semibold">Konselor</th>
                            <th class="px-6 py-4 font-semibold">Status</th>
                            <th class="px-6 py-4 font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($jadwals as $jadwal)
                            <tr>
                                <td class="px-6 py-4">{{ $formatDate($jadwal['tanggal']) }}</td>
                                <td class="px-6 py-4">{{ $jadwal['jam'] }}</td>
                                <td class="px-6 py-4 font-semibold text-kc-heading">{{ $jadwal['konselor'] }}</td>
                                <td class="px-6 py-4"><x-dashboard.status-badge :status="$jadwal['status']" /></td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('konseli.riwayat') }}" class="rounded-md border border-indigo-200 px-3 py-1.5 text-xs font-semibold text-indigo-600">Lihat Riwayat</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </article>
    </section>
</x-app-layout>
