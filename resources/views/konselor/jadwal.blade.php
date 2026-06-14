<x-app-layout>
    <x-slot name="dashboardRole">konselor</x-slot>
    <x-slot name="headerTitle">Jadwal Konseling</x-slot>

    <section class="space-y-5">
        {{-- HEADER UTAMA TANPA BOX KAKU --}}
        <header class="pt-2">
            <p class="text-xs font-bold uppercase tracking-wider text-[#5B67F1]">Workspace Utama</p>
            <h1 class="text-2xl font-black tracking-tight text-slate-900 mt-1">
                Jadwal Konseling Resmi 
            </h1>
            <p class="mt-0.5 text-xs text-slate-500">
                Pantau agenda yang telah dikonfirmasi, lakukan telekonseling, dan input rekam medis hasil sesi di sini.
            </p>
        </header>

        @if (session('success'))
            <div class="rounded-2xl border border-green-200 bg-[#E6F5EA] p-4 text-xs font-semibold text-green-800 shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid gap-5 xl:grid-cols-[1fr_360px]">
            {{-- JADWAL MENDATANG (STYLING LIST MEMANJANG PREMIUM) --}}
            <article class="bg-white rounded-[24px] border border-slate-200 shadow-xs overflow-hidden">
                <div class="border-b border-slate-100 px-5 py-4">
                    <h2 class="text-sm font-bold text-slate-900">Jadwal Sesi Mendatang</h2>
                    <p class="text-[11px] text-slate-400 mt-0.5">Daftar agenda bimbingan yang sudah disetujui bersama konseli.</p>
                </div>

                @php
                    $hari = $jadwalMendatang->groupBy(
                        fn($j) => \Illuminate\Support\Carbon::parse($j->tanggal)->toDateString(),
                    );
                @endphp

                @if ($hari->isEmpty())
                    <div class="px-5 py-12 text-center text-xs text-slate-400 font-medium">
                        <i class="bi bi-calendar-x text-2xl text-slate-300 block mb-1"></i>
                        Tidak ada jadwal pertemuan dalam waktu dekat.
                        <a href="{{ route('konselor.pengajuan') }}" class="ml-1 font-bold text-[#5B67F1] underline hover:text-[#4A55E2]">Tinjau pengajuan masuk</a>
                    </div>
                @else
                    <div class="divide-y divide-slate-100">
                        @foreach ($hari as $tanggal => $items)
                            <div class="p-5 space-y-3 bg-slate-50/40">
                                <p class="text-[10px] font-black uppercase tracking-wider text-slate-400 flex items-center gap-1">
                                    <i class="bi bi-calendar3"></i>
                                    {{ \Illuminate\Support\Carbon::parse($tanggal)->isToday() ? 'Hari Ini — ' : '' }}
                                    {{ \Illuminate\Support\Carbon::parse($tanggal)->translatedFormat('l, d M Y') }}
                                </p>
                                
                                <div class="space-y-3">
                                    @foreach ($items as $jadwal)
                                        @php $pengajuan = $jadwal->pengajuan; @endphp
                                        <div class="rounded-2xl border border-slate-200/60 p-4 bg-white shadow-2xs hover:shadow-xs transition duration-150">
                                            
                                            <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3">
                                                <div class="space-y-1.5">
                                                    <div class="flex items-center gap-2">
                                                        <span class="grid h-7 w-7 shrink-0 place-items-center rounded-lg bg-slate-100 text-slate-700 font-black text-xs uppercase">
                                                            {{ strtoupper(mb_substr($pengajuan?->konseli?->user?->nama ?? 'K', 0, 1)) }}
                                                        </span>
                                                        <p class="text-sm font-bold text-slate-900">
                                                            {{ $pengajuan?->konseli?->user?->nama ?? '(Tanpa nama konseli)' }}
                                                        </p>
                                                    </div>
                                                    
                                                    <p class="text-xs text-slate-500 font-medium flex items-center gap-3">
                                                        <span class="inline-flex items-center gap-1 font-bold text-slate-700"><i class="bi bi-clock"></i> {{ \Illuminate\Support\Carbon::parse($jadwal->jam)->format('H:i') }} WIB</span>
                                                        <span class="text-slate-300">•</span>
                                                        <span class="text-slate-400 uppercase tracking-wider text-[10px] font-bold bg-slate-100 px-1.5 py-0.5 rounded">{{ $jadwal->tipe_konseling }}</span>
                                                    </p>

                                                    {{-- BADGE WA DENGAN HEX UNGU PASTEL ATAU HIJAU --}}
                                                    @if ($pengajuan?->konseli?->no_hp)
                                                        <div class="pt-0.5">
                                                            <a href="https://wa.me/{{ preg_replace('/^0/', '62', $pengajuan->konseli->no_hp) }}"
                                                                target="_blank" rel="noopener noreferrer"
                                                                class="inline-flex items-center gap-1.5 rounded-xl bg-[#E6F5EA] px-2.5 py-1 text-[11px] font-bold text-green-700 border border-green-200/40 shadow-3xs hover:bg-green-100 transition">
                                                                <i class="bi bi-whatsapp"></i>
                                                                +62 {{ $pengajuan->konseli->no_hp }}
                                                            </a>
                                                        </div>
                                                    @endif
                                                </div>
                                                
                                                <div class="shrink-0">
                                                    <x-dashboard.status-badge :status="$pengajuan?->status_pengajuan ?? $jadwal->status_jadwal" />
                                                </div>
                                            </div>

                                            {{-- TOMBOL INPUT HASIL BERGAYA UNGU SESUAI image_62898b.png --}}
                                            @if ($pengajuan && $pengajuan->status_pengajuan === 'disetujui')
                                                <div class="mt-3.5 border-t border-slate-50 pt-3">
                                                    <button type="button"
                                                        onclick="document.getElementById('form-selesai-{{ $pengajuan->id_pengajuan }}').classList.toggle('hidden')"
                                                        class="inline-flex items-center gap-1.5 bg-[#5B67F1] hover:bg-[#4A55E2] text-white text-sm font-semibold px-4 py-2 rounded-full shadow-md shadow-indigo-100 transition-all hover:shadow-lg">
                                                        ✓ Selesaikan Sesi &amp; Input Hasil
                                                    </button>
                                                </div>

                                                <form id="form-selesai-{{ $pengajuan->id_pengajuan }}" method="POST"
                                                    action="{{ route('konselor.pengajuan.selesai', $pengajuan->id_pengajuan) }}"
                                                    class="mt-4 hidden rounded-2xl border border-sky-200 bg-[#E0F2FE] p-4 space-y-4">
                                                    @csrf
                                                    <p class="text-xs font-bold uppercase tracking-wider text-sky-800">Pencatatan Rekam Medis / Hasil Sesi</p>
                                                    <div class="space-y-3">
                                                        <div>
                                                            <label class="mb-1 block text-[11px] font-semibold text-sky-800">Catatan Perkembangan Jalannya Konseling <span class="text-red-500">*</span></label>
                                                            <textarea name="catatan_konseling" rows="4" required
                                                                placeholder="Tuliskan ringkasan pokok pembicaraan, dinamika emosi, serta kesimpulan inti..."
                                                                class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-xs outline-none bg-white focus:border-[#5B67F1] focus:ring-4 focus:ring-indigo-100 resize-none"></textarea>
                                                        </div>
                                                        <div>
                                                            <label class="mb-1 block text-[11px] font-semibold text-sky-800">Rekomendasi / Tindak Lanjut <span class="text-slate-400 text-[10px] font-medium">(Opsional)</span></label>
                                                            <textarea name="rekomendasi" rows="2"
                                                                placeholder="Misal: Sesi lanjutan pekan depan, rujukan ke psikolog klinis spesialis, dsb."
                                                                class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-xs outline-none bg-white focus:border-[#5B67F1] focus:ring-4 focus:ring-indigo-100 resize-none"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="flex gap-2 pt-1">
                                                        <button type="submit"
                                                            onclick="return confirm('Kunci status menjadi Selesai? Catatan rekam medis tidak dapat diperbarui lagi.')"
                                                            class="inline-flex items-center gap-1.5 bg-[#5B67F1] hover:bg-[#4A55E2] text-white text-sm font-semibold px-4 py-2 rounded-full shadow-md shadow-indigo-100 transition-all hover:shadow-lg">
                                                            Simpan &amp; Selesaikan
                                                        </button>
                                                        <button type="button"
                                                            onclick="document.getElementById('form-selesai-{{ $pengajuan->id_pengajuan }}').classList.add('hidden')"
                                                            class="inline-flex items-center gap-1.5 border border-slate-200 bg-white text-slate-600 hover:bg-slate-50 text-sm font-semibold px-4 py-2 rounded-full transition-all">
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

            {{-- ASIDE: PENGAJUAN PERLU RESPONS (GAYA TIMELINE KUNING GADING PASTEL) --}}
            <aside class="space-y-5">
                <article class="bg-[#FEF9EC] rounded-[24px] border border-amber-200/70 p-5 shadow-2xs">
                    <div class="border-b border-amber-200/30 pb-3">
                        <h2 class="text-sm font-bold text-amber-900">Perlu Ditindaklanjuti</h2>
                        <p class="text-[11px] text-amber-700/80 mt-0.5">Daftar pengajuan menunggu konfirmasi atau jadwal ulang.</p>
                    </div>
                    
                    <div class="divide-y divide-amber-200/20 max-h-[480px] overflow-y-auto pr-0.5">
                        @forelse ($pengajuanMenunggu as $p)
                            <div class="py-4 space-y-3">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="min-w-0 space-y-1">
                                        <p class="text-xs font-bold text-slate-900 truncate">
                                            {{ $p->konseli?->user?->nama ?? '-' }}
                                        </p>
                                        <div class="text-[11px] text-slate-500 font-medium space-y-0.5">
                                            <p><span class="font-bold text-slate-700">Usulan:</span> {{ \Illuminate\Support\Carbon::parse($p->tanggal_usulan)->translatedFormat('d M Y') }} • {{ \Illuminate\Support\Carbon::parse($p->jam_usulan)->format('H:i') }}</p>
                                            
                                            @if ($p->status_pengajuan === 'reschedule')
                                                <p class="text-amber-700 font-semibold bg-amber-100/60 px-2 py-0.5 rounded-lg inline-block mt-1">
                                                    <i class="bi bi-arrow-clockwise"></i> Atur ulang: {{ \Illuminate\Support\Carbon::parse($p->tanggal_reschedule)->translatedFormat('d M Y') }} • {{ \Illuminate\Support\Carbon::parse($p->jam_reschedule)->format('H:i') }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="shrink-0">
                                        <x-dashboard.status-badge :status="$p->status_pengajuan" />
                                    </div>
                                </div>
                                
                                <a href="{{ route('konselor.pengajuan') }}"
                                    class="block w-full text-center bg-white border border-amber-200/60 text-amber-800 text-xs font-bold px-3 py-2 rounded-xl hover:bg-amber-100/40 transition">
                                    Tinjau Berkas →
                                </a>
                            </div>
                        @empty
                            <div class="text-center py-10">
                                <i class="bi bi-check2-circle text-amber-500 text-2xl"></i>
                                <p class="text-xs text-amber-700/70 mt-1 font-medium">Semua berkas masuk sudah ditangani.</p>
                            </div>
                        @endforelse
                    </div>
                </article>
            </aside>
        </div>
    </section>
</x-app-layout>