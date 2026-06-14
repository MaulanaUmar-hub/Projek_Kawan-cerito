<x-app-layout>
    <x-slot name="dashboardRole">konselor</x-slot>
    <x-slot name="headerTitle">Pengajuan Konseling</x-slot>

    <section class="space-y-5">
        {{-- HEADER UTAMA TANPA BOX KAKU --}}
        <header class="pt-2">
            <p class="text-xs font-bold uppercase tracking-wider text-indigo-600">Workspace Utama</p>
            <h1 class="text-2xl font-black tracking-tight text-slate-900 mt-1">
                Manajemen Pengajuan Konseling 
            </h1>
            <p class="mt-0.5 text-xs text-slate-500">
                Tinjau permohonan masuk dari konseli, ambil tindakan persetujuan, atau kelola opsi jadwal baru.
            </p>
        </header>

        @if (session('success'))
        <div class="rounded-2xl border border-green-200 bg-[#E6F5EA] p-4 text-xs font-semibold text-green-800 shadow-sm">
            {{ session('success') }}
        </div>
        @endif

        @if (session('error'))
        <div class="rounded-2xl border border-red-200 bg-red-50 p-4 text-xs font-semibold text-red-700 shadow-sm">
            {{ session('error') }}
        </div>
        @endif

        {{-- SUMMARY COUNTER STATS CARD STANDAR KONSISTEN --}}
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 xl:grid-cols-5">
            @foreach ([
            ['Total', $counts['total'], 'bg-[#E0F2FE]', 'border-sky-200/70', 'text-sky-700'],
            ['Menunggu', $counts['menunggu'], 'bg-[#FFF3D6]', 'border-amber-200/70', 'text-amber-700'],
            ['Reschedule', $counts['reschedule'], 'bg-[#F3E8FF]', 'border-purple-200/70', 'text-purple-700'],
            ['Disetujui', $counts['disetujui'], 'bg-[#E6F5EA]', 'border-green-200/70', 'text-green-700'],
            ['Selesai', $counts['selesai'], 'bg-slate-100', 'border-slate-200', 'text-slate-700']
            ] as [$label, $val, $bg, $border, $color])
            <article class="{{ $bg }} rounded-[24px] p-4 border {{ $border }} shadow-2xs flex items-center justify-between gap-3">
                <div>
                    <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">{{ $label }}</p>
                    <p class="mt-1 text-2xl font-black text-slate-900">{{ $val }}</p>
                </div>
                <span class="text-xs font-bold {{ $color }} bg-white px-2 py-0.5 rounded-lg shadow-3xs">Sesi</span>
            </article>
            @endforeach
        </div>

        {{-- DAFTAR PENGAJUAN GAYA LIST MEMANJANG --}}
        <article class="bg-white rounded-[24px] border border-slate-200 shadow-xs overflow-hidden">
            <div class="border-b border-slate-100 px-5 py-4">
                <h2 class="text-sm font-bold text-slate-900">Daftar Permohonan Sesi</h2>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse ($pengajuanList as $p)
                <div class="p-5 hover:bg-slate-50/20 transition duration-150">
                    <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-4">

                        {{-- INFO UTAMA KONSELI --}}
                        <div class="min-w-0 flex-1 space-y-2">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="grid h-8 w-8 shrink-0 place-items-center rounded-xl bg-slate-100 text-slate-700 font-black text-xs uppercase">
                                    {{ strtoupper(mb_substr($p->konseli?->user?->nama ?? 'K', 0, 1)) }}
                                </span>
                                <p class="text-sm font-bold text-slate-900">{{ $p->konseli?->user?->nama ?? '-' }}</p>
                                <x-dashboard.status-badge :status="$p->status_pengajuan" />
                            </div>

                            <p class="text-[11px] text-slate-400 font-medium">
                                Diajukan {{ \Illuminate\Support\Carbon::parse($p->created_at)->diffForHumans() }}
                            </p>

                            {{-- NOMOR WHATSAPP DENGAN BADGE HIJAU PASTEL --}}
                            <div>
                                @if ($p->konseli?->no_hp)
                                <a href="https://wa.me/{{ preg_replace('/^0/', '62', $p->konseli->no_hp) }}"
                                    target="_blank" rel="noopener noreferrer"
                                    class="inline-flex items-center gap-1.5 rounded-xl bg-[#E6F5EA] px-3 py-1 text-xs font-bold text-green-700 border border-green-200/40 shadow-3xs hover:bg-green-100 transition">
                                    <i class="bi bi-whatsapp"></i>
                                    +62 {{ $p->konseli->no_hp }}
                                </a>
                                @else
                                <p class="text-xs text-slate-400 italic">Nomor WhatsApp belum diisi konseli.</p>
                                @endif
                            </div>

                            {{-- DETAIL USULAN WAKTU & TIPE --}}
                            <div class="grid grid-cols-2 gap-4 max-w-sm bg-slate-50/80 border border-slate-100 p-3 rounded-2xl">
                                <div>
                                    <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider block">Usulan Waktu</span>
                                    <p class="text-xs font-bold text-slate-800 mt-0.5">
                                        {{ \Illuminate\Support\Carbon::parse($p->tanggal_usulan)->translatedFormat('d M Y') }} • {{ \Illuminate\Support\Carbon::parse($p->jam_usulan)->format('H:i') }} WIB
                                    </p>
                                </div>
                                <div>
                                    <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider block">Tipe Konseling</span>
                                    <p class="text-xs font-bold text-slate-800 mt-0.5">
                                        {{ ucfirst($p->tipe_konseling_usulan ?? '-') }}
                                    </p>
                                </div>
                            </div>

                            {{-- KELUHAN DARI ASSESSMENT KONSELI --}}
                            @if ($p->assessment?->keluhan)
                            <div class="rounded-2xl border border-slate-100 bg-slate-50/50 p-3.5 text-xs text-slate-600 leading-relaxed">
                                <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider block mb-1">Keluhan / Gambaran Kondisi</span>
                                "{{ $p->assessment->keluhan }}"
                            </div>
                            @endif

                            {{-- INFO RESCHEDULE TERDAPAT (UNGU PASTEL) --}}
                            @if ($p->status_pengajuan === 'reschedule')
                            <div class="rounded-2xl border border-purple-200 bg-[#F3E8FF] p-3.5 text-xs">
                                <p class="font-bold text-purple-800">Anda mengusulkan waktu penyesuaian (Reschedule):</p>
                                <p class="mt-0.5 font-bold text-slate-900">
                                    {{ \Illuminate\Support\Carbon::parse($p->tanggal_reschedule)->translatedFormat('d M Y') }} • {{ \Illuminate\Support\Carbon::parse($p->jam_reschedule)->format('H:i') }} WIB
                                </p>
                                @if ($p->catatan_reschedule)
                                <p class="mt-1 text-purple-700/90 italic">Catatan: {{ $p->catatan_reschedule }}</p>
                                @endif
                                <p class="mt-2 text-[10px] font-bold text-purple-600 uppercase tracking-wide flex items-center gap-1">
                                    <span class="h-1.5 w-1.5 rounded-full bg-purple-500 animate-pulse"></span> Menunggu konfirmasi dari pihak konseli
                                </p>
                            </div>
                            @endif

                            {{-- ALASAN PENOLAKAN --}}
                            @if ($p->status_pengajuan === 'ditolak' && $p->alasan_penolakan)
                            <p class="text-xs font-bold text-red-500 bg-red-50 border border-red-100 rounded-xl px-3 py-2 inline-block">Alasan Penolakan: {{ $p->alasan_penolakan }}</p>
                            @endif
                        </div>

                        {{-- PANEL ACTION BUTTONS --}}
                        <div class="flex flex-row lg:flex-col gap-2 shrink-0 pt-1 flex-wrap">
                            @if ($p->status_pengajuan === 'menunggu')
                            <form method="POST" action="{{ route('konselor.pengajuan.setujui', $p->id_pengajuan) }}">
                                @csrf
                                <button type="submit"
                                    class="w-full rounded-xl bg-green-600 px-4 py-2 text-xs font-bold text-white hover:bg-green-700 shadow-2xs transition"
                                    onclick="return confirm('Setujui pengajuan ini? Sesi akan resmi dijadwalkan.')">
                                    ✓ Setujui
                                </button>
                            </form>

                            <button type="button"
                                onclick="document.getElementById('form-reschedule-{{ $p->id_pengajuan }}').classList.toggle('hidden')"
                                class="rounded-xl border border-purple-300 bg-white px-4 py-2 text-xs font-bold text-purple-700 hover:bg-purple-50 transition">
                                ↺ Reschedule
                            </button>

                            <button type="button"
                                onclick="document.getElementById('form-tolak-{{ $p->id_pengajuan }}').classList.toggle('hidden')"
                                class="rounded-xl border border-red-200 bg-white px-4 py-2 text-xs font-bold text-red-600 hover:bg-red-50 transition">
                                ✕ Tolak
                            </button>
                            @endif

                            @if ($p->status_pengajuan === 'disetujui')
                            <button type="button"
                                onclick="document.getElementById('form-selesai-{{ $p->id_pengajuan }}').classList.toggle('hidden')"
                                class="inline-flex items-center gap-1.5 bg-[#5B67F1] hover:bg-[#4A55E2] text-white text-sm font-semibold px-4 py-2 rounded-full shadow-md shadow-indigo-100 transition-all hover:shadow-lg">
                                ✓ Selesaikan Sesi & Input Hasil
                            </button>
                            @endif

                            {{-- SUB-FORM RESCHEDULE --}}
                            @if ($p->status_pengajuan === 'menunggu')
                            <form id="form-reschedule-{{ $p->id_pengajuan }}" method="POST"
                                action="{{ route('konselor.pengajuan.reschedule', $p->id_pengajuan) }}"
                                class="mt-4 hidden rounded-2xl border border-purple-200 bg-[#F3E8FF] p-4 space-y-4">
                                @csrf
                                <p class="text-xs font-bold uppercase tracking-wider text-purple-800">Usulkan Opsi Waktu Pertemuan Baru</p>
                                <div class="grid gap-3 sm:grid-cols-2">
                                    <div>
                                        <label class="mb-1 block text-[11px] font-semibold text-purple-700">Tanggal Baru</label>
                                        <input type="date" name="tanggal_reschedule" min="{{ now()->toDateString() }}"
                                            class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs outline-none bg-white focus:border-purple-400 focus:ring-4 focus:ring-purple-100" required />
                                    </div>
                                    <div>
                                        <label class="mb-1 block text-[11px] font-semibold text-purple-700">Jam Baru</label>
                                        <input type="time" name="jam_reschedule"
                                            class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs outline-none bg-white focus:border-purple-400 focus:ring-4 focus:ring-purple-100" required />
                                    </div>
                                    <div class="sm:col-span-2">
                                        <label class="mb-1 block text-[11px] font-semibold text-purple-700">Catatan Penyesuaian Alasan</label>
                                        <input type="text" name="catatan_reschedule" placeholder="Contoh: Jam operasional penuh, dialihkan ke esok pagi."
                                            class="w-full rounded-xl border border-slate-200 px-4 py-2 text-xs outline-none bg-white focus:border-purple-400 focus:ring-4 focus:ring-purple-100" />
                                    </div>
                                </div>
                                <div class="flex gap-2 pt-1">
                                    <button type="submit" class="rounded-xl bg-purple-600 px-4 py-2 text-xs font-bold text-white hover:bg-purple-700 shadow-xs transition">Kirim Usulan</button>
                                    <button type="button" onclick="document.getElementById('form-reschedule-{{ $p->id_pengajuan }}').classList.add('hidden')"
                                        class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-xs font-bold text-slate-500 hover:bg-slate-50">Batal</button>
                                </div>
                            </form>

                            {{-- SUB-FORM TOLAK --}}
                            <form id="form-tolak-{{ $p->id_pengajuan }}" method="POST"
                                action="{{ route('konselor.pengajuan.tolak', $p->id_pengajuan) }}"
                                class="mt-4 hidden rounded-2xl border border-red-200 bg-red-50 p-4 space-y-4">
                                @csrf
                                <p class="text-xs font-bold uppercase tracking-wider text-red-800">Konfirmasi Penolakan Berkas</p>
                                <div>
                                    <label class="mb-1 block text-[11px] font-semibold text-red-700">Alasan Penolakan Resmi <span class="text-red-500">*</span></label>
                                    <textarea name="alasan_penolakan" rows="3" required placeholder="Jelaskan kendala atau alasan penolakan agar konseli memahami rincian situasinya..."
                                        class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-xs outline-none bg-white focus:border-red-400 focus:ring-4 focus:ring-red-100 resize-none"></textarea>
                                </div>
                                <div class="flex gap-2">
                                    <button type="submit" class="rounded-xl bg-red-600 px-4 py-2 text-xs font-bold text-white hover:bg-red-700 shadow-xs transition">Konfirmasi Tolak</button>
                                    <button type="button" onclick="document.getElementById('form-tolak-{{ $p->id_pengajuan }}').classList.add('hidden')"
                                        class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-xs font-bold text-slate-500 hover:bg-slate-50">Batal</button>
                                </div>
                            </form>
                            @endif

                            {{-- SUB-FORM SELESAIKAN JADWAL (PERBAIKAN: konselog DIUBAH MENJADI konselor) --}}
                            @if ($p->status_pengajuan === 'disetujui')
                            <form id="form-selesai-{{ $p->id_pengajuan }}" method="POST"
                                action="{{ route('konselor.pengajuan.selesai', $p->id_pengajuan) }}"
                                class="mt-4 hidden rounded-2xl border border-sky-200 bg-[#E0F2FE] p-4 space-y-4">
                                @csrf
                                <p class="text-xs font-bold uppercase tracking-wider text-sky-800">Pencatatan Rekam Medis / Hasil Sesi</p>
                                <div class="space-y-3">
                                    <div>
                                        <label class="mb-1 block text-[11px] font-semibold text-sky-800">Catatan Perkembangan Jalannya Konseling <span class="text-red-500">*</span></label>
                                        <textarea name="catatan_konseling" rows="4" required placeholder="Tuliskan ringkasan pokok pembicaraan, dinamika emosi, serta kesimpulan inti yang didapatkan..."
                                            class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-xs outline-none bg-white focus:border-sky-400 focus:ring-4 focus:ring-sky-100 resize-none"></textarea>
                                    </div>
                                    <div>
                                        <label class="mb-1 block text-[11px] font-semibold text-sky-800">Rekomendasi / Tindak Lanjut <span class="text-slate-400 text-[10px] font-medium">(Opsional)</span></label>
                                        <textarea name="rekomendasi" rows="2" placeholder="Misal: Sesi lanjutan pekan depan, rujukan ke psikolog klinis spesialis, dsb."
                                            class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-xs outline-none bg-white focus:border-sky-400 focus:ring-4 focus:ring-sky-100 resize-none"></textarea>
                                    </div>
                                </div>
                                <div class="flex gap-2 pt-1">
                                    <button type="submit" onclick="return confirm('Kunci status menjadi Selesai? Catatan rekam medis tidak dapat diperbarui lagi.')"
                                        class="rounded-xl bg-sky-600 px-4 py-2 text-xs font-bold text-white hover:bg-sky-700 shadow-xs transition">Simpan & Selesaikan Sesi</button>
                                    <button type="button" onclick="document.getElementById('form-selesai-{{ $p->id_pengajuan }}').classList.add('hidden')"
                                        class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-xs font-bold text-slate-500 hover:bg-slate-50">Batal</button>
                                </div>
                            </form>
                            @endif
                        </div>
                        @empty
                        <div class="px-5 py-12 text-center text-xs text-slate-400 font-medium">
                            <i class="bi bi-inbox text-2xl text-slate-300 block mb-1"></i>
                            Belum ditemukan permohonan pengajuan masuk dalam sistem.
                        </div>
                        @endforelse
                    </div>
        </article>
    </section>
</x-app-layout>