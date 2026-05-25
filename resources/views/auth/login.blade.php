@extends('layouts.auth')

@section('content')
    <div class="min-h-screen bg-[#F5F7FF] flex items-center justify-center px-6 py-8">

        <div
            class="w-full max-w-[1500px] bg-white rounded-[36px] overflow-hidden shadow-sm grid lg:grid-cols-[1.08fr_0.92fr] min-h-[850px]">

            {{-- LEFT PANEL --}}
            <div class="hidden lg:flex flex-col justify-between bg-[#F7F8FF] px-16 py-14 relative overflow-hidden">

                {{-- LOGO --}}
                <div>

                    <div class="flex items-center gap-4 mb-20">

                        <div class="w-16 h-16 rounded-[24px] bg-gradient-to-br from-[#5B67F1] to-[#72D6C9]"></div>

                        <div>
                            <h1 class="text-[52px] leading-none font-extrabold tracking-[-0.04em] text-[#0B132B]">
                                Kawan Cerito
                            </h1>

                            <p class="text-[15px] text-gray-500 mt-1">
                                Telekonseling untuk Kesehatan Mental
                            </p>
                        </div>
                    </div>

                    {{-- HERO --}}
                    <div class="max-w-[650px]">

                        <h2 class="auth-hero-title mb-8">
                            Tempat aman
                            untuk bercerita
                            dan
                            <span class="auth-hero-accent">
                                didengar.
                            </span>
                        </h2>

                        <p class="auth-hero-subtitle">
                            Kawan Cerito hadir untuk menemani perjalananmu menuju kesehatan mental yang lebih baik.
                        </p>
                    </div>
                </div>

                {{-- FEATURE CARD --}}
                <div class="grid grid-cols-3 gap-5 mt-10 max-w-[760px]">

                    {{-- CARD --}}
                    <div class="bg-white rounded-[28px] p-5 shadow-sm border border-[#F3F4F6]">

                        <div class="w-12 h-12 rounded-2xl bg-green-100 mb-5"></div>

                        <h3 class="font-bold text-[15px] text-[#111827] mb-3">
                            Privasi Terjamin
                        </h3>

                        <p class="text-[13px] leading-7 text-gray-500">
                            Data dan cerita kamu aman bersama kami.
                        </p>
                    </div>

                    {{-- CARD --}}
                    <div class="bg-white rounded-[28px] p-5 shadow-sm border border-[#F3F4F6]">

                        <div class="w-12 h-12 rounded-2xl bg-purple-100 mb-5"></div>

                        <h3 class="font-bold text-[15px] text-[#111827] mb-3">
                            Konselor Profesional
                        </h3>

                        <p class="text-[13px] leading-7 text-gray-500">
                            Dibimbing oleh konselor berpengalaman.
                        </p>
                    </div>

                    {{-- CARD --}}
                    <div class="bg-white rounded-[28px] p-5 shadow-sm border border-[#F3F4F6]">

                        <div class="w-12 h-12 rounded-2xl bg-blue-100 mb-5"></div>

                        <h3 class="font-bold text-[15px] text-[#111827] mb-3">
                            Mudah & Fleksibel
                        </h3>

                        <p class="text-[13px] leading-7 text-gray-500">
                            Konseling online via WhatsApp.
                        </p>
                    </div>

                </div>
            </div>

            {{-- RIGHT PANEL --}}
            <div class="flex items-start justify-center bg-white px-10 pt-24 lg:px-20">

                <div class="w-full max-w-[480px]">

                    {{-- HEADER --}}
                    <div class="mb-12">

                        <h2 class="text-[58px] leading-[1.05] font-extrabold tracking-[-0.04em] text-[#0B132B] mb-4">
                            Selamat datang kembali 👋
                        </h2>

                        <p class="text-[18px] leading-8 text-gray-500">
                            Silakan masuk ke akun Kawan Cerito Anda
                        </p>
                    </div>

                    {{-- FORM --}}
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        {{-- EMAIL --}}
                        <div class="mb-7">

                            <label class="block mb-3 font-semibold text-[#111827]">
                                Email
                            </label>

                            <input type="email" name="email" placeholder="Masukkan email Anda"
                                class="w-full h-[68px] rounded-2xl border border-gray-200 px-6 text-[16px] outline-none focus:border-[#5B67F1] focus:ring-4 focus:ring-indigo-100 transition-all duration-300">
                        </div>

                        {{-- PASSWORD --}}
                        <div class="mb-5">

                            <label class="block mb-3 font-semibold text-[#111827]">
                                Password
                            </label>

                            <input type="password" name="password" placeholder="Masukkan password Anda"
                                class="w-full h-[68px] rounded-2xl border border-gray-200 px-6 text-[16px] outline-none focus:border-[#5B67F1] focus:ring-4 focus:ring-indigo-100 transition-all duration-300">
                        </div>

                        {{-- FORGOT --}}
                        <div class="flex justify-end mb-10">

                            <a href="#"
                                class="text-[15px] font-semibold text-[#5B67F1] hover:text-indigo-700 transition-all duration-300">
                                Lupa password?
                            </a>
                        </div>

                        {{-- BUTTON --}}
                        <button type="submit"
                            class="w-full h-[68px] rounded-2xl text-white font-bold text-[18px] bg-gradient-to-r from-[#5B67F1] to-[#7482FF] hover:scale-[1.01] transition-all duration-300 shadow-lg shadow-indigo-100">
                            Masuk
                        </button>

                        {{-- DIVIDER --}}
                        <div class="relative flex items-center justify-center my-10">

                            <div class="absolute border-t border-gray-200 w-full"></div>

                            <span class="relative bg-white px-5 text-[14px] text-gray-400">
                                atau masuk dengan
                            </span>
                        </div>

                        {{-- GOOGLE --}}
                        <button type="button"
                            class="w-full h-[68px] border border-gray-200 rounded-2xl text-[17px] font-semibold hover:bg-gray-50 transition-all duration-300">
                            Masuk dengan Google
                        </button>

                        {{-- REGISTER --}}
                        <p class="text-center text-[16px] text-gray-500 mt-10">

                            Belum punya akun?

                            <a href="{{ route('register') }}" class="font-bold text-[#5B67F1] hover:text-indigo-700">
                                Daftar sekarang
                            </a>
                        </p>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
