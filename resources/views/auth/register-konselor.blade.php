@extends('layouts.guest')

@section('content')
    <form method="POST" action="{{ route('register.konselor') }}" novalidate>
        @csrf

        @if ($errors->any())
            <div class="mb-7 rounded-2xl border border-red-100 bg-red-50 px-5 py-4 text-sm font-medium text-red-600">
                Periksa kembali data pendaftaranmu.
            </div>
        @endif

        <div class="space-y-6">

            {{-- Nama --}}
            <div>
                <label for="nama" class="mb-3 block font-semibold text-[#111827]">
                    Nama Lengkap
                </label>
                <input id="nama" type="text" name="nama" value="{{ old('nama') }}"
                    placeholder="Masukkan nama lengkap Anda"
                    class="w-full rounded-2xl border px-5 py-4 outline-none transition-all duration-300 focus:ring-4 {{ $errors->has('nama') ? 'border-red-400 focus:border-red-400 focus:ring-red-100' : 'border-gray-200 focus:border-indigo-500 focus:ring-indigo-100' }}">
                @error('nama')
                    <p class="mt-2 text-sm font-medium text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Email --}}
            <div>
                <label for="email" class="mb-3 block font-semibold text-[#111827]">
                    Email
                </label>
                <input id="email" type="email" name="email" value="{{ old('email') }}"
                    placeholder="Masukkan email Anda"
                    class="w-full rounded-2xl border px-5 py-4 outline-none transition-all duration-300 focus:ring-4 {{ $errors->has('email') ? 'border-red-400 focus:border-red-400 focus:ring-red-100' : 'border-gray-200 focus:border-indigo-500 focus:ring-indigo-100' }}">
                @error('email')
                    <p class="mt-2 text-sm font-medium text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password --}}
            <div>
                <label for="password" class="mb-3 block font-semibold text-[#111827]">
                    Password
                </label>
                <input id="password" type="password" name="password" placeholder="Minimal 8 karakter"
                    class="w-full rounded-2xl border px-5 py-4 outline-none transition-all duration-300 focus:ring-4 {{ $errors->has('password') ? 'border-red-400 focus:border-red-400 focus:ring-red-100' : 'border-gray-200 focus:border-indigo-500 focus:ring-indigo-100' }}">
                @error('password')
                    <p class="mt-2 text-sm font-medium text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Konfirmasi Password --}}
            <div>
                <label for="password_confirmation" class="mb-3 block font-semibold text-[#111827]">
                    Konfirmasi Password
                </label>
                <input id="password_confirmation" type="password" name="password_confirmation"
                    placeholder="Ulangi password Anda"
                    class="w-full rounded-2xl border px-5 py-4 outline-none transition-all duration-300 focus:ring-4 {{ $errors->has('password_confirmation') ? 'border-red-400 focus:border-red-400 focus:ring-red-100' : 'border-gray-200 focus:border-indigo-500 focus:ring-indigo-100' }}">
                @error('password_confirmation')
                    <p class="mt-2 text-sm font-medium text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Spesialisasi --}}
            <div>
                <label for="spesialisasi" class="mb-3 block font-semibold text-[#111827]">
                    Spesialisasi
                </label>
                <input id="spesialisasi" type="text" name="spesialisasi" value="{{ old('spesialisasi') }}"
                    placeholder="Contoh: Konseling Remaja, Kecemasan, Depresi"
                    class="w-full rounded-2xl border px-5 py-4 outline-none transition-all duration-300 focus:ring-4 {{ $errors->has('spesialisasi') ? 'border-red-400 focus:border-red-400 focus:ring-red-100' : 'border-gray-200 focus:border-indigo-500 focus:ring-indigo-100' }}">
                @error('spesialisasi')
                    <p class="mt-2 text-sm font-medium text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- No HP --}}
            <div>
                <label for="no_hp" class="mb-3 block font-semibold text-[#111827]">
                    Nomor HP
                </label>
                <input id="no_hp" type="text" name="no_hp" value="{{ old('no_hp') }}"
                    placeholder="Contoh: 08123456789"
                    class="w-full rounded-2xl border px-5 py-4 outline-none transition-all duration-300 focus:ring-4 {{ $errors->has('no_hp') ? 'border-red-400 focus:border-red-400 focus:ring-red-100' : 'border-gray-200 focus:border-indigo-500 focus:ring-indigo-100' }}">
                @error('no_hp')
                    <p class="mt-2 text-sm font-medium text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Gender --}}
            <div>
                <label for="gender" class="mb-3 block font-semibold text-[#111827]">
                    Gender
                </label>
                <select id="gender" name="gender"
                    class="w-full rounded-2xl border px-5 py-4 outline-none transition-all duration-300 focus:ring-4 {{ $errors->has('gender') ? 'border-red-400 focus:border-red-400 focus:ring-red-100' : 'border-gray-200 focus:border-indigo-500 focus:ring-indigo-100' }}">
                    <option value="" disabled selected>Pilih gender</option>
                    <option value="L" {{ old('gender') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="P" {{ old('gender') == 'P' ? 'selected' : '' }}>Perempuan</option>
                    <option value="N" {{ old('gender') == 'N' ? 'selected' : '' }}>Tidak ingin menyebutkan</option>
                </select>
                @error('gender')
                    <p class="mt-2 text-sm font-medium text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Link WhatsApp --}}
            <div>
                <label for="link_whatsapp" class="mb-3 block font-semibold text-[#111827]">
                    Link WhatsApp <span class="font-normal text-slate-400">(opsional)</span>
                </label>
                <input id="link_whatsapp" type="url" name="link_whatsapp" value="{{ old('link_whatsapp') }}"
                    placeholder="Contoh: https://wa.me/628123456789"
                    class="w-full rounded-2xl border px-5 py-4 outline-none transition-all duration-300 focus:ring-4 {{ $errors->has('link_whatsapp') ? 'border-red-400 focus:border-red-400 focus:ring-red-100' : 'border-gray-200 focus:border-indigo-500 focus:ring-indigo-100' }}">
                <p class="mt-2 text-sm leading-6 text-slate-500">
                    Format: https://wa.me/628xxxxxxxxx
                </p>
                @error('link_whatsapp')
                    <p class="mt-2 text-sm font-medium text-red-500">{{ $message }}</p>
                @enderror
            </div>

        </div>

        <div class="mt-10 flex flex-col items-center justify-between gap-5 md:flex-row">
            <a href="{{ route('login') }}" class="text-gray-500 transition-all duration-300 hover:text-indigo-500">
                Sudah punya akun?
            </a>

            <button type="submit"
                class="w-full rounded-2xl bg-gradient-to-r from-[#5B67F1] to-[#7482FF] px-10 py-4 font-bold text-white shadow-lg shadow-indigo-100 transition-all duration-300 hover:scale-[1.01] md:w-auto">
                Daftar sebagai Konselor
            </button>
        </div>
    </form>
@endsection
