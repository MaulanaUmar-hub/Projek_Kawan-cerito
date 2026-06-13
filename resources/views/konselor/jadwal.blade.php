<x-app-layout>
    <x-slot name="dashboardRole">konselor</x-slot>
    <x-slot name="headerTitle">Jadwal Konseling</x-slot>

    <section class="space-y-6">
        <x-dashboard.page-header title="Jadwal Konseling"
            subtitle="Jadwal resmi yang sudah dikonfirmasi. Selesaikan sesi dan input hasil konseling di sini." />

        @if (session('success'))
            <div class="kc-card border-emerald-100 bg-emerald-50 p-4 text-sm font-medium text-emerald-700">
                {{ session('success') }}
            </div>
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
                                <div class="space-y-4">
                                    @foreach ($items as $jadwal)
                                        @php $pengajuan = $jadwal->pengajuan; @endphp
                                        <div class="rounded-xl border border-slate-100 p-4">
                                            {{-- Header card jadwal --}}
                                            <div class="flex items-start justify-between gap-4">
                                                <div>
                                                    <p class="text-sm font-semibold text-kc-heading">
                                                        {{ $pengajuan?->konseli?->user?->nama ?? '(Tanpa konseli)' }}
                                                    </p>
                                                    <p class="mt-0.5 text-xs text-slate-400">
                                                        {{ \Illuminate\Support\Carbon::parse($jadwal->jam)->format('H:i') }}
                                                        — {{ ucfirst($jadwal->tipe_konseling) }}
                                                    </p>
                                                    {{-- Nomor WA konseli --}}
                                                    @if ($pengajuan?->konseli?->no_hp)
                                                        <a href="https://wa.me/{{ preg_replace('/^0/', '62', $pengajuan->konseli->no_hp) }}"
                                                            target="_blank" rel="noopener noreferrer"
                                                            class="mt-1.5 inline-flex items-center gap-1 text-xs font-semibold text-emerald-600 hover:underline">
                                                            <svg class="h-3 w-3" fill="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path
                                                                    d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z" />
                                                                <path
                                                                    d="M12 0C5.373 0 0 5.373 0 12c0 2.127.558 4.126 1.532 5.86L.057 23.75a.5.5 0 0 0 .614.63l6.094-1.598A11.94 11.94 0 0 0 12 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.9a9.87 9.87 0 0 1-5.034-1.374l-.36-.214-3.733.979.997-3.648-.234-.374A9.861 9.861 0 0 1 2.1 12C2.1 6.534 6.534 2.1 12 2.1c5.466 0 9.9 4.434 9.9 9.9 0 5.466-4.434 9.9-9.9 9.9z" />
                                                            </svg>
                                                            {{ $pengajuan->konseli->no_hp }}
                                                        </a>
                                                    @endif
                                                </div>
                                                <x-dashboard.status-badge :status="$pengajuan?->status_pengajuan ?? $jadwal->status_jadwal" />
                                            </div>

                                            {{-- Tombol selesaikan sesi — hanya jika status disetujui --}}
                                            @if ($pengajuan && $pengajuan->status_pengajuan === 'disetujui')
                                                <div class="mt-3">
                                                    <button type="button"
                                                        onclick="document.getElementById('form-selesai-{{ $pengajuan->id_pengajuan }}').classList.toggle('hidden')"
                                                        class="rounded-lg bg-sky-600 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-sky-700">
                                                        ✓ Selesaikan Sesi &amp; Input Hasil
                                                    </button>
                                                </div>

                                                <form id="form-selesai-{{ $pengajuan->id_pengajuan }}" method="POST"
                                                    action="{{ route('konselor.pengajuan.selesai', $pengajuan->id_pengajuan) }}"
                                                    class="mt-4 hidden rounded-xl border border-sky-100 bg-sky-50 p-4">
                                                    @csrf
                                                    <p class="mb-3 text-xs font-semibold text-sky-700">Input Hasil
                                                        Konseling</p>
                                                    <div class="space-y-3">
                                                        <div>
                                                            <label
                                                                class="mb-1 block text-xs font-medium text-slate-500">
                                                                Catatan Konseling <span class="text-red-400">*</span>
                                                            </label>
                                                            <textarea name="catatan_konseling" rows="4" required
                                                                placeholder="Ringkasan jalannya sesi, permasalahan yang dibahas, dan perkembangan konseli..."
                                                                class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm outline-none focus:border-sky-400 focus:ring-4 focus:ring-sky-100"></textarea>
                                                        </div>
                                                        <div>
                                                            <label
                                                                class="mb-1 block text-xs font-medium text-slate-500">
                                                                Rekomendasi Tindak Lanjut <span
                                                                    class="text-slate-300">(opsional)</span>
                                                            </label>
                                                            <textarea name="rekomendasi" rows="2"
                                                                placeholder="Misal: Disarankan sesi lanjutan, dirujuk ke psikolog klinis, dsb."
                                                                class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm outline-none focus:border-sky-400 focus:ring-4 focus:ring-sky-100"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="mt-3 flex gap-2">
                                                        <button type="submit"
                                                            onclick="return confirm('Selesaikan sesi ini? Status tidak dapat diubah kembali.')"
                                                            class="rounded-lg bg-sky-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-sky-700">
                                                            Simpan &amp; Selesaikan
                                                        </button>
                                                        <button type="button"
                                                            onclick="document.getElementById('form-selesai-{{ $pengajuan->id_pengajuan }}').classList.add('hidden')"
                                                            class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-500 hover:bg-slate-50">
                                                            Batal
                                                        </button>
                                                    </div>
                                                </form>
                                            @endif
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
                                            {{ $p->konseli?->user?->nama ?? '-' }}
                                        </p>
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
