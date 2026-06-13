<x-app-layout>
    <x-slot name="dashboardRole">konseli</x-slot>
    <x-slot name="headerTitle">Jadwal Konseling</x-slot>

    <section class="space-y-6">
        <x-dashboard.page-header title="Jadwal Konseling"
            subtitle="Lihat jadwal aktif, konfirmasi reschedule dari konselor, dan pantau status konselingmu." />

        @if (session('success'))
            <div class="kc-card border-emerald-100 bg-emerald-50 p-4 text-sm font-medium text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        @if (session('info'))
            <div class="kc-card border-sky-100 bg-sky-50 p-4 text-sm font-medium text-sky-700">
                {{ session('info') }}
            </div>
        @endif

        {{-- Banner notifikasi reschedule menunggu konfirmasi --}}
        @php
            $adaReschedule = $jadwals->where('status_pengajuan', 'reschedule')->count();
        @endphp
        @if ($adaReschedule > 0)
            <div class="kc-card border-amber-200 bg-amber-50 p-4 text-sm font-medium text-amber-700">
                ⚠ Ada {{ $adaReschedule }} usulan reschedule dari konselor yang menunggu konfirmasimu. Lihat di bawah.
            </div>
        @endif

        <article class="kc-card">
            <div class="border-b border-slate-100 px-6 py-5">
                <h2 class="text-lg font-semibold text-kc-heading">Daftar Jadwal</h2>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse ($jadwals as $p)
                    @php
                        $jadwal = $p->jadwal;
                        $konselorUser = $p->konselor?->user;
                        $tanggal = $jadwal?->tanggal ?? $p->tanggal_usulan;
                        $jam = $jadwal?->jam ?? $p->jam_usulan;
                        $wa = $p->konselor?->link_whatsapp;
                    @endphp

                    <div class="p-6">
                        <div class="flex flex-wrap items-start justify-between gap-4">

                            {{-- Info utama --}}
                            <div class="min-w-0 flex-1">
                                <div class="flex flex-wrap items-center gap-3">
                                    <p class="font-semibold text-kc-heading">
                                        {{ $konselorUser?->nama ?? 'Belum ditentukan' }}
                                    </p>
                                    <x-dashboard.status-badge :status="$p->status_pengajuan" />
                                </div>

                                @if ($p->konselor?->spesialisasi)
                                    <p class="mt-0.5 text-xs text-slate-400">{{ $p->konselor->spesialisasi }}</p>
                                @endif

                                <div class="mt-3 flex flex-wrap gap-5 text-sm">
                                    <div>
                                        <span class="text-xs text-slate-400">Tanggal</span>
                                        <p class="mt-0.5 font-semibold text-kc-heading">
                                            {{ $tanggal ? \Illuminate\Support\Carbon::parse($tanggal)->translatedFormat('d M Y') : '-' }}
                                        </p>
                                    </div>
                                    <div>
                                        <span class="text-xs text-slate-400">Jam</span>
                                        <p class="mt-0.5 font-semibold text-kc-heading">
                                            {{ $jam ? \Illuminate\Support\Carbon::parse($jam)->format('H:i') : '-' }}
                                        </p>
                                    </div>
                                    <div>
                                        <span class="text-xs text-slate-400">Tipe</span>
                                        <p class="mt-0.5 font-semibold text-kc-heading">
                                            {{ ucfirst($p->tipe_konseling_usulan ?? '-') }}
                                        </p>
                                    </div>
                                </div>

                                {{-- Detail usulan reschedule dari konselor --}}
                                @if ($p->status_pengajuan === 'reschedule')
                                    <div class="mt-4 rounded-lg border border-amber-200 bg-amber-50 p-4 text-sm">
                                        <p class="font-semibold text-amber-700">Konselor mengusulkan waktu baru:</p>
                                        <p class="mt-1 text-amber-800">
                                            {{ \Illuminate\Support\Carbon::parse($p->tanggal_reschedule)->translatedFormat('d M Y') }},
                                            {{ \Illuminate\Support\Carbon::parse($p->jam_reschedule)->format('H:i') }}
                                        </p>
                                        @if ($p->catatan_reschedule)
                                            <p class="mt-1 text-slate-500 text-xs">Catatan:
                                                {{ $p->catatan_reschedule }}</p>
                                        @endif
                                    </div>
                                @endif
                            </div>

                            {{-- Aksi --}}
                            <div class="flex shrink-0 flex-col gap-2 pt-1">
                                {{-- Tombol WA — hanya saat disetujui --}}
                                @if ($p->status_pengajuan === 'disetujui' && $wa)
                                    <a href="{{ $wa }}" target="_blank" rel="noopener noreferrer"
                                        class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-600 px-4 py-2 text-xs font-semibold text-white transition hover:bg-emerald-700">
                                        <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z" />
                                            <path
                                                d="M12 0C5.373 0 0 5.373 0 12c0 2.127.558 4.126 1.532 5.86L.057 23.75a.5.5 0 0 0 .614.63l6.094-1.598A11.94 11.94 0 0 0 12 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.9a9.87 9.87 0 0 1-5.034-1.374l-.36-.214-3.733.979.997-3.648-.234-.374A9.861 9.861 0 0 1 2.1 12C2.1 6.534 6.534 2.1 12 2.1c5.466 0 9.9 4.434 9.9 9.9 0 5.466-4.434 9.9-9.9 9.9z" />
                                        </svg>
                                        Hubungi via WhatsApp
                                    </a>
                                @endif

                                {{-- Tombol konfirmasi reschedule --}}
                                @if ($p->status_pengajuan === 'reschedule')
                                    <form method="POST"
                                        action="{{ route('konseli.reschedule.konfirmasi', $p->id_pengajuan) }}">
                                        @csrf
                                        <input type="hidden" name="aksi" value="setuju">
                                        <button type="submit"
                                            class="w-full rounded-lg bg-emerald-600 px-4 py-2 text-xs font-semibold text-white transition hover:bg-emerald-700"
                                            onclick="return confirm('Setujui jadwal reschedule dari konselor?')">
                                            ✓ Setujui Reschedule
                                        </button>
                                    </form>
                                    <form method="POST"
                                        action="{{ route('konseli.reschedule.konfirmasi', $p->id_pengajuan) }}">
                                        @csrf
                                        <input type="hidden" name="aksi" value="tolak">
                                        <button type="submit"
                                            class="w-full rounded-lg border border-red-200 px-4 py-2 text-xs font-semibold text-red-600 transition hover:bg-red-50"
                                            onclick="return confirm('Tolak usulan reschedule? Pengajuan akan kembali ke status menunggu.')">
                                            ✕ Tolak Reschedule
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-12 text-center text-slate-400">
                        Belum ada jadwal konseling.
                    </div>
                @endforelse
            </div>
        </article>
    </section>
</x-app-layout>
