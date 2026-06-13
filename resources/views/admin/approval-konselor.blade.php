<x-app-layout>
    <x-slot name="dashboardRole">admin</x-slot>
    <x-slot name="headerTitle">Approval Konselor</x-slot>

    @php
        $formatDate = fn ($value) => \Illuminate\Support\Carbon::parse($value)->translatedFormat('d M Y');
    @endphp

    <section class="space-y-6">
        <x-dashboard.page-header
            title="Approval Konselor"
            subtitle="Tinjau pengajuan calon konselor dan tentukan status approval."
        />

        <article class="kc-card">
            <div class="flex flex-wrap items-center justify-between gap-4 border-b border-slate-100 px-6 py-5">
                <div>
                    <h2 class="text-lg font-semibold text-kc-heading">Pengajuan Menunggu</h2>
                    <p class="mt-1 text-sm text-slate-500">Pengajuan yang belum diproses oleh admin.</p>
                </div>
                <x-dashboard.status-badge status="pending" />
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[980px] text-left text-sm">
                    <thead class="text-xs uppercase tracking-wide text-slate-400">
                        <tr>
                            <th class="px-6 py-4 font-semibold">Nama</th>
                            <th class="px-6 py-4 font-semibold">Email</th>
                            <th class="px-6 py-4 font-semibold">Asal</th>
                            <th class="px-6 py-4 font-semibold">No HP</th>
                            <th class="px-6 py-4 font-semibold">Tanggal</th>
                            <th class="px-6 py-4 font-semibold">Status</th>
                            <th class="px-6 py-4 font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($konselorPending as $item)
                            <tr>
                                <td class="px-6 py-4 font-semibold text-kc-heading">{{ $item['nama'] }}</td>
                                <td class="px-6 py-4 text-slate-500">{{ $item['email'] }}</td>
                                <td class="px-6 py-4 text-slate-500">{{ $item['asal'] }}</td>
                                <td class="px-6 py-4 text-slate-500">{{ $item['no_hp'] }}</td>
                                <td class="px-6 py-4 text-slate-500">{{ $formatDate($item['tanggal']) }}</td>
                                <td class="px-6 py-4"><x-dashboard.status-badge :status="$item['status']" /></td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-2">
                                        <a href="{{ route('admin.konselor.show', $item['id']) }}" class="inline-flex whitespace-nowrap rounded-md border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-600 transition hover:bg-slate-50">Detail</a>
                                        <form method="POST" action="{{ route('admin.konselor.approve', $item['id']) }}">
                                            @csrf
                                            <button class="rounded-md bg-emerald-500 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-emerald-600">Setujui</button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.konselor.reject', $item['id']) }}">
                                            @csrf
                                            <button class="rounded-md bg-red-500 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-red-600">Tolak</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </article>
    </section>
</x-app-layout>
