<x-app-layout>
    <x-slot name="dashboardRole">konseli</x-slot>
    <x-slot name="headerTitle">Lengkapi Data Diri</x-slot>

    <section class="space-y-6">
        <x-dashboard.page-header
            title="Lengkapi Data Diri"
            subtitle="Lengkapi profil agar proses konseling berjalan lebih nyaman."
        />

        <x-dashboard.form-card title="Profil Konseli" description="Data ini membantu konselor memahami informasi dasar kamu sebelum sesi dimulai.">
            <form method="POST" action="{{ route('konseli.profile.setup.store') }}" enctype="multipart/form-data" class="space-y-6" novalidate>
                @csrf

                <div>
                    <label for="nama" class="mb-2 block text-sm font-semibold text-kc-heading">Nama Lengkap</label>
                    <input
                        id="nama"
                        type="text"
                        name="nama"
                        value="{{ old('nama', $user->nama) }}"
                        class="w-full rounded-lg border px-4 py-3 text-sm outline-none transition focus:ring-4 {{ $errors->has('nama') ? 'border-red-300 focus:border-red-400 focus:ring-red-100' : 'border-slate-200 focus:border-indigo-400 focus:ring-indigo-100' }}"
                    >
                    @error('nama')
                        <p class="mt-2 text-sm font-medium text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid gap-6 md:grid-cols-2">
                    <div>
                        <label for="gender" class="mb-2 block text-sm font-semibold text-kc-heading">Gender</label>
                        <select
                            id="gender"
                            name="gender"
                            class="w-full rounded-lg border px-4 py-3 text-sm outline-none transition focus:ring-4 {{ $errors->has('gender') ? 'border-red-300 focus:border-red-400 focus:ring-red-100' : 'border-slate-200 focus:border-indigo-400 focus:ring-indigo-100' }}"
                        >
                            <option value="">Pilih gender</option>
                            <option value="L" @selected(old('gender', $user->gender) === 'L')>Laki-laki</option>
                            <option value="P" @selected(old('gender', $user->gender) === 'P')>Perempuan</option>
                            <option value="N" @selected(old('gender', $user->gender) === 'N')>Tidak ingin memberi tahu</option>
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
                            value="{{ old('asal', $user->asal) }}"
                            placeholder="Contoh: Universitas / Sekolah / Kota"
                            class="w-full rounded-lg border px-4 py-3 text-sm outline-none transition focus:ring-4 {{ $errors->has('asal') ? 'border-red-300 focus:border-red-400 focus:ring-red-100' : 'border-slate-200 focus:border-indigo-400 focus:ring-indigo-100' }}"
                        >
                        @error('asal')
                            <p class="mt-2 text-sm font-medium text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid gap-6 md:grid-cols-2">
                    <div>
                        <label for="no_hp" class="mb-2 block text-sm font-semibold text-kc-heading">Nomor HP</label>
                        <input
                            id="no_hp"
                            type="text"
                            name="no_hp"
                            value="{{ old('no_hp', $user->no_hp) }}"
                            placeholder="Contoh: 081234567890"
                            class="w-full rounded-lg border px-4 py-3 text-sm outline-none transition focus:ring-4 {{ $errors->has('no_hp') ? 'border-red-300 focus:border-red-400 focus:ring-red-100' : 'border-slate-200 focus:border-indigo-400 focus:ring-indigo-100' }}"
                        >
                        @error('no_hp')
                            <p class="mt-2 text-sm font-medium text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="foto" class="mb-2 block text-sm font-semibold text-kc-heading">Upload Foto <span class="font-normal text-slate-400">(opsional)</span></label>
                        <input
                            id="foto"
                            type="file"
                            name="foto"
                            accept="image/*"
                            class="w-full rounded-lg border border-slate-200 px-4 py-3 text-sm outline-none transition file:mr-4 file:rounded-md file:border-0 file:bg-indigo-50 file:px-3 file:py-2 file:text-sm file:font-semibold file:text-indigo-600 focus:border-indigo-400 focus:ring-4 focus:ring-indigo-100"
                        >
                        @error('foto')
                            <p class="mt-2 text-sm font-medium text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex flex-col-reverse gap-3 pt-2 sm:flex-row sm:justify-end">
                    <a href="{{ route('konseli.dashboard') }}" class="rounded-lg border border-slate-200 px-5 py-3 text-center text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
                        Lewati dulu
                    </a>
                    <button type="submit" class="rounded-lg bg-indigo-500 px-5 py-3 text-sm font-semibold text-white shadow-sm shadow-indigo-100 transition hover:bg-indigo-600">
                        Simpan dan Lanjutkan
                    </button>
                </div>
            </form>
        </x-dashboard.form-card>
    </section>
</x-app-layout>
