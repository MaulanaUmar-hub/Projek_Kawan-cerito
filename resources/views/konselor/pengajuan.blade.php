<x-app-layout>
    <x-slot name="dashboardRole">konselor</x-slot>
    <x-slot name="headerTitle">Pengajuan Konseling</x-slot>

    <section class="space-y-6">
        <x-dashboard.page-header title="Tinjauan Pengajuan"
            subtitle="Tinjau pengajuan dari konseli, lalu setujui, tolak, atau usulkan waktu lain." />

        @if (session('success'))
            <div class="kc-card border-emerald-100 bg-emerald-50 p-4 text-sm font-medium text-emerald-700">
                {{ session('success') }}</div>
        @endif

        {{-- Summary cards --}}
        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ([['Total', $counts['total'], 'text-indigo-500'], ['Menunggu', $counts['menunggu'], 'text-amber-500'], ['Reschedule', $counts['reschedule'], 'text-orange-500'], ['Disetujui', $counts['disetujui'], 'text-emerald-500']] as [$label, $val, $color])
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

                                {{-- Usulan waktu --}}
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
                                            {{ ucfirst($p->tipe_konseling_usulan ?? '-') }}</p>
                                    </div>
                                </div>

                                {{-- Keluhan --}}
                                @if ($p->assessment?->keluhan)
                                    <p class="mt-3 rounded-lg bg-slate-50 p-3 text-xs leading-5 text-slate-500">
                                        {{ \Illuminate\Support\Str::limit($p->assessment->keluhan, 200) }}
                                    </p>
                                @endif

                                {{-- Info reschedule --}}
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

                            {{-- Aksi — hanya tampil saat masih bisa direspons --}}
                            @if (in_array($p->status_pengajuan, ['menunggu']))
                                <div class="flex shrink-0 flex-col gap-2">
                                    {{-- Setujui --}}
                                    <form method="POST"
                                        action="{{ route('konselor.pengajuan.setujui', $p->id_pengajuan) }}">
                                        @csrf
                                        <button type="submit"
                                            class="w-full rounded-lg bg-emerald-600 px-4 py-2 text-xs font-semibold text-white transition hover:bg-emerald-700"
                                            onclick="return confirm('Setujui pengajuan ini? Jadwal akan dibuat sesuai usulan konseli.')">
                                            Setujui
                                        </button>
                                    </form>

                                    {{-- Reschedule toggle --}}
                                    <button type="button"
                                        onclick="document.getElementById('form-reschedule-{{ $p->id_pengajuan }}').classList.toggle('hidden')"
                                        class="w-full rounded-lg border border-amber-300 px-4 py-2 text-xs font-semibold text-amber-700 transition hover:bg-amber-50">
                                        Reschedule
                                    </button>

                                    {{-- Tolak toggle --}}
                                    <button type="button"
                                        onclick="document.getElementById('form-tolak-{{ $p->id_pengajuan }}').classList.toggle('hidden')"
                                        class="w-full rounded-lg border border-red-200 px-4 py-2 text-xs font-semibold text-red-600 transition hover:bg-red-50">
                                        Tolak
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
                                    <label class="mb-1.5 block text-xs font-medium text-slate-500">Alasan Penolakan
                                        <span class="text-red-400">*</span></label>
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
