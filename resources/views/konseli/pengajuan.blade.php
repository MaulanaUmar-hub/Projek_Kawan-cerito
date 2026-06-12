<x-app-layout>
    <x-slot name="dashboardRole">konseli</x-slot>
    <x-slot name="headerTitle">Ajukan Konseling</x-slot>

    <section class="space-y-6">
        <x-dashboard.page-header title="Ajukan Konseling"
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
                            <label for="id_konselor" class="mb-2 block text-sm font-semibold text-kc-heading">
                                Konselor <span class="text-red-400">*</span>
                            </label>
                            <select id="id_konselor" name="id_konselor"
                                class="w-full rounded-lg border px-4 py-3 text-sm outline-none transition focus:border-indigo-400 focus:ring-4 focus:ring-indigo-100 {{ $errors->has('id_konselor') ? 'border-red-300' : 'border-slate-200' }}">
                                <option value="">Pilih konselor</option>
                                @foreach ($konselors as $k)
                                    <option value="{{ $k['id'] }}" @selected(old('id_konselor') == $k['id'])>
                                        {{ $k['nama'] }}{{ $k['spesialisasi'] !== '-' ? ' — ' . $k['spesialisasi'] : '' }}
                                    </option>
                                @endforeach
                            </select>
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
                    <div class="mt-4 space-y-3">
                        @forelse ($konselors as $k)
                            <div class="rounded-lg border border-slate-100 p-4">
                                <p class="text-sm font-semibold text-kc-heading">{{ $k['nama'] }}</p>
                                <p class="mt-0.5 text-xs text-slate-400">{{ $k['spesialisasi'] }}</p>
                            </div>
                        @empty
                            <p class="text-sm text-slate-400">Belum ada konselor aktif.</p>
                        @endforelse
                    </div>
                </article>
            </aside>
        </div>
    </section>
</x-app-layout>
