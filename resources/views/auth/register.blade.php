@extends('layouts.guest')

@section('content')
    <form method="POST" action="{{ route('register') }}" novalidate>
        @csrf

        @if ($errors->any())
            <div class="mb-3 rounded-2xl border border-red-100 bg-red-50 px-5 py-2.5 text-sm font-medium text-red-600">
                Periksa kembali data pendaftaranmu.
            </div>
        @endif

        <div class="space-y-3">
            <div>
                <label for="nama" class="mb-1 block font-semibold text-[#111827]">
                    Nama / Nama Samaran
                </label>
                
                <input
                    id="nama"
                    type="text"
                    name="nama"
                    value="{{ old('nama') }}"
                    placeholder="Contoh: Maulana atau Cerito01"
                    class="w-full rounded-2xl border px-5 py-2.5 outline-none transition-all duration-300 focus:ring-4 {{ $errors->has('nama') ? 'border-red-400 focus:border-red-400 focus:ring-red-100' : 'border-gray-200 focus:border-indigo-500 focus:ring-indigo-100' }}"
                >
                
                <p class="mt-0.5 text-xs leading-normal text-slate-500">
                    Boleh gunakan nama asli atau nama samaran yang membuatmu nyaman.
                </p>
                @error('nama')
                    <p class="mt-0.5 text-xs font-medium text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="mb-1 block font-semibold text-[#111827]">
                    Email
                </label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="Masukkan email Anda"
                    class="w-full rounded-2xl border px-5 py-2.5 outline-none transition-all duration-300 focus:ring-4 {{ $errors->has('email') ? 'border-red-400 focus:border-red-400 focus:ring-red-100' : 'border-gray-200 focus:border-indigo-500 focus:ring-indigo-100' }}"
                >
                @error('email')
                    <p class="mt-0.5 text-xs font-medium text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="mb-1 block font-semibold text-[#111827]">
                    Password
                </label>
                <div class="relative">
                    <input
                        id="password"
                        type="password"
                        name="password"
                        placeholder="Minimal 8 karakter"
                        class="w-full rounded-2xl border pl-5 pr-12 py-2.5 outline-none transition-all duration-300 focus:ring-4 {{ $errors->has('password') ? 'border-red-400 focus:border-red-400 focus:ring-red-100' : 'border-gray-200 focus:border-indigo-500 focus:ring-indigo-100' }}"
                    >
                    
                    <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
                        <i class="bi bi-eye text-xl"></i>
                    </button>
                </div>
                @error('password')
                    <p class="mt-0.5 text-xs font-medium text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="mb-1 block font-semibold text-[#111827]">
                    Konfirmasi Password
                </label>
                <div class="relative">
                    <input
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        placeholder="Ulangi password Anda"
                        class="w-full rounded-2xl border pl-5 pr-12 py-2.5 outline-none transition-all duration-300 focus:ring-4 {{ $errors->has('password_confirmation') ? 'border-red-400 focus:border-red-400 focus:ring-red-100' : 'border-gray-200 focus:border-indigo-500 focus:ring-indigo-100' }}"
                    >
                    
                    <button type="button" id="toggleConfirmPassword" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
                        <i class="bi bi-eye text-xl"></i>
                    </button>
                </div>
                @error('password_confirmation')
                    <p class="mt-0.5 text-xs font-medium text-red-500">{{ $message }}</p>
                @enderror
            </div>
        </div>

       
        <div class="mt-10 flex flex-col items-center justify-between gap-4 md:flex-row">
            <a href="{{ route('login') }}" class="text-sm text-blue-500 transition-all duration-300 hover:text-indigo-500">
                Sudah punya akun?
            </a>

            <button
                type="submit"
                class="w-full rounded-2xl bg-gradient-to-r from-[#5B67F1] to-[#7482FF] px-10 py-2.5 font-bold text-white shadow-lg shadow-indigo-100 transition-all duration-300 hover:scale-[1.01] md:w-auto">
                Daftar
            </button>
        </div>
    </form>

    @vite('resources/js/auth-password.js')
@endsection