<x-app-layout>
    <x-slot name="dashboardRole">konseli</x-slot>
    <x-slot name="headerTitle">Ajukan Konseling</x-slot>

    <style>
        /* Mengubah sistem carousel menjadi list memanjang ke bawah dengan scroll tipis jika banyak data */
        .konselor-list-container {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            max-h: 420px;
            overflow-y: auto;
            padding-right: 4px;
        }

        .konselor-card-row {
            width: 100%;
            transition: all 0.30s ease;
        }

        /* Gaya Khusus Kartu Memanjang Saat Terpilih (Ungu) */
        .konselor-card-row.is-selected {
            border-color: #a855f7;
            background-color: #f3e8ff;
            box-shadow: 0 4px 12px -2px rgba(168, 85, 247, 0.08);
        }

        .konselor-card-row.is-selected .konselor-check-badge {
            background-color: #a855f7;
            color: #ffffff;
        }
    </style>

    <section class="space-y-5">
        {{-- HEADER SAPAAN TANPA BOX KAKU --}}
        <header class="pt-2">
            <h1 class="text-2xl font-black tracking-tight text-slate-900">
                Ajukan Konseling Untuk Mulai Bercerita
            </h1>
            <p class="mt-1 text-sm text-slate-500">
                Pilih profesional yang tepat dan usulkan waktu yang sesuai dengan kenyamanan janjimu.
            </p>
        </header>

        {{-- NOTIFIKASI BERHASIL (HIJAU PASTEL) --}}
        @if (session('success'))
            <div class="rounded-2xl border border-green-200 bg-[#E6F5EA] p-4 text-xs font-semibold text-green-800 shadow-sm flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i class="bi bi-check-circle-fill text-green-600 text-sm"></i>
                    <span>{{ session('success') }}</span>
                </div>
                <a href="{{ route('konseli.riwayat') }}" class="inline-flex items-center gap-1 bg-white text-green-700 px-3 py-1 rounded-xl shadow-sm hover:bg-green-600 hover:text-white transition duration-300">
                    Lihat Riwayat Pengajuan →
                </a>
            </div>
        @endif

        @if ($errors->any())
            <div class="rounded-2xl border border-red-200 bg-red-50 p-4 text-xs font-semibold text-red-700 shadow-sm flex items-center gap-2">
                <i class="bi bi-exclamation-triangle-fill text-red-500 text-sm"></i>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        {{-- MAIN CONTENT GRID --}}
        <div class="grid gap-5 xl:grid-cols-[1fr_360px]">
            
            {{-- FORM UTAMA --}}
            <x-dashboard.form-card title="Form Pengajuan"
                description="Lengkapi pilihan di bawah untuk mengirimkan permohonan temu langsung ke konselor.">
                
                @if ($assessments->isEmpty())
                    <div class="rounded-2xl border border-amber-200 bg-[#FFF3D6] p-5 text-xs font-medium text-amber-900 shadow-sm">
                        <p class="font-bold text-amber-800 text-sm flex items-center gap-1.5">
                            <i class="bi bi-exclamation-circle-fill"></i> Kamu belum memiliki assessment.
                        </p>
                        <p class="mt-1 text-slate-700">Setiap pengajuan wajib didahului dengan pengisian kondisi awal pada Langkah 1.</p>
                        <a href="{{ route('konseli.assessment') }}"
                            class="mt-3 inline-flex rounded-xl bg-amber-600 px-4 py-2 text-xs font-bold text-white shadow-sm hover:bg-amber-700 transition duration-300">
                            Buat Assessment Sekarang
                        </a>
                    </div>
                @elseif ($konselors->isEmpty())
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5 text-xs font-medium text-slate-500">
                        Belum ada praktisi konselor aktif saat ini. Coba kembali beberapa saat lagi.
                    </div>
                @else
                    @php
                        $selectedKonselor = old('id_konselor');
                    @endphp
                    
                    <form method="POST" action="{{ route('konseli.pengajuan.store') }}" class="space-y-5">
                        @csrf

                        {{-- Assessment Dropdown --}}
                        <div>
                            <label for="id_assessment" class="mb-1.5 block text-xs font-bold text-slate-800 uppercase tracking-wider">
                                Assessment Acuan <span class="text-red-500">*</span>
                            </label>
                            <select id="id_assessment" name="id_assessment"
                                class="w-full rounded-2xl border px-4 py-2.5 text-xs font-medium text-slate-900 outline-none transition-all duration-300 focus:border-indigo-400 focus:ring-4 focus:ring-indigo-100 {{ $errors->has('id_assessment') ? 'border-red-300 ring-4 ring-red-50' : 'border-slate-200' }}">
                                <option value="">Pilih assessment terakhirmu</option>
                                @foreach ($assessments as $a)
                                    <option value="{{ $a['id'] }}" @selected(old('id_assessment') == $a['id'])>{{ $a['label'] }}</option>
                                @endforeach
                            </select>
                            @error('id_assessment')
                                <p class="mt-1.5 text-[11px] font-semibold text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Pilihan Konselor (Desain Memanjang Ke Bawah) --}}
                        <div>
                            <div class="mb-3 flex flex-wrap items-end justify-between gap-3">
                                <div>
                                    <label class="block text-xs font-bold text-slate-800 uppercase tracking-wider">
                                        Pilih Konselor Anda <span class="text-red-500">*</span>
                                    </label>
                                    <p class="mt-1 text-[11px] text-slate-400">
                                        Pilih salah satu profil praktisi di bawah ini untuk mengajukan sesi temu.
                                    </p>
                                </div>
                                <p id="selected-konselor-label" class="text-xs font-bold text-purple-700 bg-purple-100/70 px-2.5 py-0.5 rounded-full">
                                    {{ $selectedKonselor ? 'Konselor sudah dipilih' : 'Belum memilih konselor' }}
                                </p>
                            </div>

                            <input id="id_konselor" type="hidden" name="id_konselor" value="{{ $selectedKonselor }}">

                            <div class="konselor-list-container" aria-label="Daftar pilihan konselor">
                                @foreach ($konselors as $k)
                                    @php
                                        $isSelected = (string) $selectedKonselor === (string) $k['id'];
                                        $initial = strtoupper(mb_substr($k['nama'], 0, 1));
                                        $peminatanTags = collect(explode(',', $k['peminatan'] ?? ''))
                                            ->map(fn($tag) => trim($tag))
                                            ->filter()
                                            ->take(4);
                                    @endphp
                                    
                                    <button type="button" data-konselor-card data-konselor-id="{{ $k['id'] }}"
                                        data-konselor-name="{{ $k['nama'] }}"
                                        aria-pressed="{{ $isSelected ? 'true' : 'false' }}"
                                        class="konselor-card-row group rounded-2xl border p-4 text-left flex flex-col sm:flex-row sm:items-center justify-between gap-4 {{ $isSelected ? 'is-selected' : 'border-slate-200 bg-white hover:border-purple-200 hover:bg-purple-50/20' }}">
                                        
                                        <div class="flex items-center gap-3.5 min-w-0 flex-1">
                                            @if ($k['foto_url'])
                                                <img src="{{ $k['foto_url'] }}" alt="Foto {{ $k['nama'] }}"
                                                    class="h-11 w-11 rounded-xl object-cover flex-none ring-2 ring-purple-100/50">
                                            @else
                                                <span class="grid h-11 w-11 rounded-xl bg-gradient-to-br from-purple-500 to-indigo-400 text-xs font-black text-white place-items-center flex-none shadow-xs">
                                                    {{ $initial }}
                                                </span>
                                            @endif

                                            <div class="min-w-0">
                                                <div class="flex items-center gap-2">
                                                    <p class="truncate text-sm font-bold text-slate-900">{{ $k['nama'] }}</p>
                                                    <span class="text-[10px] font-extrabold text-purple-600 bg-purple-100 px-2 py-0.5 rounded-md uppercase tracking-wide flex-none">
                                                        {{ $k['spesialisasi'] }}
                                                    </span>
                                                </div>
                                                <p class="mt-0.5 text-xs text-slate-500 line-clamp-1">
                                                    {{ $k['catatan'] ?: 'Konselor siap mendengarkan cerita dan membimbing sesi konseling Anda.' }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="flex items-center justify-between sm:justify-end gap-4 border-t sm:border-t-0 border-slate-100 pt-2 sm:pt-0 flex-wrap sm:flex-nowrap">
                                            <div class="flex flex-wrap gap-1">
                                                @forelse ($peminatanTags as $tag)
                                                    <span class="rounded-lg bg-slate-100 group-hover:bg-white/60 px-2 py-0.5 text-[10px] font-semibold text-slate-600 transition-colors">
                                                        {{ $tag }}
                                                    </span>
                                                @empty
                                                    <span class="rounded-lg bg-slate-50 px-2 py-0.5 text-[10px] font-medium text-slate-400">Umum</span>
                                                @endforelse
                                            </div>

                                            <div data-konselor-check class="konselor-check-badge flex h-6 px-3 items-center justify-center rounded-xl bg-slate-100 text-slate-400 text-xs font-bold transition-all">
                                                {!! $isSelected ? '<i class="bi bi-check-lg mr-1"></i> Terpilih' : 'Pilih' !!}
                                            </div>
                                        </div>

                                    </button>
                                @endforeach
                            </div>
                            @error('id_konselor')
                                <p class="mt-1.5 text-[11px] font-semibold text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Usulan Jadwal --}}
                        <div class="pt-2 border-t border-slate-50">
                            <label class="mb-1 block text-xs font-bold text-slate-800 uppercase tracking-wider">
                                Usulan Waktu Sesi <span class="text-red-500">*</span>
                            </label>
                            <p class="mb-3 text-[11px] text-slate-400">Konselor dapat menyetujui langsung atau menawarkan opsi jam penyesuaian baru.</p>
                            
                            <div class="grid gap-3 sm:grid-cols-3">
                                <div>
                                    <label for="tanggal_usulan" class="mb-1.5 block text-[11px] font-semibold text-slate-500">Tanggal Temu</label>
                                    <input type="date" id="tanggal_usulan" name="tanggal_usulan"
                                        min="{{ now()->toDateString() }}" value="{{ old('tanggal_usulan') }}"
                                        class="w-full rounded-2xl border px-4 py-2.5 text-xs font-medium outline-none bg-white transition-all duration-300 focus:border-indigo-400 focus:ring-4 focus:ring-indigo-100 {{ $errors->has('tanggal_usulan') ? 'border-red-300 ring-4 ring-red-50' : 'border-slate-200' }}" />
                                    @error('tanggal_usulan')
                                        <p class="mt-1 text-[11px] font-semibold text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="jam_usulan" class="mb-1.5 block text-[11px] font-semibold text-slate-500">Jam Sesi</label>
                                    <input type="time" id="jam_usulan" name="jam_usulan"
                                        value="{{ old('jam_usulan') }}"
                                        class="w-full rounded-2xl border px-4 py-2.5 text-xs font-medium outline-none bg-white transition-all duration-300 focus:border-indigo-400 focus:ring-4 focus:ring-indigo-100 {{ $errors->has('jam_usulan') ? 'border-red-300 ring-4 ring-red-50' : 'border-slate-200' }}" />
                                    @error('jam_usulan')
                                        <p class="mt-1 text-[11px] font-semibold text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="tipe_konseling_usulan" class="mb-1.5 block text-[11px] font-semibold text-slate-500">Tipe Pertemuan</label>
                                    <select id="tipe_konseling_usulan" name="tipe_konseling_usulan"
                                        class="w-full rounded-2xl border px-4 py-2.5 text-xs font-medium outline-none bg-white transition-all duration-300 focus:border-indigo-400 focus:ring-4 focus:ring-indigo-100 {{ $errors->has('tipe_konseling_usulan') ? 'border-red-300 ring-4 ring-red-50' : 'border-slate-200' }}">
                                        <option value="">Pilih tipe</option>
                                        <option value="online" @selected(old('tipe_konseling_usulan') === 'online')>Online (WhatsApp)</option>
                                        <option value="offline" @selected(old('tipe_konseling_usulan') === 'offline')>Offline (Tatap Muka)</option>
                                    </select>
                                    @error('tipe_konseling_usulan')
                                        <p class="mt-1 text-[11px] font-semibold text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- BUTTONS ACTION --}}
                        <div class="flex items-center gap-3 pt-3 border-t border-slate-50">
                            <button type="submit"
                                class="rounded-xl bg-indigo-600 px-5 py-2.5 text-xs font-bold text-white shadow-md shadow-indigo-100 transition-all duration-300 hover:scale-[1.01] hover:bg-indigo-700">
                                Kirim Pengajuan
                            </button>
                            <a href="{{ route('konseli.dashboard') }}"
                                class="rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-xs font-bold text-slate-600 transition-all duration-300 hover:bg-slate-50">
                                Kembali
                            </a>
                        </div>
                    </form>
                @endif
            </x-dashboard.form-card>

            {{-- ASIDE PANEL --}}
            <aside class="space-y-4">
                
                {{-- ALUR ALUR (UNGU PASTEL) --}}
                <article class="bg-[#F3E8FF] rounded-2xl p-5 border border-purple-200/80 shadow-sm">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xs font-black uppercase tracking-wider text-purple-800">Alur Pengajuan</h2>
                        <i class="bi bi-arrow-down-up text-purple-600"></i>
                    </div>
                    <ol class="mt-4 space-y-3.5">
                        @foreach ([['Kirim pengajuan', 'Tentukan usulan waktu & profil praktisi.'], ['Konselor merespons', 'Menunggu persetujuan atau reschedule.'], ['Jika reschedule', 'Konfirmasi ulang opsi jam baru dari konselor.'], ['Jadwal terkonfirmasi', 'Ruang konseling aktif sepenuhnya sesuai jadwal.']] as $i => [$title, $desc])
                            <li class="flex gap-2.5">
                                <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-md bg-white text-purple-700 text-[11px] font-black shadow-sm">
                                    {{ $i + 1 }}
                                </span>
                                <div>
                                    <p class="text-xs font-bold text-slate-900 leading-none">{{ $title }}</p>
                                    <p class="mt-1 text-[10px] text-slate-600 leading-normal">{{ $desc }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ol>
                </article>

                {{-- KONSELOR AKTIF (BIRU PASTEL) --}}
                <article class="bg-[#E0F2FE] rounded-2xl p-5 border border-sky-200 shadow-sm">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xs font-black uppercase tracking-wider text-sky-800">Konselor Tersedia</h2>
                        <i class="bi bi-people-fill text-sky-600"></i>
                    </div>
                    <p class="mt-1 text-[10px] leading-relaxed text-sky-900/80">
                        Ringkasan cepat para praktisi aktif yang siap melayani konsultasimu.
                    </p>
                    
                    <div class="mt-3.5 space-y-2 max-h-[220px] overflow-y-auto pr-1">
                        @forelse ($konselors as $k)
                            <div class="rounded-xl bg-white border border-sky-100 p-2.5 shadow-2xs transition-all hover:bg-sky-50/40">
                                <div class="flex items-center gap-2.5">
                                    @if ($k['foto_url'])
                                        <img src="{{ $k['foto_url'] }}" alt="Foto {{ $k['nama'] }}"
                                            class="h-8 w-8 rounded-lg object-cover">
                                    @else
                                        <span class="grid h-8 w-8 place-items-center rounded-lg bg-sky-100 text-xs font-black text-sky-700">
                                            {{ strtoupper(mb_substr($k['nama'], 0, 1)) }}
                                        </span>
                                    @endif
                                    <div class="min-w-0 flex-1">
                                        <p class="truncate text-xs font-bold text-slate-900 leading-tight">{{ $k['nama'] }}</p>
                                        <p class="mt-0.5 truncate text-[10px] text-slate-500 leading-none">{{ $k['spesialisasi'] }}</p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-xs text-sky-700/60 font-medium">Belum ada praktisi yang berjaga.</p>
                        @endforelse
                    </div>
                </article>
            </aside>
        </div>
    </section>

    {{-- INTERAKSI JAVASCRIPT --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const input = document.getElementById('id_konselor');
            const label = document.getElementById('selected-konselor-label');
            const cards = document.querySelectorAll('[data-konselor-card]');

            const setSelected = (selectedCard) => {
                cards.forEach((card) => {
                    const isActive = card === selectedCard;
                    card.setAttribute('aria-pressed', isActive ? 'true' : 'false');
                    card.classList.toggle('is-selected', isActive);
                    
                    if (!isActive) {
                        card.classList.add('border-slate-200', 'bg-white');
                    } else {
                        card.classList.remove('border-slate-200', 'bg-white');
                    }
                    
                    const checkBadge = card.querySelector('[data-konselor-check]');
                    if (checkBadge) {
                        if (isActive) {
                            checkBadge.innerHTML = '<i class="bi bi-check-lg mr-1"></i> Terpilih';
                            checkBadge.classList.remove('bg-slate-100', 'text-slate-400');
                            checkBadge.classList.add('bg-purple-600', 'text-white');
                        } else {
                            checkBadge.innerHTML = 'Pilih';
                            checkBadge.classList.remove('bg-purple-600', 'text-white');
                            checkBadge.classList.add('bg-slate-100', 'text-slate-400');
                        }
                    }
                });

                input.value = selectedCard.dataset.konselorId;
                label.textContent = `${selectedCard.dataset.konselorName} dipilih`;
            };

            cards.forEach((card) => {
                if (input.value && card.dataset.konselorId === input.value) {
                    setSelected(card);
                }

                card.addEventListener('click', () => setSelected(card));
            });
        });
    </script>
</x-app-layout>