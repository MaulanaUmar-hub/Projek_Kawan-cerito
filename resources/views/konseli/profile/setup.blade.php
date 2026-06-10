<x-app-layout>
    <x-slot name="dashboardRole">konseli</x-slot>
    <x-slot name="headerTitle">Lengkapi Data Diri</x-slot>

    @php
        $profile = $user->konseli ?? null;
        $nama = old('nama', $user->nama);
        $asal = old('asal', $profile->asal ?? $user->asal);
        $noHp = old('no_hp', $profile->no_hp ?? $user->no_hp);
        $gender = old('gender', $profile->gender ?? $user->gender);
        $fotoUrl = $profile?->foto ? \Illuminate\Support\Facades\Storage::disk('public')->url($profile->foto) : null;
        $initial = strtoupper(mb_substr($nama ?: 'K', 0, 1));
        $genderLabel = match ($gender) {
            'L' => 'Laki-laki',
            'P' => 'Perempuan',
            'N' => 'Tidak ingin memberi tahu',
            default => 'Belum dipilih',
        };
        $completed = collect([$nama, $asal, $noHp, $gender])->filter(fn ($item) => filled($item))->count();
        $progress = round(($completed / 4) * 100);
    @endphp

    <section class="space-y-6">
        <div class="kc-card overflow-hidden">
            <div class="grid gap-6 p-6 lg:grid-cols-[1fr_280px] lg:p-8">
                <div>
                    <p class="text-sm font-semibold text-indigo-500">Langkah Awal Konseling</p>
                    <h2 class="mt-3 text-2xl font-bold text-kc-heading md:text-3xl">Lengkapi Data Diri</h2>
                    <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-500">
                        Lengkapi profil agar proses konseling berjalan lebih nyaman dan konselor memahami konteks dasar kamu.
                    </p>
                </div>
                <div class="rounded-xl border border-indigo-100 bg-indigo-50 p-5">
                    <p class="text-sm font-semibold text-indigo-600">Kelengkapan Profil</p>
                    <p class="mt-2 text-3xl font-bold text-kc-heading">{{ $progress }}%</p>
                    <div class="mt-4 h-2 rounded-full bg-white">
                        <div class="h-2 rounded-full bg-indigo-500" style="width: {{ $progress }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid gap-6 xl:grid-cols-[360px_1fr]">
            <article class="kc-card overflow-hidden">
                <div class="h-24 bg-gradient-to-br from-indigo-500 via-[#8b8eff] to-cyan-300"></div>
                <div class="px-5 pb-6">
                    <div class="-mt-14 flex flex-col items-center text-center">
                        @if ($fotoUrl)
                            <img
                                src="{{ $fotoUrl }}"
                                alt="Foto profil {{ $nama }}"
                                class="h-28 w-28 rounded-full border-4 border-white object-cover shadow-lg shadow-indigo-100"
                            >
                        @else
                            <div class="flex h-28 w-28 items-center justify-center rounded-full border-4 border-white bg-indigo-500 text-4xl font-bold text-white shadow-lg shadow-indigo-100">
                                {{ $initial }}
                            </div>
                        @endif

                        <h2 class="mt-4 text-xl font-bold text-kc-heading">{{ $nama ?: 'Nama Konseli' }}</h2>
                        <p class="mt-1 text-sm text-slate-400">{{ $user->email }}</p>
                    </div>

                    <div class="mt-6 space-y-3">
                        <div class="rounded-2xl border border-slate-100 p-4">
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Asal</p>
                            <p class="mt-2 text-sm font-semibold leading-6 text-kc-heading">{{ $asal ?: 'Belum diisi' }}</p>
                        </div>
                        <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-1">
                            <div class="rounded-2xl border border-slate-100 p-4">
                                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Gender</p>
                                <p class="mt-2 text-sm font-semibold leading-6 text-kc-heading">{{ $genderLabel }}</p>
                            </div>
                            <div class="rounded-2xl border border-slate-100 p-4">
                                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Nomor HP</p>
                                <p class="mt-2 text-sm font-semibold leading-6 text-kc-heading">{{ $noHp ?: 'Belum diisi' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </article>

            <article class="kc-card p-5 sm:p-6">
                <div class="mb-5">
                    <h2 class="text-lg font-semibold text-kc-heading">Profil Konseli</h2>
                    <p class="mt-1 max-w-2xl text-sm leading-6 text-slate-500">
                        Data ini membantu konselor memahami informasi dasar kamu sebelum konseling dimulai.
                    </p>
                </div>

                <form method="POST" action="{{ route('konseli.profile.setup.store') }}" enctype="multipart/form-data" class="space-y-4" novalidate>
                    @csrf

                    <div class="rounded-2xl border border-indigo-100 bg-indigo-50/60 p-4">
                        <div class="grid gap-4 md:grid-cols-[88px_1fr] md:items-center">
                            <div class="mx-auto md:mx-0">
                                @if ($fotoUrl)
                                    <img src="{{ $fotoUrl }}" alt="Preview foto {{ $nama }}" class="h-[88px] w-[88px] rounded-2xl object-cover shadow-sm shadow-indigo-100">
                                @else
                                    <div class="flex h-[88px] w-[88px] items-center justify-center rounded-2xl bg-indigo-500 text-3xl font-bold text-white shadow-sm shadow-indigo-100">
                                        {{ $initial }}
                                    </div>
                                @endif
                            </div>
                            <div>
                                <label for="foto" class="block text-sm font-semibold text-kc-heading">
                                    Upload Foto <span class="font-normal text-slate-400">(opsional)</span>
                                </label>
                                <p class="mt-1 text-sm leading-6 text-slate-500">Gunakan foto square atau portrait agar preview tetap rapi.</p>
                                <input
                                    id="foto"
                                    type="file"
                                    name="foto"
                                    accept="image/*"
                                    class="mt-3 block w-full rounded-xl border border-indigo-100 bg-white px-3 py-2.5 text-sm text-slate-500 file:mr-3 file:rounded-lg file:border-0 file:bg-indigo-500 file:px-3 file:py-2 file:text-sm file:font-semibold file:text-white"
                                >
                            </div>
                        </div>
                        @error('foto')
                            <p class="mt-2 text-sm font-medium text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="nama" class="mb-2 block text-sm font-semibold text-kc-heading">Nama Lengkap</label>
                        <input
                            id="nama"
                            type="text"
                            name="nama"
                            value="{{ $nama }}"
                            placeholder="Masukkan nama lengkap"
                            class="w-full rounded-xl border px-4 py-3 text-sm outline-none transition focus:ring-4 {{ $errors->has('nama') ? 'border-red-300 focus:border-red-400 focus:ring-red-100' : 'border-slate-200 focus:border-indigo-400 focus:ring-indigo-100' }}"
                        >
                        @error('nama')
                            <p class="mt-2 text-sm font-medium text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label for="gender" class="mb-2 block text-sm font-semibold text-kc-heading">Gender</label>
                            <select
                                id="gender"
                                name="gender"
                                class="w-full rounded-xl border px-4 py-3 text-sm outline-none transition focus:ring-4 {{ $errors->has('gender') ? 'border-red-300 focus:border-red-400 focus:ring-red-100' : 'border-slate-200 focus:border-indigo-400 focus:ring-indigo-100' }}"
                            >
                                <option value="">Pilih gender</option>
                                <option value="L" @selected($gender === 'L')>Laki-laki</option>
                                <option value="P" @selected($gender === 'P')>Perempuan</option>
                                <option value="N" @selected($gender === 'N')>Tidak ingin memberi tahu</option>
                            </select>
                            @error('gender')
                                <p class="mt-2 text-sm font-medium text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="asal" class="mb-2 block text-sm font-semibold text-kc-heading">Asal</label>
                            <input
                                id="asal"
                                type="text"
                                name="asal"
                                value="{{ $asal }}"
                                placeholder="Contoh: Universitas / Sekolah / Kota"
                                class="w-full rounded-xl border px-4 py-3 text-sm outline-none transition focus:ring-4 {{ $errors->has('asal') ? 'border-red-300 focus:border-red-400 focus:ring-red-100' : 'border-slate-200 focus:border-indigo-400 focus:ring-indigo-100' }}"
                            >
                            @error('asal')
                                <p class="mt-2 text-sm font-medium text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="no_hp" class="mb-2 block text-sm font-semibold text-kc-heading">Nomor HP</label>
                        <input
                            id="no_hp"
                            type="text"
                            name="no_hp"
                            value="{{ $noHp }}"
                            placeholder="Contoh: 081234567890"
                            class="w-full rounded-xl border px-4 py-3 text-sm outline-none transition focus:ring-4 {{ $errors->has('no_hp') ? 'border-red-300 focus:border-red-400 focus:ring-red-100' : 'border-slate-200 focus:border-indigo-400 focus:ring-indigo-100' }}"
                        >
                        @error('no_hp')
                            <p class="mt-2 text-sm font-medium text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex flex-col-reverse gap-3 pt-2 sm:flex-row sm:justify-end">
                        <a href="{{ route('konseli.dashboard') }}" class="rounded-xl border border-slate-200 px-5 py-3 text-center text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
                            Lewati dulu
                        </a>
                        <button type="submit" class="rounded-xl bg-indigo-500 px-5 py-3 text-sm font-semibold text-white shadow-sm shadow-indigo-100 transition hover:bg-indigo-600">
                            Simpan dan Lanjutkan
                        </button>
                    </div>
                </form>
            </article>
        </div>
    </section>
</x-app-layout>
