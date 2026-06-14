@extends('layouts.auth')

@section('content')
    <div class="min-h-screen bg-[#F5F7FF] flex items-center justify-center px-6 py-8">

        <div
            class="w-full max-w-[1500px] bg-white rounded-[36px] overflow-hidden shadow-sm grid lg:grid-cols-[1.08fr_0.92fr] min-h-[calc(100vh-4rem)]">

            <div class="auth-left-panel hidden lg:flex flex-col bg-[#F7F8FF] px-16 py-2 relative overflow-hidden">

                <div>

                    <div class="auth-brand flex items-center gap-5 mb-0">

                        <img src="{{ asset('assets/brand/kawan-cerito-logo.png') }}" alt="Logo Kawan Cerito"
                            class="h-14 w-14 rounded-2xl object-cover shadow-sm"
                            style="width: 56px; height: 56px; border-radius: 18px; object-fit: cover;">

                        <div>
                            <h1 class="text-3xl leading-none font-extrabold tracking-[-0.04em] text-[#0B132B]">
                                Kawan Cerito
                            </h1>

                            <p class="text-[13px] text-gray-500 mt-0">
                                Telekonseling untuk Kesehatan Mental
                            </p>
                        </div>
                    </div>

                    <div class="max-w-[650px] mt-2">

                        <h2 class="auth-hero-title mb-1">
                            Kembali ke ruang
                            yang tenang dan
                            <span class="auth-hero-accent">
                                aman.
                            </span>
                        </h2>

                        <p class="auth-hero-subtitle mb-0 pb-0">
                            Lanjutkan proses konselingmu dengan nyaman, privat, dan tetap terarah.
                        </p>
                    </div>
                </div>

                <div class="auth-feature-grid grid grid-cols-3 gap-5 !-mt-5 max-w-[760px] relative z-10">

                    <div class="bg-green-50 rounded-[28px] p-3 shadow-sm border border-green-100/50">

                        <i class="bi bi-shield-lock text-2xl text-green-700 mb-2 block"></i>

                        <h3 class="font-bold text-[15px] text-green-900 mb-1">
                            Privasi Terjamin
                        </h3>

                        <p class="text-[13px] leading-6 text-green-700/90">
                            Data dan cerita kamu aman bersama kami.
                        </p>
                    </div>

                    <div class="bg-purple-50 rounded-[28px] p-3 shadow-sm border border-purple-100/50">

                        <i class="bi bi-person-check text-2xl text-purple-700 mb-2 block"></i>

                        <h3 class="font-bold text-[15px] text-purple-900 mb-1">
                            Konselor Profesional
                        </h3>

                        <p class="text-[13px] leading-6 text-purple-700/90">
                            Dibimbing oleh konselor berpengalaman.
                        </p>
                    </div>

                    <div class="bg-blue-50 rounded-[28px] p-3 shadow-sm border border-blue-100/50">

                        <i class="bi bi-chat-dots text-2xl text-blue-700 mb-2 block"></i>

                        <h3 class="font-bold text-[15px] text-blue-900 mb-1">
                            Mudah & Fleksibel
                        </h3>

                        <p class="text-[13px] leading-6 text-blue-700/90">
                            Konseling online via WhatsApp.
                        </p>
                    </div>

                </div>
            </div>

            <div class="flex items-start justify-center bg-white px-10 pt-10 pb-14 lg:px-16 overflow-y-auto">

                <div class="w-full max-w-[680px] mx-auto">

                    <div class="mb-10">
                        <a href="{{ url('/') }}"
                            class="mb-8 inline-flex items-center gap-2 text-sm font-semibold text-[#5B67F1] transition-all duration-300 hover:text-indigo-700">
                            <span aria-hidden="true">←</span>
                            Kembali ke halaman utama
                        </a>

                        <h2 class="text-[56px] leading-[1.05] font-extrabold tracking-[-0.04em] text-[#0B132B] mb-4">
                            Selamat datang kembali
                        </h2>

                        <p class="text-[18px] leading-8 text-gray-500">
                            Silakan masuk ke akun Kawan Cerito Anda
                        </p>
                    </div>

                    <form method="POST" action="{{ route('login') }}" novalidate>
                        @csrf

                        @if ($errors->any())
                            <div
                                class="mb-3 rounded-2xl border border-red-100 bg-red-50 px-5 py-2.5 text-sm font-medium text-red-600">
                                Periksa kembali email dan password Anda.
                            </div>
                        @endif

                        <div class="space-y-3">

                            <div>
                                <label for="email" class="mb-1 block font-semibold text-[#111827]">
                                    Email
                                </label>

                                <input type="email" id="email" name="email" value="{{ old('email') }}"
                                    placeholder="Masukkan email Anda"
                                    class="w-full rounded-2xl border px-5 py-2.5 outline-none transition-all duration-300 focus:ring-4
                                    {{ $errors->has('email') ? 'border-red-400 focus:border-red-400 focus:ring-red-100' : 'border-gray-200 focus:border-indigo-500 focus:ring-indigo-100' }}">

                                @error('email')
                                    <p class="mt-0.5 text-xs font-medium text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <div class="flex justify-between items-center mb-1">
                                    <label for="password" class="block font-semibold text-[#111827]">
                                        Password
                                    </label>
                                    <a href="#"
                                        class="text-xs font-semibold text-[#5B67F1] hover:text-indigo-700 transition-all duration-300">
                                        Lupa password?
                                    </a>
                                </div>

                                <div class="relative">
                                    <input type="password" id="password" name="password"
                                        placeholder="Masukkan password Anda"
                                        class="w-full rounded-2xl border pl-5 pr-12 py-2.5 outline-none transition-all duration-300 focus:ring-4
                                        {{ $errors->has('password') ? 'border-red-400 focus:border-red-400 focus:ring-red-100' : 'border-gray-200 focus:border-indigo-500 focus:ring-indigo-100' }}">

                                    <button type="button" id="togglePassword"
                                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
                                        <i id="passwordIcon" class="bi bi-eye text-xl"></i>
                                    </button>
                                </div>

                                @error('password')
                                    <p class="mt-0.5 text-xs font-medium text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="relative flex items-center justify-center my-4">
                            <div class="absolute border-t border-gray-200 w-full"></div>
                            <span class="relative bg-white px-4 text-[12px] text-gray-400">
                            </span>
                        </div>

                        <div class="mt-10 flex flex-col items-center justify-between gap-4 md:flex-row">
                            <p class="text-sm text-gray-700">
                                Belum punya akun?
                                <a href="{{ route('register') }}"
                                    class="font-semibold text-blue-500 transition-all duration-300 hover:text-indigo-500">
                                    Daftar sekarang
                                </a>
                            </p>

                            <button type="submit"
                                class="w-full rounded-2xl bg-gradient-to-r from-[#5B67F1] to-[#7482FF] px-10 py-2.5 font-bold text-white shadow-lg shadow-indigo-100 transition-all duration-300 hover:scale-[1.01] md:w-auto">
                                Masuk
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @vite('resources/js/auth-password.js')
@endpush
