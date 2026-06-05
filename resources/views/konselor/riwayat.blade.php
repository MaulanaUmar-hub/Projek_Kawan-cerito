<x-app-layout>
    <x-slot name="dashboardRole">konselor</x-slot>
    <x-slot name="headerTitle">Riwayat Konseling</x-slot>

    @php
        $formatDate = fn ($value) => \Illuminate\Support\Carbon::parse($value)->translatedFormat('d M Y');
    @endphp

    <section class="space-y-6">
        <x-dashboard.page-header
            title="Riwayat Konseling"
            subtitle="Lihat sesi yang sudah berjalan beserta ringkasan hasil konseling."
        />

        <article class="kc-card">
            <div class="border-b border-slate-100 px-6 py-5">
                <h2 class="text-lg font-semibold text-kc-heading">Riwayat Sesi</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[760px] text-left text-sm">
                    <thead class="text-xs uppercase tracking-wide text-slate-400">
                        <tr>
                            <th class="px-6 py-4 font-semibold">Tanggal</th>
                            <th class="px-6 py-4 font-semibold">Nama Konseli</th>
                            <th class="px-6 py-4 font-semibold">Status</th>
                            <th class="px-6 py-4 font-semibold">Hasil Konseling</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($riwayatKonseling as $item)
                            <tr>
                                <td class="px-6 py-4 text-slate-500">{{ $formatDate($item['tanggal']) }}</td>
                                <td class="px-6 py-4 font-semibold text-kc-heading">{{ $item['nama'] }}</td>
                                <td class="px-6 py-4"><x-dashboard.status-badge :status="$item['status']" /></td>
                                <td class="px-6 py-4 text-slate-500">{{ $item['hasil'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </article>
    </section>
</x-app-layout>
