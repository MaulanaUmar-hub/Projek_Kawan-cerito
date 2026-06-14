@extends('layouts.guest')

@section('content')
<form method="POST" action="{{ route('register.konselor') }}" novalidate>
    @csrf

    @if ($errors->any())
    <div class="mb-5 rounded-2xl border border-red-100 bg-red-50 px-5 py-3 text-sm font-medium text-red-600">
        Periksa kembali data pendaftaranmu.
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-5 gap-y-4">

        {{-- Nama --}}
        <div>
            <label for="nama" class="mb-1.5 block text-sm font-semibold text-[#111827]">
                Nama Lengkap
            </label>
            <input id="nama" type="text" name="nama" value="{{ old('nama') }}"
                placeholder="Nama lengkap Anda"
                class="w-full rounded-2xl border px-5 py-2.5 text-sm outline-none transition-all duration-300 focus:ring-4 {{ $errors->has('nama') ? 'border-red-400 focus:border-red-400 focus:ring-red-100' : 'border-gray-200 focus:border-indigo-500 focus:ring-indigo-100' }}">
            @error('nama')
            <p class="mt-1 text-xs font-medium text-red-500">{{ $message }}</p>
            @enderror
        </div>

        {{-- Email --}}
        <div>
            <label for="email" class="mb-1.5 block text-sm font-semibold text-[#111827]">
                Email
            </label>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                placeholder="Masukkan email Anda"
                class="w-full rounded-2xl border px-5 py-2.5 text-sm outline-none transition-all duration-300 focus:ring-4 {{ $errors->has('email') ? 'border-red-400 focus:border-red-400 focus:ring-red-100' : 'border-gray-200 focus:border-indigo-500 focus:ring-indigo-100' }}">
            @error('email')
            <p class="mt-1 text-xs font-medium text-red-500">{{ $message }}</p>
            @enderror
        </div>

        {{-- Password --}}
        <div>
            <label for="password" class="mb-1.5 block text-sm font-semibold text-[#111827]">
                Password
            </label>
            <div class="relative">
                <input id="password" type="password" name="password" placeholder="Minimal 8 karakter"
                    class="w-full rounded-2xl border pl-5 pr-12 py-2.5 text-sm outline-none transition-all duration-300 focus:ring-4 {{ $errors->has('password') ? 'border-red-400 focus:border-red-400 focus:ring-red-100' : 'border-gray-200 focus:border-indigo-500 focus:ring-indigo-100' }}">

                <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
                    <i class="bi bi-eye text-xl"></i>
                </button>
            </div>
            @error('password')
            <p class="mt-1 text-xs font-medium text-red-500">{{ $message }}</p>
            @enderror
        </div>

        {{-- Konfirmasi Password --}}
        <div>
            <label for="password_confirmation" class="mb-1.5 block text-sm font-semibold text-[#111827]">
                Konfirmasi Password
            </label>
            <div class="relative">
                <input id="password_confirmation" type="password" name="password_confirmation"
                    placeholder="Ulangi password Anda"
                    class="w-full rounded-2xl border pl-5 pr-12 py-2.5 text-sm outline-none transition-all duration-300 focus:ring-4 {{ $errors->has('password_confirmation') ? 'border-red-400 focus:border-red-400 focus:ring-red-100' : 'border-gray-200 focus:border-indigo-500 focus:ring-indigo-100' }}">

                <button type="button" id="toggleConfirmPassword" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
                    <i class="bi bi-eye text-xl"></i>
                </button>
            </div>
            @error('password_confirmation')
            <p class="mt-1 text-xs font-medium text-red-500">{{ $message }}</p>
            @enderror
        </div>

        {{-- Nomor HP --}}
        <div>
            <label for="no_hp" class="mb-1.5 block text-sm font-semibold text-[#111827]">
                Nomor HP
            </label>
            <input id="no_hp" type="text" name="no_hp" value="{{ old('no_hp') }}"
                placeholder="Contoh: 08123456789"
                class="w-full rounded-2xl border px-5 py-2.5 text-sm outline-none transition-all duration-300 focus:ring-4 {{ $errors->has('no_hp') ? 'border-red-400 focus:border-red-400 focus:ring-red-100' : 'border-gray-200 focus:border-indigo-500 focus:ring-indigo-100' }}">
            @error('no_hp')
            <p class="mt-1 text-xs font-medium text-red-500">{{ $message }}</p>
            @enderror
        </div>

        {{-- Gender --}}
        <div>
            <label for="gender" class="mb-1.5 block text-sm font-semibold text-[#111827]">
                Gender
            </label>
            <select id="gender" name="gender"
                class="w-full rounded-2xl border px-5 py-2.5 text-sm outline-none transition-all duration-300 focus:ring-4 {{ $errors->has('gender') ? 'border-red-400 focus:border-red-400 focus:ring-red-100' : 'border-gray-200 focus:border-indigo-500 focus:ring-indigo-100' }}">
                <option value="" disabled selected>Pilih gender</option>
                <option value="L" {{ old('gender') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                <option value="P" {{ old('gender') == 'P' ? 'selected' : '' }}>Perempuan</option>
            </select>
            @error('gender')
            <p class="mt-1 text-xs font-medium text-red-500">{{ $message }}</p>
            @enderror
        </div>

        {{-- Spesialisasi --}}
        @php
        $spesialisasiOptions = [
        'Kesehatan Mental',
        'Anak & Remaja',
        'Karier',
        'Keluarga & Pernikahan',
        'Trauma & Krisis',
        'Adiksi',
        'Pendidikan'
        ];
        $oldSpesialisasi = old('spesialisasi');
        $isCustom = !empty($oldSpesialisasi) && !in_array($oldSpesialisasi, $spesialisasiOptions);
        @endphp
        <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4 border-t border-gray-100 pt-3 mt-1">
            <div>
                <label for="spesialisasi_select" class="mb-1.5 block text-sm font-semibold text-[#111827]">
                    Spesialisasi
                </label>
                <select id="spesialisasi_select"
                    class="w-full rounded-2xl border px-5 py-2.5 text-sm outline-none transition-all duration-300 focus:ring-4 {{ $errors->has('spesialisasi') ? 'border-red-400 focus:border-red-400 focus:ring-red-100' : 'border-gray-200 focus:border-indigo-500 focus:ring-indigo-100' }}">
                    <option value="" disabled {{ empty($oldSpesialisasi) ? 'selected' : '' }}>Pilih spesialisasi</option>
                    @foreach($spesialisasiOptions as $option)
                    <option value="{{ $option }}" {{ $oldSpesialisasi == $option ? 'selected' : '' }}>{{ $option }}</option>
                    @endforeach
                    <option value="lainnya" {{ $isCustom ? 'selected' : '' }}>Pilihan lainnya...</option>
                </select>
            </div>

            <div id="custom_spesialisasi_wrapper" class="{{ $isCustom ? '' : 'hidden' }}">
                <label for="spesialisasi" class="mb-1.5 block text-sm font-semibold text-[#111827]">
                    Tulis Spesialisasi Anda
                </label>
                <input id="spesialisasi" type="text" name="spesialisasi" value="{{ old('spesialisasi') }}"
                    placeholder="Masukkan spesialisasi khusus Anda"
                    class="w-full rounded-2xl border px-5 py-2.5 text-sm outline-none transition-all duration-300 focus:ring-4 {{ $errors->has('spesialisasi') ? 'border-red-400 focus:border-red-400 focus:ring-red-100' : 'border-gray-200 focus:border-indigo-500 focus:ring-indigo-100' }}"
                    {{ $isCustom ? '' : 'disabled' }}>
            </div>

            @error('spesialisasi')
            <div class="md:col-span-2">
                <p class="mt-1 text-xs font-medium text-red-500">{{ $message }}</p>
            </div>
            @enderror
        </div>

        {{-- Link WhatsApp --}}
        <div class="md:col-span-2 border-t border-gray-100 pt-3">
            <label for="link_whatsapp" class="mb-1.5 block text-sm font-semibold text-[#111827]">
                Link WhatsApp <span class="font-normal text-slate-400">(opsional)</span>
            </label>
            <input id="link_whatsapp" type="url" name="link_whatsapp" value="{{ old('link_whatsapp') }}"
                placeholder="Contoh: https://wa.me/628123456789"
                class="w-full rounded-2xl border px-5 py-2.5 text-sm outline-none transition-all duration-300 focus:ring-4 {{ $errors->has('link_whatsapp') ? 'border-red-400 focus:border-red-400 focus:ring-red-100' : 'border-gray-200 focus:border-indigo-500 focus:ring-indigo-100' }}">
            <p class="mt-1 text-xs leading-normal text-slate-500">
                Format: https://wa.me/628xxxxxxxxx
            </p>
            @error('link_whatsapp')
            <p class="mt-1 text-xs font-medium text-red-500">{{ $message }}</p>
            @enderror
        </div>

    </div>

    <div class="mt-6 flex flex-col items-center justify-between gap-4 border-t border-gray-100 pt-5 md:flex-row">
        <a href="{{ route('login') }}" class="text-sm text-blue-500 transition-all duration-300 hover:text-indigo-500">
            Sudah punya akun?
        </a>

        <button type="submit"
            class="w-full rounded-2xl bg-gradient-to-r from-[#5B67F1] to-[#7482FF] px-10 py-2.5 font-bold text-white shadow-lg shadow-indigo-100 transition-all duration-300 hover:scale-[1.01] md:w-auto">
            Daftar sebagai Konselor
        </button>
    </div>
</form>

<!-- Sisa kode form bagian atas tetap sama seperti sebelumnya... -->
</div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectEl = document.getElementById('spesialisasi_select');
        const inputEl = document.getElementById('spesialisasi');
        const wrapperEl = document.getElementById('custom_spesialisasi_wrapper');

        selectEl.addEventListener('change', function() {
            if (this.value === 'lainnya') {
                wrapperEl.classList.remove('hidden');
                inputEl.removeAttribute('disabled');
                inputEl.value = '';
                inputEl.focus();
            } else {
                wrapperEl.classList.add('hidden');
                inputEl.setAttribute('disabled', 'disabled');
                inputEl.value = this.value;
            }
        });

        if (selectEl.value === 'lainnya') {
            inputEl.name = 'spesialisasi';
        } else {
            selectEl.setAttribute('name', 'spesialisasi');
            inputEl.removeAttribute('name');
        }

        selectEl.closest('form').addEventListener('submit', function() {
            if (selectEl.value === 'lainnya') {
                selectEl.removeAttribute('name');
                inputEl.setAttribute('name', 'spesialisasi');
            } else {
                selectEl.setAttribute('name', 'spesialisasi');
                inputEl.removeAttribute('name');
            }
        });
    });
</script>

{{-- Diubah: Memanggil Vite langsung di dalam content tanpa @push --}}
@vite('resources/js/auth-password.js')
@endsection