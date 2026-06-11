<x-app-layout>
    <x-slot name="dashboardRole">konselor</x-slot>
    <x-slot name="headerTitle">Jadwal Konseling</x-slot>

    <section class="space-y-6">
        <x-dashboard.page-header title="Jadwal Konseling"
            subtitle="Jadwal resmi yang sudah dikonfirmasi, dan pengajuan yang masih perlu ditindaklanjuti." />

        @if (session('success'))
            <div class="kc-card border-emerald-100 bg-emerald-50 p-4 text-sm font-medium text-emerald-700">
                {{ session('success') }}</div>
        @endif

        <div class="grid gap-6 xl:grid-cols-[1fr_360px]">
            {{-- Jadwal mendatang --}}
            <article class="kc-card">
                <div class="border-b border-slate-100 px-6 py-5">
                    <h2 class="text-lg font-semibold text-kc-heading">Jadwal Mendatang</h2>
                    <p class="mt-1 text-sm text-slate-400">Pengajuan yang sudah disetujui dan memiliki jadwal resmi.</p>
                </div>

                @php
                    $hari = $jadwalMendatang->groupBy(
                        fn($j) => \Illuminate\Support\Carbon::parse($j->tanggal)->toDateString(),
                    );
                @endphp

                @if ($hari->isEmpty())
                    <div class="px-6 py-12 text-center text-slate-400">
                        Tidak ada jadwal mendatang.
                        <a href="{{ route('konselor.pengajuan') }}"
                            class="ml-1 font-semibold text-indigo-500 underline">Tinjau pengajuan</a>
                    </div>
                @else
                    <div class="divide-y divide-slate-100">
                        @foreach ($hari as $tanggal => $items)
                            <div class="px-6 py-4">
                                <p class="mb-3 text-xs font-bold uppercase tracking-wide text-slate-400">
                                    {{ \Illuminate\Support\Carbon::parse($tanggal)->isToday() ? 'Hari Ini — ' : '' }}
                                    {{ \Illuminate\Support\Carbon::parse($tanggal)->translatedFormat('l, d M Y') }}
                                </p>
                                <div class="space-y-3">
                                    @foreach ($items as $jadwal)
                                        @php $pengajuan = $jadwal->pengajuan; @endphp
                                        <div
                                            class="flex items-center justify-between gap-4 rounded-xl border border-slate-100 p-4">
                                            <div>
                                                <p class="text-sm font-semibold text-kc-heading">
                                                    {{ $pengajuan?->konseli?->user?->nama ?? '(Tanpa konseli)' }}
                                                </p>
                                                <p class="mt-0.5 text-xs text-slate-400">
                                                    {{ \Illuminate\Support\Carbon::parse($jadwal->jam)->format('H:i') }}
                                                    —
                                                    {{ ucfirst($jadwal->tipe_konseling) }}
                                                </p>
                                            </div>
                                            <x-dashboard.status-badge :status="$pengajuan?->status_pengajuan ?? $jadwal->status_jadwal" />
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </article>

            {{-- Pengajuan perlu respons --}}
            <aside class="space-y-5">
                <article class="kc-card">
                    <div class="border-b border-slate-100 px-6 py-5">
                        <h2 class="text-base font-semibold text-kc-heading">Perlu Ditindaklanjuti</h2>
                        <p class="mt-1 text-xs text-slate-400">Pengajuan menunggu atau sedang di-reschedule.</p>
                    </div>
                    <div class="divide-y divide-slate-100">
                        @forelse ($pengajuanMenunggu as $p)
                            <div class="p-5">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="min-w-0">
                                        <p class="text-sm font-semibold text-kc-heading">
                                            {{ $p->konseli?->user?->nama ?? '-' }}</p>
                                        <p class="mt-1 text-xs text-slate-400">
                                            Usulan:
                                            {{ \Illuminate\Support\Carbon::parse($p->tanggal_usulan)->translatedFormat('d M Y') }},
                                            {{ \Illuminate\Support\Carbon::parse($p->jam_usulan)->format('H:i') }}
                                        </p>
                                        @if ($p->status_pengajuan === 'reschedule')
                                            <p class="mt-1 text-xs text-amber-600">
                                                Reschedule ke:
                                                {{ \Illuminate\Support\Carbon::parse($p->tanggal_reschedule)->translatedFormat('d M Y') }},
                                                {{ \Illuminate\Support\Carbon::parse($p->jam_reschedule)->format('H:i') }}
                                            </p>
                                        @endif
                                    </div>
                                    <x-dashboard.status-badge :status="$p->status_pengajuan" />
                                </div>
                                <a href="{{ route('konselor.pengajuan') }}"
                                    class="mt-3 block rounded-lg border border-indigo-100 px-3 py-2 text-center text-xs font-semibold text-indigo-600 hover:bg-indigo-50">
                                    Tinjau →
                                </a>
                            </div>
                        @empty
                            <p class="px-5 py-8 text-center text-sm text-slate-400">Semua pengajuan sudah ditangani.</p>
                        @endforelse
                    </div>
                </article>
            </aside>
        </div>
    </section>
</x-app-layout>
