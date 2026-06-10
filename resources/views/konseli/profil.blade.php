<x-app-layout>
    <x-slot name="dashboardRole">konseli</x-slot>
    <x-slot name="headerTitle">Profil Saya</x-slot>

    @php
        $initial = strtoupper(mb_substr($profile->nama ?: 'K', 0, 1));
        $genderLabel = match ($profile->gender) {
            'L' => 'Laki-laki',
            'P' => 'Perempuan',
            'N' => 'Tidak ingin memberi tahu',
            default => 'Belum dipilih',
        };

        $profileItems = [
            ['label' => 'Nama', 'value' => $profile->nama ?: '-'],
            ['label' => 'Email', 'value' => $profile->email ?: '-'],
            ['label' => 'Asal', 'value' => $profile->asal ?: '-'],
            ['label' => 'Nomor HP', 'value' => $profile->no_hp ?: '-'],
            ['label' => 'Gender', 'value' => $genderLabel],
        ];
    @endphp

    <section class="space-y-6">
        <div class="kc-card overflow-hidden">
            <div class="grid gap-6 p-6 lg:grid-cols-[1fr_280px] lg:p-8">
                <div>
                    <p class="text-sm font-semibold text-indigo-500">Profil Konseli</p>
                    <h2 class="mt-3 text-2xl font-bold text-kc-heading md:text-3xl">Profil Saya</h2>
                    <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-500">
                        Lihat informasi dasar akun konseli kamu. Data ini membantu proses konseling berjalan lebih nyaman dan tertata.
                    </p>
                </div>
                <div class="rounded-xl border border-indigo-100 bg-indigo-50 p-5">
                    <p class="text-sm font-semibold text-indigo-600">Status Profil</p>
                    <p class="mt-2 text-2xl font-bold text-kc-heading">Aktif</p>
                    <p class="mt-2 text-sm leading-6 text-slate-500">Profil siap digunakan untuk layanan konseling.</p>
                </div>
            </div>
        </div>

        <div class="grid gap-6 xl:grid-cols-[380px_1fr]">
            <article class="kc-card overflow-hidden">
                <div class="h-28 bg-gradient-to-br from-indigo-500 via-[#8b8eff] to-cyan-300"></div>

                <div class="px-5 pb-6">
                    <div class="-mt-16 flex flex-col items-center text-center">
                        @if ($profile->foto_url)
                            <img
                                src="{{ $profile->foto_url }}"
                                alt="Foto profil {{ $profile->nama }}"
                                class="h-32 w-32 rounded-full border-4 border-white object-cover shadow-lg shadow-indigo-100"
                            >
                        @else
                            <div class="flex h-32 w-32 items-center justify-center rounded-full border-4 border-white bg-indigo-500 text-4xl font-bold text-white shadow-lg shadow-indigo-100">
                                {{ $initial }}
                            </div>
                        @endif

                        <h2 class="mt-4 text-xl font-bold text-kc-heading">{{ $profile->nama }}</h2>
                        <p class="mt-1 break-all text-sm text-slate-400">{{ $profile->email }}</p>
                    </div>

                    <div class="mt-6 rounded-2xl border border-indigo-100 bg-indigo-50/70 p-5">
                        <p class="text-xs font-semibold uppercase tracking-wide text-indigo-500">Ringkasan</p>
                        <p class="mt-3 text-sm leading-6 text-kc-heading">
                            Profil ini digunakan sebagai data dasar sebelum kamu mengajukan atau mengikuti konseling.
                        </p>
                    </div>

                    <a
                        href="{{ route('konseli.profile.setup') }}"
                        class="mt-5 inline-flex w-full justify-center rounded-xl bg-indigo-500 px-5 py-3 text-sm font-semibold text-white shadow-sm shadow-indigo-100 transition hover:bg-indigo-600"
                    >
                        Perbarui Profil
                    </a>
                </div>
            </article>

            <article class="kc-card p-5 sm:p-6">
                <div class="mb-5 flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-kc-heading">Informasi Profil</h2>
                        <p class="mt-1 max-w-2xl text-sm leading-6 text-slate-500">
                            Informasi berikut tersimpan sebagai data konseli dan dapat diperbarui melalui form profil.
                        </p>
                    </div>
                    <span class="inline-flex w-fit rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-600">
                        Tersimpan
                    </span>
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    @foreach ($profileItems as $item)
                        <div class="rounded-2xl border border-slate-100 p-4">
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">{{ $item['label'] }}</p>
                            <p class="mt-2 break-words text-sm font-semibold leading-6 text-kc-heading">{{ $item['value'] }}</p>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6 rounded-2xl bg-slate-50 p-5">
                    <h3 class="text-sm font-semibold text-kc-heading">Catatan Privasi</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-500">
                        Data profil digunakan untuk mendukung proses konseling dan tidak ditampilkan sebagai profil publik.
                    </p>
                </div>
            </article>
        </div>
    </section>
</x-app-layout>
