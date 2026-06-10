<x-app-layout>
    <x-slot name="dashboardRole">konseli</x-slot>
    <x-slot name="headerTitle">Ajukan Konseling</x-slot>

    <section class="space-y-6">
        <x-dashboard.page-header title="Ajukan Konseling"
            subtitle="Pilih konselor dan jadwal yang sesuai dengan kebutuhanmu." />

        @if (session('success'))
            <div class="kc-card border-emerald-100 bg-emerald-50 p-4 text-sm font-medium text-emerald-700">
                {{ session('success') }}
                <a href="{{ route('konseli.riwayat') }}" class="ml-2 font-semibold text-emerald-800 underline">Lihat
                    riwayat</a>
            </div>
        @endif

        @if ($errors->any())
            <div class="kc-card border-red-100 bg-red-50 p-4 text-sm font-medium text-red-700">
                {{ $errors->first() }}
            </div>
        @endif

        <article class="kc-card p-5">
            <div class="grid gap-3 md:grid-cols-4">
                @foreach ([['1', 'Assessment'], ['2', 'Pilih Konselor'], ['3', 'Pilih Jadwal'], ['4', 'Kirim Pengajuan']] as [$number, $label])
                    <div class="flex items-center gap-3 rounded-lg bg-indigo-50 px-4 py-3">
                        <span
                            class="grid h-8 w-8 place-items-center rounded-lg bg-indigo-600 text-sm font-bold text-white">{{ $number }}</span>
                        <span class="text-sm font-semibold text-indigo-700">{{ $label }}</span>
                    </div>
                @endforeach
            </div>
        </article>

        <div class="grid gap-6 xl:grid-cols-[1fr_380px]">
            <x-dashboard.form-card title="Form Pengajuan"
                description="Pastikan assessment, konselor, dan jadwal sudah sesuai sebelum mengirim pengajuan.">
                {{-- Cek apakah ada assessment dulu --}}
                @if ($assessments->isEmpty())
                    <div class="rounded-lg border border-amber-100 bg-amber-50 p-5 text-sm">
                        <p class="font-semibold text-amber-700">Kamu belum punya assessment.</p>
                        <p class="mt-1 text-amber-600">Buat assessment terlebih dahulu sebelum mengajukan konseling.</p>
                        <a href="{{ route('konseli.assessment') }}"
                            class="mt-3 inline-flex rounded-lg bg-amber-500 px-4 py-2 text-sm font-semibold text-white hover:bg-amber-600">
                            Buat Assessment Sekarang
                        </a>
                    </div>
                @else
                    <form method="POST" action="{{ route('konseli.pengajuan.store') }}" class="space-y-5">
                        @csrf

                        {{-- Assessment --}}
                        <div>
                            <label for="id_assessment" class="mb-2 block text-sm font-semibold text-kc-heading">Pilih
                                Assessment</label>
                            <select id="id_assessment" name="id_assessment"
                                class="w-full rounded-lg border px-4 py-3 text-sm outline-none transition focus:border-indigo-400 focus:ring-4 focus:ring-indigo-100 {{ $errors->has('id_assessment') ? 'border-red-300' : 'border-slate-200' }}">
                                <option value="">Pilih assessment yang ingin digunakan</option>
                                @foreach ($assessments as $a)
                                    <option value="{{ $a['id'] }}" @selected(old('id_assessment') == $a['id'])>
                                        {{ $a['label'] }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_assessment')
                                <p class="mt-1 text-xs font-medium text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Konselor & Jadwal --}}
                        <div class="grid gap-5 md:grid-cols-2">
                            <div>
                                <label for="id_konselor" class="mb-2 block text-sm font-semibold text-kc-heading">Pilih
                                    Konselor</label>
                                <select id="id_konselor" name="id_konselor"
                                    class="w-full rounded-lg border px-4 py-3 text-sm outline-none transition focus:border-indigo-400 focus:ring-4 focus:ring-indigo-100 {{ $errors->has('id_konselor') ? 'border-red-300' : 'border-slate-200' }}">
                                    <option value="">Pilih konselor</option>
                                    @foreach ($konselors as $k)
                                        <option value="{{ $k['id'] }}" @selected(old('id_konselor') == $k['id'])>
                                            {{ $k['nama'] }} — {{ $k['spesialisasi'] }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_konselor')
                                    <p class="mt-1 text-xs font-medium text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="id_jadwal" class="mb-2 block text-sm font-semibold text-kc-heading">Pilih
                                    Jadwal</label>
                                @if ($jadwals->isEmpty())
                                    <p class="rounded-lg border border-slate-200 px-4 py-3 text-sm text-slate-400">
                                        Belum ada jadwal tersedia saat ini.
                                    </p>
                                @else
                                    <select id="id_jadwal" name="id_jadwal"
                                        class="w-full rounded-lg border px-4 py-3 text-sm outline-none transition focus:border-indigo-400 focus:ring-4 focus:ring-indigo-100 {{ $errors->has('id_jadwal') ? 'border-red-300' : 'border-slate-200' }}">
                                        <option value="">Pilih jadwal</option>
                                        @foreach ($jadwals as $j)
                                            <option value="{{ $j['id'] }}" @selected(old('id_jadwal') == $j['id'])>
                                                {{ $j['label'] }} ({{ $j['tipe'] }})
                                            </option>
                                        @endforeach
                                    </select>
                                @endif
                                @error('id_jadwal')
                                    <p class="mt-1 text-xs font-medium text-red-500">{{ $message }}</p>
                                @enderror
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

            <aside class="space-y-6">
                <article class="kc-card p-6">
                    <h2 class="text-lg font-semibold text-kc-heading">Ringkasan Pengajuan</h2>
                    <div class="mt-5 space-y-4 text-sm">
                        <div class="rounded-lg bg-indigo-50 p-4">
                            <p class="font-semibold text-indigo-700">Status awal</p>
                            <p class="mt-1 text-slate-500">Pengajuan akan masuk sebagai menunggu persetujuan konselor.
                            </p>
                        </div>
                        <div>
                            <p class="text-slate-400">Estimasi proses</p>
                            <p class="mt-1 font-semibold text-kc-heading">1 x 24 jam</p>
                        </div>
                        <div>
                            <p class="text-slate-400">Langkah setelah kirim</p>
                            <p class="mt-1 leading-6">Pantau status pengajuan di riwayat konseling dan ikuti instruksi
                                jadwal yang disetujui.</p>
                        </div>
                    </div>
                </article>

                <article class="kc-card p-6">
                    <h2 class="text-lg font-semibold text-kc-heading">Ketersediaan Jadwal</h2>
                    <div class="mt-5 space-y-3">
                        @forelse ($jadwals as $j)
                            <div class="flex items-center justify-between gap-4 rounded-lg border border-slate-100 p-4">
                                <div>
                                    <p class="text-sm font-semibold text-kc-heading">{{ $j['label'] }}</p>
                                    <p class="mt-0.5 text-xs text-slate-400">{{ $j['tipe'] }}</p>
                                </div>
                                <x-dashboard.status-badge status="tersedia" />
                            </div>
                        @empty
                            <p class="text-sm text-slate-400">Belum ada jadwal tersedia.</p>
                        @endforelse
                    </div>
                </article>

                <article class="kc-card p-6">
                    <h2 class="text-lg font-semibold text-kc-heading">Konselor Tersedia</h2>
                    <div class="mt-5 space-y-4">
                        @forelse ($konselors as $k)
                            <div class="rounded-lg border border-slate-100 p-4">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <p class="font-semibold text-kc-heading">{{ $k['nama'] }}</p>
                                        <p class="mt-1 text-sm text-slate-400">{{ $k['spesialisasi'] }}</p>
                                    </div>
                                    <x-dashboard.status-badge status="tersedia" />
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
</x-app-layout>
