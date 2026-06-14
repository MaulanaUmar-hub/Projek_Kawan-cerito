<x-app-layout>
    <x-slot name="dashboardRole">admin</x-slot>
    <x-slot name="headerTitle">Kelola Konselor</x-slot>

    <section class="space-y-6">
        <x-dashboard.page-header title="Kelola Konselor"
            subtitle="Pantau konselor aktif, menunggu persetujuan, dan ditolak dalam satu halaman rinci." />

        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
            <x-dashboard.stat-card
                label="Menunggu"
                :value="$konselorPending->count()"
                icon="bi bi-hourglass-split"
                color="warning"
            />
            <x-dashboard.stat-card
                label="Aktif"
                :value="$konselorAktif->count()"
                icon="bi bi-patch-check-fill"
                color="success"
            />
            <x-dashboard.stat-card
                label="Ditolak"
                :value="$konselorDitolak->count()"
                icon="bi bi-x-circle-fill"
                color="danger"
            />
        </div>

        <article class="kc-card">
            <div class="border-b border-slate-100 px-6 py-5">
                <h2 class="text-lg font-semibold text-kc-heading">Daftar Konselor</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[1040px] text-left text-sm">
                    <thead class="text-xs uppercase tracking-wide text-slate-400">
                        <tr>
                            <th class="px-6 py-4 font-semibold">Nama</th>
                            <th class="px-6 py-4 font-semibold">Email</th>
                            <th class="px-6 py-4 font-semibold">Spesialisasi</th>
                            <th class="px-6 py-4 font-semibold">No HP</th>
                            <th class="px-6 py-4 font-semibold">Status</th>
                            <th class="px-6 py-4 font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($konselorPending->merge($konselorAktif)->merge($konselorDitolak) as $item)
                            <tr>
                                <td class="px-6 py-4 font-semibold text-kc-heading">{{ $item['nama'] }}</td>
                                <td class="px-6 py-4 text-slate-500">{{ $item['email'] }}</td>
                                <td class="px-6 py-4 text-slate-500">{{ $item['spesialisasi'] }}</td>
                                <td class="px-6 py-4 text-slate-500">{{ $item['no_hp'] }}</td>
                                <td class="px-6 py-4"><x-dashboard.status-badge :status="$item['status']" /></td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <a href="{{ route('admin.konselor.show', $item['id']) }}"
                                            class="inline-flex whitespace-nowrap rounded-md border border-indigo-200 px-3 py-1.5 text-xs font-semibold text-indigo-600 transition hover:bg-indigo-50">
                                            Lihat Detail
                                        </a>

                                        @if ($item['status'] === 'pending')
                                            <form method="POST" action="{{ route('admin.konselor.approve', $item['id']) }}"
                                                onsubmit="return confirm('Setujui pengajuan konselor ini?')">
                                                @csrf
                                                <button type="submit"
                                                    class="inline-flex whitespace-nowrap rounded-md bg-emerald-100 px-3 py-1.5 text-xs font-semibold text-emerald-700 transition hover:bg-emerald-200">
                                                    Setujui
                                                </button>
                                            </form>

                                            <form method="POST" action="{{ route('admin.konselor.reject', $item['id']) }}"
                                                onsubmit="return confirm('Tolak pengajuan konselor ini?')">
                                                @csrf
                                                <button type="submit"
                                                    class="inline-flex whitespace-nowrap rounded-md bg-red-100 px-3 py-1.5 text-xs font-semibold text-red-700 transition hover:bg-red-200">
                                                    Tolak
                                                </button>
                                            </form>
                                        @endif
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
