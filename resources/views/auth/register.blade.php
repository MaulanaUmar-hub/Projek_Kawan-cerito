@extends('layouts.guest')

@section('content')
    <form method="POST" action="{{ route('register') }}" novalidate>
        @csrf

        @if ($errors->any())
            <div class="mb-7 rounded-2xl border border-red-100 bg-red-50 px-5 py-4 text-sm font-medium text-red-600">
                Periksa kembali data pendaftaranmu.
            </div>
        @endif

        <div class="space-y-6">
            <div>
                <label for="nama" class="mb-3 block font-semibold text-[#111827]">
                    Nama
                </label>
                <input
                    id="nama"
                    type="text"
                    name="nama"
                    value="{{ old('nama') }}"
                    placeholder="Masukkan nama Anda"
                    class="w-full rounded-2xl border px-5 py-4 outline-none transition-all duration-300 focus:ring-4 {{ $errors->has('nama') ? 'border-red-400 focus:border-red-400 focus:ring-red-100' : 'border-gray-200 focus:border-indigo-500 focus:ring-indigo-100' }}"
                >
                @error('nama')
                    <p class="mt-2 text-sm font-medium text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="mb-3 block font-semibold text-[#111827]">
                    Email
                </label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="Masukkan email Anda"
                    class="w-full rounded-2xl border px-5 py-4 outline-none transition-all duration-300 focus:ring-4 {{ $errors->has('email') ? 'border-red-400 focus:border-red-400 focus:ring-red-100' : 'border-gray-200 focus:border-indigo-500 focus:ring-indigo-100' }}"
                >
                @error('email')
                    <p class="mt-2 text-sm font-medium text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="mb-3 block font-semibold text-[#111827]">
                    Password
                </label>
                <input
                    id="password"
                    type="password"
                    name="password"
                    placeholder="Minimal 8 karakter"
                    class="w-full rounded-2xl border px-5 py-4 outline-none transition-all duration-300 focus:ring-4 {{ $errors->has('password') ? 'border-red-400 focus:border-red-400 focus:ring-red-100' : 'border-gray-200 focus:border-indigo-500 focus:ring-indigo-100' }}"
                >
                @error('password')
                    <p class="mt-2 text-sm font-medium text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="mb-3 block font-semibold text-[#111827]">
                    Konfirmasi Password
                </label>
                <input
                    id="password_confirmation"
                    type="password"
                    name="password_confirmation"
                    placeholder="Ulangi password Anda"
                    class="w-full rounded-2xl border px-5 py-4 outline-none transition-all duration-300 focus:ring-4 {{ $errors->has('password_confirmation') ? 'border-red-400 focus:border-red-400 focus:ring-red-100' : 'border-gray-200 focus:border-indigo-500 focus:ring-indigo-100' }}"
                >
                @error('password_confirmation')
                    <p class="mt-2 text-sm font-medium text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <label class="flex items-start gap-3 rounded-2xl border border-indigo-100 bg-indigo-50/60 px-4 py-4 text-sm text-slate-600">
                <input
                    type="checkbox"
                    name="hide_name"
                    value="1"
                    @checked(old('hide_name'))
                    class="mt-1 rounded border-slate-300 text-indigo-500 focus:ring-indigo-200"
                >
                <span>
                    <span class="block font-semibold text-[#111827]">Sembunyikan nama saya</span>
                    <span class="mt-1 block leading-6">Nama asli tetap tersimpan, tetapi tampilan publik dapat memakai nama anonim.</span>
                </span>
            </label>
        </div>

        <div class="mt-10 flex flex-col items-center justify-between gap-5 md:flex-row">
            <a href="{{ route('login') }}" class="text-gray-500 transition-all duration-300 hover:text-indigo-500">
                Sudah punya akun?
            </a>

            <button
                type="submit"
                class="w-full rounded-2xl bg-gradient-to-r from-[#5B67F1] to-[#7482FF] px-10 py-4 font-bold text-white shadow-lg shadow-indigo-100 transition-all duration-300 hover:scale-[1.01] md:w-auto"
            >
                Daftar
            </button>
        </div>
    </form>
@endsection
