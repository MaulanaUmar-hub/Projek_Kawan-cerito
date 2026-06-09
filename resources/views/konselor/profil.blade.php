<x-app-layout>
    <x-slot name="dashboardRole">konselor</x-slot>
    <x-slot name="headerTitle">Profil Konselor</x-slot>

    @php
        $initial = strtoupper(mb_substr($profile['nama'], 0, 1));
        $note = $profile['catatan_profil'] ?: 'Tulis status singkat agar konseli lebih mudah mengenal pendekatan konseling Anda.';
        $peminatan = $profile['peminatan'] ?: 'Belum ditambahkan';
    @endphp

    <section class="space-y-6">
        <x-dashboard.page-header
            title="Profil Konselor"
            subtitle="Atur tampilan profil yang membantu konseli mengenal Anda dengan nyaman."
        />

        @if (session('success'))
            <div class="rounded-2xl border border-emerald-100 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-600">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="rounded-2xl border border-red-100 bg-red-50 px-5 py-4 text-sm font-medium text-red-600">
                Periksa kembali data profil yang Anda isi.
            </div>
        @endif

        <div class="grid gap-6 xl:grid-cols-[420px_1fr]">
            <article class="kc-card overflow-hidden">
                <div class="h-32 bg-gradient-to-br from-indigo-500 via-[#7f86ff] to-cyan-300"></div>

                <div class="px-6 pb-6">
                    <div class="-mt-16 flex flex-col items-center text-center">
                        <div class="relative">
                            @if ($profile['foto_url'])
                                <img
                                    src="{{ $profile['foto_url'] }}"
                                    alt="Foto profil {{ $profile['nama'] }}"
                                    class="h-32 w-32 rounded-full border-4 border-white object-cover shadow-lg shadow-indigo-100"
                                >
                            @else
                                <div class="flex h-32 w-32 items-center justify-center rounded-full border-4 border-white bg-indigo-500 text-4xl font-bold text-white shadow-lg shadow-indigo-100">
                                    {{ $initial }}
                                </div>
                            @endif
                            <span class="absolute bottom-2 right-2 rounded-full bg-emerald-400 p-2 ring-4 ring-white"></span>
                        </div>

                        <h2 class="mt-4 text-xl font-bold text-kc-heading">{{ $profile['nama'] }}</h2>
                        <p class="mt-1 text-sm text-slate-400">{{ $profile['email'] }}</p>

                        <div class="mt-4">
                            <x-dashboard.status-badge :status="$profile['status']" />
                        </div>
                    </div>

                    <div class="mt-6 rounded-2xl bg-slate-50 p-5">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Note Status</p>
                        <p class="mt-2 text-sm leading-6 text-kc-heading">{{ $note }}</p>
                    </div>

                    <div class="mt-4 grid gap-3">
                        <div class="rounded-2xl border border-slate-100 p-4">
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Peminatan</p>
                            <p class="mt-2 text-sm font-semibold leading-6 text-kc-heading">{{ $peminatan }}</p>
                        </div>
                        <div class="rounded-2xl border border-slate-100 p-4">
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Spesialisasi</p>
                            <p class="mt-2 text-sm font-semibold leading-6 text-kc-heading">{{ $profile['spesialisasi'] }}</p>
                        </div>
                        <div class="rounded-2xl border border-slate-100 p-4">
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">WhatsApp</p>
                            <p class="mt-2 break-all text-sm font-semibold leading-6 text-kc-heading">{{ $profile['link_whatsapp'] }}</p>
                        </div>
                    </div>
                </div>
            </article>

            <article class="kc-card p-6">
                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-kc-heading">Edit Profil Publik</h2>
                    <p class="mt-1 text-sm leading-6 text-slate-500">
                        Gunakan foto yang jelas dan note yang hangat agar konseli merasa lebih percaya sebelum memulai konseling.
                    </p>
                </div>

                <form method="POST" action="{{ route('konselor.profil.update') }}" enctype="multipart/form-data" class="space-y-5">
                    @csrf
                    @method('PATCH')

                    <div class="rounded-2xl border border-indigo-100 bg-indigo-50/60 p-5">
                        <label for="foto" class="block text-sm font-semibold text-kc-heading">Foto Profil</label>
                        <p class="mt-1 text-sm leading-6 text-slate-500">
                            Pilih foto square atau portrait. Maksimal 2 MB.
                        </p>
                        <input
                            id="foto"
                            name="foto"
                            type="file"
                            accept="image/*"
                            class="mt-4 block w-full rounded-xl border border-indigo-100 bg-white px-4 py-3 text-sm text-slate-500 file:mr-4 file:rounded-lg file:border-0 file:bg-indigo-500 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white"
                        >
                        @error('foto')
                            <p class="mt-2 text-sm font-medium text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="catatan_profil" class="mb-2 block text-sm font-semibold text-kc-heading">Note Status</label>
                        <textarea
                            id="catatan_profil"
                            name="catatan_profil"
                            rows="4"
                            maxlength="500"
                            placeholder="Contoh: Saya mendampingi konseli dengan pendekatan yang tenang, suportif, dan fokus pada langkah kecil yang realistis."
                            class="w-full rounded-xl border px-4 py-3 text-sm leading-6 outline-none transition focus:ring-4 {{ $errors->has('catatan_profil') ? 'border-red-300 focus:border-red-400 focus:ring-red-100' : 'border-slate-200 focus:border-indigo-400 focus:ring-indigo-100' }}"
                        >{{ old('catatan_profil', $profile['catatan_profil']) }}</textarea>
                        <div class="mt-2 flex items-center justify-between gap-3 text-xs text-slate-400">
                            <span>Dipakai sebagai deskripsi singkat di profil konselor.</span>
                            <span>Maks. 500 karakter</span>
                        </div>
                        @error('catatan_profil')
                            <p class="mt-2 text-sm font-medium text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid gap-5 lg:grid-cols-2">
                        <div>
                            <label for="peminatan" class="mb-2 block text-sm font-semibold text-kc-heading">Peminatan</label>
                            <input
                                id="peminatan"
                                name="peminatan"
                                type="text"
                                value="{{ old('peminatan', $profile['peminatan']) }}"
                                placeholder="Contoh: Remaja, stres akademik, kecemasan"
                                class="w-full rounded-xl border px-4 py-3 text-sm outline-none transition focus:ring-4 {{ $errors->has('peminatan') ? 'border-red-300 focus:border-red-400 focus:ring-red-100' : 'border-slate-200 focus:border-indigo-400 focus:ring-indigo-100' }}"
                            >
                            @error('peminatan')
                                <p class="mt-2 text-sm font-medium text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="spesialisasi" class="mb-2 block text-sm font-semibold text-kc-heading">Spesialisasi</label>
                            <input
                                id="spesialisasi"
                                name="spesialisasi"
                                type="text"
                                value="{{ old('spesialisasi', $profile['spesialisasi']) }}"
                                placeholder="Contoh: Konseling remaja"
                                class="w-full rounded-xl border px-4 py-3 text-sm outline-none transition focus:ring-4 {{ $errors->has('spesialisasi') ? 'border-red-300 focus:border-red-400 focus:ring-red-100' : 'border-slate-200 focus:border-indigo-400 focus:ring-indigo-100' }}"
                            >
                            @error('spesialisasi')
                                <p class="mt-2 text-sm font-medium text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="rounded-2xl border border-slate-100 p-5">
                        <h3 class="text-sm font-semibold text-kc-heading">Informasi Akun</h3>
                        <dl class="mt-4 grid gap-4 text-sm sm:grid-cols-2">
                            <div>
                                <dt class="text-slate-400">Nama</dt>
                                <dd class="mt-1 font-semibold text-kc-heading">{{ $profile['nama'] }}</dd>
                            </div>
                            <div>
                                <dt class="text-slate-400">Email</dt>
                                <dd class="mt-1 font-semibold text-kc-heading">{{ $profile['email'] }}</dd>
                            </div>
                            <div>
                                <dt class="text-slate-400">Nomor HP</dt>
                                <dd class="mt-1 font-semibold text-kc-heading">{{ $profile['no_hp'] }}</dd>
                            </div>
                            <div>
                                <dt class="text-slate-400">Gender</dt>
                                <dd class="mt-1 font-semibold text-kc-heading">{{ $profile['gender'] }}</dd>
                            </div>
                        </dl>
                    </div>

                    <div class="flex flex-col gap-3 pt-2 sm:flex-row sm:justify-end">
                        <a
                            href="{{ route('konselor.dashboard') }}"
                            class="inline-flex justify-center rounded-xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-500 transition hover:bg-slate-50"
                        >
                            Kembali
                        </a>
                        <button
                            type="submit"
                            class="inline-flex justify-center rounded-xl bg-indigo-500 px-5 py-3 text-sm font-semibold text-white shadow-sm shadow-indigo-100 transition hover:bg-indigo-600"
                        >
                            Simpan Profil
                        </button>
                    </div>
                </form>
            </article>
        </div>
    </section>
</x-app-layout>
