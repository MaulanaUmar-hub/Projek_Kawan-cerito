<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Kawan Cerito</title>
    <link rel="icon" type="image/png" sizes="64x64" href="{{ asset('assets/brand/kawan-cerito-favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('assets/brand/kawan-cerito-logo.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Lora:ital,wght@0,600;1,500;1,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <x-vite-assets :entries="[
        'resources/css/app.css',
        'resources/js/app.js'
    ]" />
</head>

<body class="bg-[#F5F7FF]">

    <div class="min-h-screen flex items-center justify-center px-6 py-8">

        <div class="w-full max-w-[1500px] bg-white rounded-[36px] overflow-hidden shadow-sm grid lg:grid-cols-[1.08fr_0.92fr] min-h-[calc(100vh-4rem)]">

            {{-- LEFT PANEL --}}
            <div class="auth-left-panel hidden lg:flex flex-col bg-[#F7F8FF] px-16 py-2 relative overflow-hidden">

                {{-- LOGO --}}
                <div>

                    <div class="auth-brand flex items-center gap-5 mb-0">

                        <img
                            src="{{ asset('assets/brand/kawan-cerito-logo.png') }}"
                            alt="Logo Kawan Cerito"
                            class="h-14 w-14 rounded-2xl object-cover shadow-sm"
                            style="width: 56px; height: 56px; border-radius: 18px; object-fit: cover;"
                        >

                        <div>
                            <h1 class="text-3xl leading-none font-extrabold tracking-[-0.04em] text-[#0B132B]">
                                Kawan Cerito
                            </h1>

                            <p class="text-[13px] text-gray-500 mt-0">
                                Telekonseling untuk Kesehatan Mental
                            </p>
                        </div>
                    </div>

                    {{-- HERO --}}
                    <div class="max-w-[650px] mt-2">

                        <h2 class="auth-hero-title mb-1">
                            Mulai langkah kecil
                            untuk pulih dan
                            <span class="auth-hero-accent">
                                bertumbuh.
                            </span>
                        </h2>

                        <p class="auth-hero-subtitle mb-0 pb-0">
                            Daftar dengan mudah dan lengkapi profilmu!
                        </p>
                    </div>
                </div>

                {{-- FEATURE CARD --}}
                <div class="auth-feature-grid grid grid-cols-3 gap-5 !-mt-5 max-w-[760px] relative z-10">

                    {{-- CARD 1: Hijau --}}
                    <div class="bg-green-50 rounded-[28px] p-3 shadow-sm border border-green-100/50">

                        <i class="bi bi-shield-lock text-2xl text-green-700 mb-2 block"></i>

                        <h3 class="font-bold text-[15px] text-green-900 mb-1">
                            Privasi Terjamin
                        </h3>

                        <p class="text-[13px] leading-6 text-green-700/90">
                            Data dan cerita kamu aman bersama kami.
                        </p>
                    </div>

                    {{-- CARD 2: Ungu --}}
                    <div class="bg-purple-50 rounded-[28px] p-3 shadow-sm border border-purple-100/50">

                        <i class="bi bi-person-check text-2xl text-purple-700 mb-2 block"></i>

                        <h3 class="font-bold text-[15px] text-purple-900 mb-1">
                            Konselor Profesional
                        </h3>

                        <p class="text-[13px] leading-6 text-purple-700/90">
                            Dibimbing oleh konselor berpengalaman.
                        </p>
                    </div>

                    {{-- CARD 3: Biru --}}
                    <div class="bg-blue-50 rounded-[28px] p-3 shadow-sm border border-blue-100/50">

                        <i class="bi bi-chat-dots text-2xl text-blue-700 mb-2 block"></i>

                        <h3 class="font-bold text-[15px] text-blue-900 mb-1">
                            Mudah & Fleksibel
                        </h3>

                        <p class="text-[13px] leading-6 text-blue-700/90">
                            Konseling online kapan saja.
                        </p>
                    </div>

                </div>

                {{-- TERTARIK MENJADI KONSELOR--}}
                <div class="!mt-[20px] rounded-2xl border border-indigo-100 bg-indigo-50/60 p-4 max-w-[760px]">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm font-semibold text-[#111827]">
                                Tertarik menjadi bagian dari konselor Kawan Cerito?
                            </p>
                            <p class="mt-1 text-sm leading-6 text-slate-500">
                                Ajukan pendaftaran sebagai konselor melalui formulir khusus yang lebih lengkap.
                            </p>
                        </div>
                        <a
                            href="{{ route('register.konselor') }}"
                            class="inline-flex shrink-0 items-center justify-center rounded-xl border border-indigo-200 bg-white px-5 py-2.5 text-sm font-bold text-indigo-600 transition-all duration-300 hover:-translate-y-0.5 hover:border-indigo-300 hover:bg-indigo-50 hover:shadow-[0_8px_24px_rgba(105,108,255,0.16)]">
                            Daftar Konselor
                        </a>
                    </div>
                </div>
            </div>

            {{-- RIGHT PANEL --}}
            <div class="bg-white overflow-y-auto">

                <div class="max-w-[680px] mx-auto px-10 pt-10 pb-14 lg:px-16">

                    {{-- HEADER --}}
                    <div class="mb-10">
                        <a href="{{ url('/') }}" class="mb-8 inline-flex items-center gap-2 text-sm font-semibold text-[#5B67F1] transition-all duration-300 hover:text-indigo-700">
                            <span aria-hidden="true">←</span>
                            Kembali ke halaman utama
                        </a>

                        <h2 class="text-[56px] leading-[1.05] font-extrabold tracking-[-0.04em] text-[#0B132B] mb-4">
                            Buat akun baru
                        </h2>

                        <p class="text-[18px] leading-8 text-gray-500">
                            Mulai perjalananmu bersama Kawan Cerito
                        </p>
                    </div>

                    {{-- FORM --}}
                    @yield('content')

                </div>
            </div>
        </div>
    </div>

</body>

</html>
