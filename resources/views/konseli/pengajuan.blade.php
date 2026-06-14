<x-app-layout>
    <x-slot name="dashboardRole">konseli</x-slot>
    <x-slot name="headerTitle">Ajukan Konseling</x-slot>

    <style>
        .konselor-carousel {
            display: flex;
            gap: 1rem;
            overflow-x: auto;
            padding-bottom: .75rem;
            scroll-snap-type: x mandatory;
        }

        .konselor-card {
            width: 280px;
            min-height: 280px;
            flex: 0 0 280px;
            scroll-snap-align: start;
        }

        .konselor-card.is-selected {
            border-color: #696cff;
            background: rgba(105, 108, 255, .08);
            box-shadow: 0 10px 24px rgba(105, 108, 255, .16);
        }

        .konselor-card.is-selected .konselor-card-check {
            display: block;
        }
    </style>

    <section class="space-y-6">
        <x-dashboard.page-header title="Mari Mulai Konseling"
            subtitle="Pilih konselor dan usulkan waktu yang sesuai. Konselor akan mengkonfirmasi atau menawarkan waktu lain." />

        @if (session('success'))
            <div class="kc-card border-emerald-100 bg-emerald-50 p-4 text-sm font-medium text-emerald-700">
                {{ session('success') }}
                <a href="{{ route('konseli.riwayat') }}" class="ml-2 font-semibold underline">Lihat riwayat</a>
            </div>
        @endif
        @if ($errors->any())
            <div class="kc-card border-red-100 bg-red-50 p-4 text-sm font-medium text-red-700">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="grid gap-6 xl:grid-cols-[1fr_360px]">
            <x-dashboard.form-card title="Form Pengajuan"
                description="Pilih konselor, tentukan waktu yang kamu inginkan, dan kirim pengajuan. Konselor bisa menyetujui atau mengusulkan waktu lain.">
                @if ($assessments->isEmpty())
                    <div class="rounded-lg border border-amber-100 bg-amber-50 p-5 text-sm">
                        <p class="font-semibold text-amber-700">Kamu belum punya assessment.</p>
                        <p class="mt-1 text-amber-600">Buat assessment terlebih dahulu sebelum mengajukan konseling.</p>
                        <a href="{{ route('konseli.assessment') }}"
                            class="mt-3 inline-flex rounded-lg bg-amber-500 px-4 py-2 text-sm font-semibold text-white hover:bg-amber-600">
                            Buat Assessment
                        </a>
                    </div>
                @elseif ($konselors->isEmpty())
                    <div class="rounded-lg border border-slate-100 bg-slate-50 p-5 text-sm text-slate-500">
                        Belum ada konselor aktif saat ini. Coba kembali beberapa saat lagi.
                    </div>
                @else
                    @php
                        $selectedKonselor = old('id_konselor');
                    @endphp
                    <form method="POST" action="{{ route('konseli.pengajuan.store') }}" class="space-y-5">
                        @csrf

                        {{-- Assessment --}}
                        <div>
                            <label for="id_assessment" class="mb-2 block text-sm font-semibold text-kc-heading">
                                Assessment <span class="text-red-400">*</span>
                            </label>
                            <select id="id_assessment" name="id_assessment"
                                class="w-full rounded-lg border px-4 py-3 text-sm outline-none transition focus:border-indigo-400 focus:ring-4 focus:ring-indigo-100 {{ $errors->has('id_assessment') ? 'border-red-300' : 'border-slate-200' }}">
                                <option value="">Pilih assessment</option>
                                @foreach ($assessments as $a)
                                    <option value="{{ $a['id'] }}" @selected(old('id_assessment') == $a['id'])>{{ $a['label'] }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_assessment')
                                <p class="mt-1 text-xs font-medium text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Konselor --}}
                        <div>
                            <div class="mb-3 flex flex-wrap items-end justify-between gap-3">
                                <div>
                                    <p class="text-sm font-semibold text-kc-heading">
                                        Pilih Konselor <span class="text-red-400">*</span>
                                    </p>
                                    <p class="mt-1 text-xs text-slate-400">
                                        Geser kartu untuk melihat profil singkat sebelum memilih konselor.
                                    </p>
                                </div>
                                <p id="selected-konselor-label" class="text-xs font-semibold text-indigo-600">
                                    {{ $selectedKonselor ? 'Konselor sudah dipilih' : 'Belum memilih konselor' }}
                                </p>
                            </div>

                            <input id="id_konselor" type="hidden" name="id_konselor" value="{{ $selectedKonselor }}">

                            <div class="konselor-carousel" aria-label="Pilihan konselor">
                                @foreach ($konselors as $k)
                                    @php
                                        $isSelected = (string) $selectedKonselor === (string) $k['id'];
                                        $initial = strtoupper(mb_substr($k['nama'], 0, 1));
                                        $peminatanTags = collect(explode(',', $k['peminatan'] ?? ''))
                                            ->map(fn($tag) => trim($tag))
                                            ->filter()
                                            ->take(3);
                                    @endphp
                                    <button type="button" data-konselor-card data-konselor-id="{{ $k['id'] }}"
                                        data-konselor-name="{{ $k['nama'] }}"
                                        aria-pressed="{{ $isSelected ? 'true' : 'false' }}"
                                        class="konselor-card group rounded-2xl border p-5 text-left transition-all duration-200 hover:-translate-y-0.5 hover:border-indigo-200 hover:bg-indigo-50/40 {{ $isSelected ? 'is-selected' : 'border-slate-200 bg-white' }}">
                                        <div class="flex items-start gap-4">
                                            @if ($k['foto_url'])
                                                <img src="{{ $k['foto_url'] }}" alt="Foto {{ $k['nama'] }}"
                                                    class="h-16 w-16 rounded-2xl object-cover ring-4 ring-indigo-50">
                                            @else
                                                <span
                                                    class="grid h-16 w-16 rounded-2xl bg-gradient-to-br from-indigo-500 to-cyan-300 text-xl font-bold text-white place-items-center ring-4 ring-indigo-50">
                                                    {{ $initial }}
                                                </span>
                                            @endif

                                            <div class="min-w-0 flex-1">
                                                <p class="truncate text-base font-bold text-kc-heading">{{ $k['nama'] }}</p>
                                                <p class="mt-0.5 text-xs font-semibold text-indigo-500">{{ $k['spesialisasi'] }}</p>
                                            </div>
                                        </div>

                                        <p class="mt-4 min-h-[60px] text-sm leading-6 text-slate-500">
                                            {{ $k['catatan'] ?: 'Konselor ini belum menambahkan ringkasan kemampuan, namun sudah tersedia untuk menerima pengajuan konseling.' }}
                                        </p>

                                        <div class="mt-4 flex flex-wrap gap-2">
                                            @forelse ($peminatanTags as $tag)
                                                <span
                                                    class="rounded-full bg-indigo-50 px-3 py-1 text-[11px] font-semibold text-indigo-600">
                                                    {{ $tag }}
                                                </span>
                                            @empty
                                                <span class="rounded-full bg-slate-100 px-3 py-1 text-[11px] font-semibold text-slate-500">
                                                    Peminatan belum diisi
                                                </span>
                                            @endforelse
                                        </div>

                                        <div class="mt-5 flex items-center justify-between border-t border-slate-100 pt-4">
                                            <span class="text-xs text-slate-400">Kontak</span>
                                            <span class="max-w-[160px] truncate text-xs font-semibold text-kc-heading">
                                                {{ $k['whatsapp'] ?: $k['no_hp'] }}
                                            </span>
                                        </div>

                                        <span data-konselor-check
                                            class="konselor-card-check mt-4 hidden rounded-lg bg-indigo-600 px-3 py-2 text-center text-xs font-semibold text-white">
                                            Terpilih
                                        </span>
                                    </button>
                                @endforeach
                            </div>
                            @error('id_konselor')
                                <p class="mt-1 text-xs font-medium text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Usulan Jadwal --}}
                        <div>
                            <p class="mb-3 text-sm font-semibold text-kc-heading">
                                Usulan Waktu <span class="text-red-400">*</span>
                            </p>
                            <p class="mb-3 text-xs text-slate-400">Konselor bisa menyetujui atau mengusulkan waktu lain
                                jika tidak tersedia.</p>
                            <div class="grid gap-4 sm:grid-cols-3">
                                <div>
                                    <label for="tanggal_usulan"
                                        class="mb-1.5 block text-xs font-medium text-slate-500">Tanggal</label>
                                    <input type="date" id="tanggal_usulan" name="tanggal_usulan"
                                        min="{{ now()->toDateString() }}" value="{{ old('tanggal_usulan') }}"
                                        class="w-full rounded-lg border px-4 py-3 text-sm outline-none transition focus:border-indigo-400 focus:ring-4 focus:ring-indigo-100 {{ $errors->has('tanggal_usulan') ? 'border-red-300' : 'border-slate-200' }}" />
                                    @error('tanggal_usulan')
                                        <p class="mt-1 text-xs font-medium text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="jam_usulan"
                                        class="mb-1.5 block text-xs font-medium text-slate-500">Jam</label>
                                    <input type="time" id="jam_usulan" name="jam_usulan"
                                        value="{{ old('jam_usulan') }}"
                                        class="w-full rounded-lg border px-4 py-3 text-sm outline-none transition focus:border-indigo-400 focus:ring-4 focus:ring-indigo-100 {{ $errors->has('jam_usulan') ? 'border-red-300' : 'border-slate-200' }}" />
                                    @error('jam_usulan')
                                        <p class="mt-1 text-xs font-medium text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="tipe_konseling_usulan"
                                        class="mb-1.5 block text-xs font-medium text-slate-500">Tipe</label>
                                    <select id="tipe_konseling_usulan" name="tipe_konseling_usulan"
                                        class="w-full rounded-lg border px-4 py-3 text-sm outline-none transition focus:border-indigo-400 focus:ring-4 focus:ring-indigo-100 {{ $errors->has('tipe_konseling_usulan') ? 'border-red-300' : 'border-slate-200' }}">
                                        <option value="">Pilih tipe</option>
                                        <option value="online" @selected(old('tipe_konseling_usulan') === 'online')>Online</option>
                                        <option value="offline" @selected(old('tipe_konseling_usulan') === 'offline')>Offline</option>
                                    </select>
                                    @error('tipe_konseling_usulan')
                                        <p class="mt-1 text-xs font-medium text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-wrap gap-3 pt-2">
                            <button type="submit"
                                class="rounded-lg bg-indigo-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-indigo-700">
                                Kirim Pengajuan
                            </button>
                            <a href="{{ route('konseli.dashboard') }}"
                                class="rounded-lg border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
                                Kembali
                            </a>
                        </div>
                    </form>
                @endif
            </x-dashboard.form-card>

            <aside class="space-y-5">
                <article class="kc-card p-6">
                    <h2 class="text-base font-semibold text-kc-heading">Alur Pengajuan</h2>
                    <ol class="mt-5 space-y-4">
                        @foreach ([['Kirim pengajuan', 'Pilih konselor & usulkan waktu.'], ['Konselor merespons', 'Disetujui, reschedule, atau ditolak.'], ['Jika reschedule', 'Kamu konfirmasi waktu baru dari konselor.'], ['Jadwal terkonfirmasi', 'Konseling berlangsung sesuai jadwal.']] as $i => [$title, $desc])
                            <li class="flex gap-3">
                                <span
                                    class="mt-0.5 grid h-6 w-6 shrink-0 place-items-center rounded-full bg-indigo-100 text-xs font-bold text-indigo-600">{{ $i + 1 }}</span>
                                <div>
                                    <p class="text-sm font-semibold text-kc-heading">{{ $title }}</p>
                                    <p class="mt-0.5 text-xs text-slate-400">{{ $desc }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ol>
                </article>

                <article class="kc-card p-6">
                    <h2 class="text-base font-semibold text-kc-heading">Konselor Tersedia</h2>
                    <p class="mt-1 text-xs leading-5 text-slate-400">
                        Ringkasan cepat konselor aktif yang dapat kamu pilih pada form.
                    </p>
                    <div class="mt-4 space-y-3">
                        @forelse ($konselors as $k)
                            <div class="rounded-xl border border-slate-100 p-4">
                                <div class="flex items-center gap-3">
                                    @if ($k['foto_url'])
                                        <img src="{{ $k['foto_url'] }}" alt="Foto {{ $k['nama'] }}"
                                            class="h-10 w-10 rounded-xl object-cover">
                                    @else
                                        <span
                                            class="grid h-10 w-10 place-items-center rounded-xl bg-indigo-50 text-sm font-bold text-indigo-600">
                                            {{ strtoupper(mb_substr($k['nama'], 0, 1)) }}
                                        </span>
                                    @endif
                                    <div class="min-w-0">
                                        <p class="truncate text-sm font-semibold text-kc-heading">{{ $k['nama'] }}</p>
                                        <p class="mt-0.5 truncate text-xs text-slate-400">{{ $k['spesialisasi'] }}</p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-slate-400">Belum ada konselor aktif.</p>
                        @endforelse
                    </div>
                </article>
            </aside>
        </div>
    </section>

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
                    card.classList.toggle('border-slate-200', !isActive);
                    card.classList.toggle('bg-white', !isActive);
                    card.querySelector('[data-konselor-check]')?.classList.toggle('hidden', !isActive);
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
