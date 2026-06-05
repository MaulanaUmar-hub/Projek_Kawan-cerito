<x-app-layout>
    <x-slot name="dashboardRole">konseli</x-slot>
    <x-slot name="headerTitle">Riwayat Konseling</x-slot>

    @php
        $formatDate = fn ($value) => \Illuminate\Support\Carbon::parse($value)->translatedFormat('d M Y');
    @endphp

    <section class="space-y-6">
        <x-dashboard.page-header
            title="Riwayat Konseling"
            subtitle="Pantau daftar pengajuan, jadwal, dan hasil konselingmu dalam satu tempat."
        />

        <article class="kc-card">
            <div class="border-b border-slate-100 px-6 py-5">
                <h2 class="text-lg font-semibold text-kc-heading">Daftar Riwayat</h2>
            </div>

            @if ($riwayat->isEmpty())
                <x-dashboard.empty-state
                    title="Belum ada riwayat konseling"
                    message="Mulai ajukan konseling pertamamu agar kamu bisa mendapatkan dukungan yang sesuai."
                    :action-url="route('konseli.pengajuan')"
                    action-label="Ajukan Konseling"
                />
            @else
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[720px] text-left text-sm">
                        <thead class="text-xs uppercase tracking-wide text-slate-400">
                            <tr>
                                <th class="px-6 py-4 font-semibold">Tanggal</th>
                                <th class="px-6 py-4 font-semibold">Konselor</th>
                                <th class="px-6 py-4 font-semibold">Jenis Konseling</th>
                                <th class="px-6 py-4 font-semibold">Status</th>
                                <th class="px-6 py-4 font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($riwayat as $item)
                                <tr>
                                    <td class="px-6 py-4">{{ $formatDate($item['tanggal']) }}</td>
                                    <td class="px-6 py-4 font-semibold text-kc-heading">{{ $item['konselor'] }}</td>
                                    <td class="px-6 py-4">{{ $item['jenis'] }}</td>
                                    <td class="px-6 py-4"><x-dashboard.status-badge :status="$item['status']" /></td>
                                    <td class="px-6 py-4">
                                        <a href="#detail-riwayat" class="rounded-md border border-indigo-200 px-3 py-1.5 text-xs font-semibold text-indigo-600">Lihat Detail</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </article>
    </section>
</x-app-layout>
