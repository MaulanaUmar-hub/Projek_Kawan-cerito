<x-app-layout>
    <x-slot name="dashboardRole">konselor</x-slot>
    <x-slot name="headerTitle">Pengajuan Konseling</x-slot>

    <section class="space-y-6">
        <x-dashboard.page-header title="Pengajuan Konseling"
            subtitle="Tinjau pengajuan dari konseli, lalu setujui, tolak, atau usulkan waktu lain." />

        @if (session('success'))
            <div class="kc-card border-emerald-100 bg-emerald-50 p-4 text-sm font-medium text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="kc-card border-red-100 bg-red-50 p-4 text-sm font-medium text-red-700">
                {{ session('error') }}
            </div>
        @endif

        {{-- Summary cards --}}
        <div class="grid gap-4 sm:grid-cols-3 xl:grid-cols-5">
            @foreach ([['Total', $counts['total'], 'text-indigo-500'], ['Menunggu', $counts['menunggu'], 'text-amber-500'], ['Reschedule', $counts['reschedule'], 'text-orange-500'], ['Disetujui', $counts['disetujui'], 'text-emerald-500'], ['Selesai', $counts['selesai'], 'text-sky-500']] as [$label, $val, $color])
                <article class="kc-card p-5">
                    <p class="text-sm text-slate-400">{{ $label }}</p>
                    <p class="mt-3 text-2xl font-bold {{ $color }}">{{ $val }}</p>
                </article>
            @endforeach
        </div>

        {{-- Daftar pengajuan --}}
        <article class="kc-card">
            <div class="border-b border-slate-100 px-6 py-5">
                <h2 class="text-lg font-semibold text-kc-heading">Daftar Pengajuan</h2>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse ($pengajuanList as $p)
                    <div class="p-6">
                        <div class="flex flex-wrap items-start justify-between gap-4">

                            {{-- Info konseli --}}
                            <div class="min-w-0 flex-1">
                                <div class="flex flex-wrap items-center gap-3">
                                    <p class="font-semibold text-kc-heading">{{ $p->konseli?->user?->nama ?? '-' }}</p>
                                    <x-dashboard.status-badge :status="$p->status_pengajuan" />
                                </div>
                                <p class="mt-2 text-xs text-slate-400">
                                    Diajukan {{ \Illuminate\Support\Carbon::parse($p->created_at)->diffForHumans() }}
                                </p>

                                {{-- Nomor WhatsApp konseli --}}
                                @if ($p->konseli?->no_hp)
                                    <a href="https://wa.me/{{ preg_replace('/^0/', '62', $p->konseli->no_hp) }}"
                                        target="_blank" rel="noopener noreferrer"
                                        class="mt-2 inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-600 hover:underline">
                                        <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z" />
                                            <path
                                                d="M12 0C5.373 0 0 5.373 0 12c0 2.127.558 4.126 1.532 5.86L.057 23.75a.5.5 0 0 0 .614.63l6.094-1.598A11.94 11.94 0 0 0 12 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.9a9.87 9.87 0 0 1-5.034-1.374l-.36-.214-3.733.979.997-3.648-.234-.374A9.861 9.861 0 0 1 2.1 12C2.1 6.534 6.534 2.1 12 2.1c5.466 0 9.9 4.434 9.9 9.9 0 5.466-4.434 9.9-9.9 9.9z" />
                                        </svg>
                                        {{ $p->konseli->no_hp }}
                                    </a>
                                @else
                                    <p class="mt-2 text-xs text-slate-400 italic">Nomor WhatsApp belum diisi konseli.
                                    </p>
                                @endif

                                {{-- Usulan waktu & tipe --}}
                                <div class="mt-3 flex flex-wrap gap-4 text-sm">
                                    <div>
                                        <span class="text-xs text-slate-400">Usulan waktu</span>
                                        <p class="mt-0.5 font-semibold text-kc-heading">
                                            {{ \Illuminate\Support\Carbon::parse($p->tanggal_usulan)->translatedFormat('d M Y') }},
                                            {{ \Illuminate\Support\Carbon::parse($p->jam_usulan)->format('H:i') }}
                                        </p>
                                    </div>
                                    <div>
                                        <span class="text-xs text-slate-400">Tipe</span>
                                        <p class="mt-0.5 font-semibold text-kc-heading">
                                            {{ ucfirst($p->tipe_konseling_usulan ?? '-') }}
                                        </p>
                                    </div>
                                </div>

                                {{-- Keluhan dari assessment --}}
                                @if ($p->assessment?->keluhan)
                                    <p class="mt-3 rounded-lg bg-slate-50 p-3 text-xs leading-5 text-slate-500">
                                        {{ \Illuminate\Support\Str::limit($p->assessment->keluhan, 200) }}
                                    </p>
                                @endif

                                {{-- Info reschedule yang sudah diusulkan --}}
                                @if ($p->status_pengajuan === 'reschedule')
                                    <div class="mt-3 rounded-lg border border-amber-100 bg-amber-50 p-3 text-xs">
                                        <p class="font-semibold text-amber-700">Kamu mengusulkan reschedule ke:</p>
                                        <p class="mt-1 text-amber-600">
                                            {{ \Illuminate\Support\Carbon::parse($p->tanggal_reschedule)->translatedFormat('d M Y') }},
                                            {{ \Illuminate\Support\Carbon::parse($p->jam_reschedule)->format('H:i') }}
                                        </p>
                                        @if ($p->catatan_reschedule)
                                            <p class="mt-1 text-slate-400">{{ $p->catatan_reschedule }}</p>
                                        @endif
                                        <p class="mt-2 italic text-amber-500">Menunggu konfirmasi konseli…</p>
                                    </div>
                                @endif

                                {{-- Alasan penolakan --}}
                                @if ($p->status_pengajuan === 'ditolak' && $p->alasan_penolakan)
                                    <p class="mt-2 text-xs text-red-400">Alasan: {{ $p->alasan_penolakan }}</p>
                                @endif
                            </div>

                            {{-- Tombol aksi (hanya saat menunggu) --}}
                            @if ($p->status_pengajuan === 'menunggu')
                                <div class="flex shrink-0 flex-col gap-2 pt-1">
                                    {{-- Setujui --}}
                                    <form method="POST"
                                        action="{{ route('konselor.pengajuan.setujui', $p->id_pengajuan) }}">
                                        @csrf
                                        <button type="submit"
                                            class="w-full rounded-lg bg-emerald-600 px-4 py-2 text-xs font-semibold text-white transition hover:bg-emerald-700"
                                            onclick="return confirm('Setujui pengajuan ini? Jadwal akan dibuat sesuai usulan konseli.')">
                                            ✓ Setujui
                                        </button>
                                    </form>

                                    {{-- Reschedule --}}
                                    <button type="button"
                                        onclick="document.getElementById('form-reschedule-{{ $p->id_pengajuan }}').classList.toggle('hidden')"
                                        class="w-full rounded-lg border border-amber-300 px-4 py-2 text-xs font-semibold text-amber-700 transition hover:bg-amber-50">
                                        ↺ Reschedule
                                    </button>

                                    {{-- Tolak --}}
                                    <button type="button"
                                        onclick="document.getElementById('form-tolak-{{ $p->id_pengajuan }}').classList.toggle('hidden')"
                                        class="w-full rounded-lg border border-red-200 px-4 py-2 text-xs font-semibold text-red-600 transition hover:bg-red-50">
                                        ✕ Tolak
                                    </button>
                                </div>
                            @endif
                        </div>

                        {{-- Form Reschedule --}}
                        @if ($p->status_pengajuan === 'menunggu')
                            <form id="form-reschedule-{{ $p->id_pengajuan }}" method="POST"
                                action="{{ route('konselor.pengajuan.reschedule', $p->id_pengajuan) }}"
                                class="mt-5 hidden rounded-xl border border-amber-100 bg-amber-50 p-5">
                                @csrf
                                <p class="mb-4 text-sm font-semibold text-amber-700">Usulkan Waktu Baru</p>
                                <div class="grid gap-4 sm:grid-cols-2">
                                    <div>
                                        <label class="mb-1.5 block text-xs font-medium text-slate-500">Tanggal
                                            Baru</label>
                                        <input type="date" name="tanggal_reschedule"
                                            min="{{ now()->toDateString() }}"
                                            class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm outline-none focus:border-amber-400 focus:ring-4 focus:ring-amber-100"
                                            required />
                                    </div>
                                    <div>
                                        <label class="mb-1.5 block text-xs font-medium text-slate-500">Jam Baru</label>
                                        <input type="time" name="jam_reschedule"
                                            class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm outline-none focus:border-amber-400 focus:ring-4 focus:ring-amber-100"
                                            required />
                                    </div>
                                    <div class="sm:col-span-2">
                                        <label class="mb-1.5 block text-xs font-medium text-slate-500">Catatan
                                            (opsional)</label>
                                        <input type="text" name="catatan_reschedule"
                                            placeholder="Misal: Waktu sebelumnya sudah terpakai."
                                            class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm outline-none focus:border-amber-400 focus:ring-4 focus:ring-amber-100" />
                                    </div>
                                </div>
                                <div class="mt-4 flex gap-3">
                                    <button type="submit"
                                        class="rounded-lg bg-amber-500 px-4 py-2 text-xs font-semibold text-white hover:bg-amber-600">
                                        Kirim Usulan Reschedule
                                    </button>
                                    <button type="button"
                                        onclick="document.getElementById('form-reschedule-{{ $p->id_pengajuan }}').classList.add('hidden')"
                                        class="rounded-lg border border-slate-200 px-4 py-2 text-xs font-semibold text-slate-500 hover:bg-slate-50">
                                        Batal
                                    </button>
                                </div>
                            </form>

                            {{-- Form Tolak --}}
                            <form id="form-tolak-{{ $p->id_pengajuan }}" method="POST"
                                action="{{ route('konselor.pengajuan.tolak', $p->id_pengajuan) }}"
                                class="mt-5 hidden rounded-xl border border-red-100 bg-red-50 p-5">
                                @csrf
                                <p class="mb-4 text-sm font-semibold text-red-600">Tolak Pengajuan</p>
                                <div>
                                    <label class="mb-1.5 block text-xs font-medium text-slate-500">
                                        Alasan Penolakan <span class="text-red-400">*</span>
                                    </label>
                                    <textarea name="alasan_penolakan" rows="3" required
                                        placeholder="Jelaskan alasan penolakan agar konseli dapat memahami situasinya."
                                        class="w-full rounded-lg border border-slate-200 px-4 py-3 text-sm outline-none focus:border-red-300 focus:ring-4 focus:ring-red-100"></textarea>
                                </div>
                                <div class="mt-4 flex gap-3">
                                    <button type="submit"
                                        class="rounded-lg bg-red-600 px-4 py-2 text-xs font-semibold text-white hover:bg-red-700">
                                        Konfirmasi Tolak
                                    </button>
                                    <button type="button"
                                        onclick="document.getElementById('form-tolak-{{ $p->id_pengajuan }}').classList.add('hidden')"
                                        class="rounded-lg border border-slate-200 px-4 py-2 text-xs font-semibold text-slate-500 hover:bg-slate-50">
                                        Batal
                                    </button>
                                </div>
                            </form>
                        @endif

                        {{-- Tombol Selesaikan Sesi (saat disetujui) --}}
                        @if ($p->status_pengajuan === 'disetujui')
                            <div class="mt-4">
                                <button type="button"
                                    onclick="document.getElementById('form-selesai-{{ $p->id_pengajuan }}').classList.toggle('hidden')"
                                    class="rounded-lg bg-sky-600 px-4 py-2 text-xs font-semibold text-white transition hover:bg-sky-700">
                                    ✓ Selesaikan Sesi &amp; Input Hasil
                                </button>
                            </div>

                            {{-- Form Selesaikan Konseling --}}
                            <form id="form-selesai-{{ $p->id_pengajuan }}" method="POST"
                                action="{{ route('konselor.pengajuan.selesai', $p->id_pengajuan) }}"
                                class="mt-5 hidden rounded-xl border border-sky-100 bg-sky-50 p-5">
                                @csrf
                                <p class="mb-4 text-sm font-semibold text-sky-700">Input Hasil Konseling</p>
                                <div class="space-y-4">
                                    <div>
                                        <label class="mb-1.5 block text-xs font-medium text-slate-500">
                                            Catatan Konseling <span class="text-red-400">*</span>
                                        </label>
                                        <textarea name="catatan_konseling" rows="5" required
                                            placeholder="Tuliskan ringkasan jalannya sesi, permasalahan yang dibahas, dan perkembangan konseli..."
                                            class="w-full rounded-lg border border-slate-200 px-4 py-3 text-sm outline-none focus:border-sky-400 focus:ring-4 focus:ring-sky-100"></textarea>
                                    </div>
                                    <div>
                                        <label class="mb-1.5 block text-xs font-medium text-slate-500">
                                            Rekomendasi Tindak Lanjut <span class="text-slate-300">(opsional)</span>
                                        </label>
                                        <textarea name="rekomendasi" rows="3"
                                            placeholder="Misal: Disarankan melakukan sesi lanjutan, dirujuk ke psikolog klinis, dsb."
                                            class="w-full rounded-lg border border-slate-200 px-4 py-3 text-sm outline-none focus:border-sky-400 focus:ring-4 focus:ring-sky-100"></textarea>
                                    </div>
                                </div>
                                <div class="mt-4 flex gap-3">
                                    <button type="submit"
                                        onclick="return confirm('Selesaikan sesi ini? Status akan berubah jadi Selesai dan tidak dapat diubah kembali.')"
                                        class="rounded-lg bg-sky-600 px-4 py-2 text-xs font-semibold text-white hover:bg-sky-700">
                                        Simpan &amp; Selesaikan
                                    </button>
                                    <button type="button"
                                        onclick="document.getElementById('form-selesai-{{ $p->id_pengajuan }}').classList.add('hidden')"
                                        class="rounded-lg border border-slate-200 px-4 py-2 text-xs font-semibold text-slate-500 hover:bg-slate-50">
                                        Batal
                                    </button>
                                </div>
                            </form>
                        @endif
                    </div>
                @empty
                    <div class="px-6 py-12 text-center text-slate-400">
                        Belum ada pengajuan yang masuk.
                    </div>
                @endforelse
            </div>
        </article>
    </section>
</x-app-layout>
