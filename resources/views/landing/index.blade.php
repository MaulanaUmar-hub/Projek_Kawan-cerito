<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kawan Cerito – Tempat Bercerita dan Didengar</title>

    {{-- Tailwind CSS CDN (ganti dengan build Tailwind jika sudah setup Vite/Mix) --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Lora:ital,wght@0,400;0,600;1,400&display=swap"
        rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        serif: ['Lora', 'serif'],
                    },
                    colors: {
                        brand: {
                            50: '#f0f4ff',
                            100: '#e2eaff',
                            200: '#c3d3ff',
                            300: '#a0b8ff',
                            400: '#7b96f8',
                            500: '#5b74f0',
                            600: '#4356e3',
                            700: '#3644c8',
                            800: '#2d39a2',
                            900: '#283481',
                        },
                        teal: {
                            400: '#38c9b5',
                            500: '#2ab5a0',
                        },
                        soft: '#f5f7ff',
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'float-delay': 'float 6s ease-in-out 2s infinite',
                        'fade-up': 'fadeUp 0.7s ease forwards',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': {
                                transform: 'translateY(0px)'
                            },
                            '50%': {
                                transform: 'translateY(-12px)'
                            },
                        },
                        fadeUp: {
                            from: {
                                opacity: '0',
                                transform: 'translateY(24px)'
                            },
                            to: {
                                opacity: '1',
                                transform: 'translateY(0)'
                            },
                        },
                    },
                }
            }
        }
    </script>

    <style>
        /* Smooth scroll */
        html {
            scroll-behavior: smooth;
        }



        .blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.35;
        }

        /* Card hover lift */
        .card-lift {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card-lift:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 40px rgba(91, 116, 240, 0.15);
        }

        /* Stagger animation utilities */
        .delay-100 {
            animation-delay: 0.1s;
        }

        .delay-200 {
            animation-delay: 0.2s;
        }

        .delay-300 {
            animation-delay: 0.3s;
        }

        .delay-400 {
            animation-delay: 0.4s;
        }

        /* Nav link underline */
        .nav-link::after {
            content: '';
            display: block;
            height: 2px;
            background: #5b74f0;
            transform: scaleX(0);
            transition: transform 0.25s ease;
            border-radius: 99px;
        }

        .nav-link:hover::after {
            transform: scaleX(1);
        }

        /* Testimonial quote mark */
        .quote-mark {
            font-size: 5rem;
            line-height: 0.6;
            color: #c3d3ff;
            font-family: Georgia, serif;
        }

        /* Step connector line */
        .step-line {
            position: absolute;
            top: 28px;
            left: 50%;
            width: 100%;
            height: 2px;
            background: linear-gradient(90deg, #c3d3ff, #a0b8ff);
        }

        /* Gradient text */
        .gradient-text {
            background: linear-gradient(135deg, #5b74f0, #38c9b5);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Section fade-in on scroll (JS-driven) */
        .reveal {
            opacity: 0;
            transform: translateY(28px);
            transition: opacity 0.6s ease, transform 0.6s ease;
        }

        .reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>

<body class="font-sans text-slate-800 antialiased hero-bg">

    
    {{-- ===================== NAVBAR ===================== --}}
    <nav class="fixed top-0 inset-x-0 z-50 bg-white/80 backdrop-blur-md border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
            {{-- Logo --}}
            <a href="#" class="flex items-center gap-2.5">
                <div
                    class="w-9 h-9 rounded-xl bg-gradient-to-br from-brand-500 to-teal-400 flex items-center justify-center shadow-md">
                    {{-- Heart icon --}}
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <div>
                    <span class="font-bold text-slate-900 text-lg leading-tight">Kawan Cerito</span>
                    <p class="text-[10px] text-slate-400 font-medium leading-tight -mt-0.5">Telekonseling Kesehatan
                        Mental</p>
                </div>
            </a>

            {{-- Nav links --}}
            <div class="hidden md:flex items-center gap-8 text-sm font-medium text-slate-600">
                <a href="#beranda" class="nav-link hover:text-brand-600 transition-colors">Beranda</a>
                <a href="#layanan" class="nav-link hover:text-brand-600 transition-colors">Layanan</a>
                <a href="#cara-kerja" class="nav-link hover:text-brand-600 transition-colors">Cara Kerja</a>
                <a href="#konselor" class="nav-link hover:text-brand-600 transition-colors">Konselor</a>
                <a href="#testimoni" class="nav-link hover:text-brand-600 transition-colors">Testimoni</a>
            </div>

            {{-- CTA --}}
            <div class="flex items-center gap-3">
                <a href="{{ route('login') }}"
                    class="hidden md:inline-flex text-sm font-semibold text-brand-600 hover:text-brand-700 transition-colors">
                    Masuk
                </a>
                <a href="{{ route('register') }}"
                    class="inline-flex items-center gap-1.5 bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold px-4 py-2 rounded-full shadow-md shadow-brand-200 transition-all hover:shadow-lg">
                    Daftar Sekarang
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                    </svg>
                </a>
            </div>
        </div>
    </nav>

    
    {{-- ===================== HERO ===================== --}}
    <section
        id="beranda"
        class="relative overflow-hidden pt-32 pb-24 md:pt-40 md:pb-32 bg-gradient-to-br from-[#F5F3FF] via-[#EEF2FF] to-[#ECFEFF]">

        {{-- Ambient Blur --}}
        <div class="absolute top-[-120px] left-[-120px] w-[420px] h-[420px] bg-[#C4B5FD] opacity-30 blur-3xl rounded-full">
        </div>

        <div
            class="absolute bottom-[-180px] right-[-140px] w-[400px] h-[400px] bg-[#67E8F9] opacity-20 blur-3xl rounded-full">
        </div>

        <div class="absolute top-[30%] right-[20%] w-[260px] h-[260px] bg-[#DDD6FE] opacity-30 blur-3xl rounded-full">
        </div>

        <div class="relative z-10 max-w-[1450px] mx-auto px-6 lg:px-10">

            <div class="grid lg:grid-cols-[0.9fr_1.1fr] gap-16 items-start">

                {{-- ================= LEFT CONTENT ================= --}}
                <div>

                    {{-- Badge --}}
                    <div
                        class="inline-flex items-center gap-3 bg-white/80 backdrop-blur-sm border border-white/60 text-[#5B67F1] text-sm font-semibold px-5 py-2 rounded-full shadow-sm mb-8">

                        <span class="w-2 h-2 bg-[#72D6C9] rounded-full animate-pulse"></span>

                        Konselor profesional siap mendengarkanmu
                    </div>

                    {{-- Heading --}}
                    <h1
                        class="max-w-[760px] text-[clamp(3.5rem,5vw,6rem)] leading-[0.98] tracking-[-0.05em] font-extrabold text-[#0B132B] mb-8 text-balance">

                        Kamu Tidak
                        Harus Menanggung
                        Semuanya
                        <span
                            class="bg-gradient-to-r from-[#5B67F1] to-[#72D6C9] bg-clip-text text-transparent italic font-serif">
                            Sendirian.
                        </span>
                    </h1>

                    {{-- Description --}}
                    <p class="max-w-[620px] text-[18px] md:text-[20px] leading-9 text-slate-600 mb-10">

                        Kawan Cerito hadir sebagai ruang aman untukmu bercerita,
                        didengar tanpa dihakimi, dan mendapatkan bantuan dari
                        konselor profesional secara nyaman dan fleksibel.
                    </p>

                    {{-- CTA --}}
                    <div class="flex flex-col sm:flex-row items-start gap-5 mb-12">

                        {{-- Primary CTA --}}
                        <a href="{{ route('register') }}"
                            class="group inline-flex items-center justify-center gap-3 rounded-full bg-gradient-to-r from-[#5B67F1] to-[#7482FF] px-8 py-4 text-white font-semibold shadow-xl shadow-indigo-200 transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl">

                            Mulai Cerita Sekarang

                            <svg class="w-5 h-5 transition-transform duration-300 group-hover:translate-x-1" fill="none"
                                stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                            </svg>
                        </a>

                        {{-- Secondary CTA --}}
                        <a href="#cara-kerja"
                            class="inline-flex items-center justify-center gap-3 rounded-full bg-white/80 backdrop-blur-sm border border-white/70 px-8 py-4 text-slate-700 font-semibold shadow-sm transition-all duration-300 hover:bg-white hover:shadow-md">



                            Lihat Cara Kerja
                        </a>
                    </div>

                    {{-- Trust Indicators --}}
                    <div class="flex flex-wrap items-center gap-x-8 gap-y-4 text-sm text-slate-500">

                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-[#72D6C9]" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 1a9 9 0 100 18A9 9 0 0010 1zm4.207 6.293a1 1 0 00-1.414-1.414L9 10.172 7.207 8.379a1 1 0 00-1.414 1.414l2.5 2.5a1 1 0 001.414 0l4.5-4.5z"
                                    clip-rule="evenodd" />
                            </svg>

                            Privasi & kerahasiaan terjamin
                        </div>

                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-[#72D6C9]" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 1a9 9 0 100 18A9 9 0 0010 1zm4.207 6.293a1 1 0 00-1.414-1.414L9 10.172 7.207 8.379a1 1 0 00-1.414 1.414l2.5 2.5a1 1 0 001.414 0l4.5-4.5z"
                                    clip-rule="evenodd" />
                            </svg>

                            Konselor profesional & terpercaya
                        </div>

                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-[#72D6C9]" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 1a9 9 0 100 18A9 9 0 0010 1zm4.207 6.293a1 1 0 00-1.414-1.414L9 10.172 7.207 8.379a1 1 0 00-1.414 1.414l2.5 2.5a1 1 0 001.414 0l4.5-4.5z"
                                    clip-rule="evenodd" />
                            </svg>

                            Fleksibel & mudah diakses
                        </div>
                    </div>
                </div>

                {{-- ================= RIGHT CONTENT ================= --}}
                {{-- Right: Illustration card --}}
                <div class="flex justify-center lg:justify-end lg:pt-16">
                    <div class="relative w-full max-w-lg">
                        {{-- Main card --}}
                        <div
                            class="w-full bg-white rounded-3xl shadow-2xl shadow-brand-200/40 px-8 pt-10 pb-12 animate-float">
                            <div class="flex items-center gap-4 mb-8">
                                <div
                                    class="w-20 h-20 rounded-3xl bg-brand-100 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-9 h-9 text-brand-600" fill="none" stroke="currentColor"
                                        stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-slate-800 text-lg">Sesi Konseling Aktif</p>
                                    <p class="text-sm text-slate-400 mt-0.5">Bersama Kak Nisa, M.Psi</p>
                                </div>
                                <span class="ml-auto w-3 h-3 bg-teal-400 rounded-full animate-pulse"></span>
                            </div>
                            {{-- Chat bubbles --}}
                            <div class="space-y-5">
                                <div class="flex gap-3">
                                    <div
                                        class="w-10 h-10 rounded-full bg-brand-200 flex-shrink-0 flex items-center justify-center text-sm font-bold text-brand-700">
                                        N</div>
                                    <div
                                        class="bg-slate-100 rounded-2xl rounded-tl-none px-4 py-3.5 text-base text-slate-700 max-w-[80%]">
                                        Hai! Cerita dulu yuk, kamu lagi ngerasa gimana hari ini? 😊
                                    </div>
                                </div>
                                <div class="flex gap-3 justify-end">
                                    <div
                                        class="bg-brand-600 rounded-2xl rounded-tr-none px-4 py-3.5 text-base text-white max-w-[75%]">
                                        Aku lagi overwhelmed banget sama kerjaan...
                                    </div>
                                </div>
                                <div class="flex gap-3">
                                    <div
                                        class="w-10 h-10 rounded-full bg-brand-200 flex-shrink-0 flex items-center justify-center text-sm font-bold text-brand-700">
                                        N</div>
                                    <div
                                        class="bg-slate-100 rounded-2xl rounded-tl-none px-4 py-3.5 text-base text-slate-700 max-w-[80%]">
                                        Makasih udah mau cerita. Itu valid banget rasanya. Yuk kita bahas pelan-pelan 🌿
                                    </div>
                                </div>
                                <div class="flex gap-3 justify-end">
                                    <div
                                        class="bg-brand-600 rounded-2xl rounded-tr-none px-4 py-3.5 text-base text-white max-w-[75%]">
                                        Iya, makasih Kak... 🙏
                                    </div>
                                </div>
                                {{-- Typing indicator --}}
                                <div class="flex gap-3">
                                    <div
                                        class="w-10 h-10 rounded-full bg-brand-200 flex-shrink-0 flex items-center justify-center text-sm font-bold text-brand-700">
                                        N</div>
                                    <div
                                        class="bg-slate-100 rounded-2xl rounded-tl-none px-5 py-4 flex items-center gap-1.5">
                                        <span
                                            class="w-2 h-2 bg-slate-400 rounded-full animate-bounce [animation-delay:0ms]"></span>
                                        <span
                                            class="w-2 h-2 bg-slate-400 rounded-full animate-bounce [animation-delay:150ms]"></span>
                                        <span
                                            class="w-2 h-2 bg-slate-400 rounded-full animate-bounce [animation-delay:300ms]"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Floating badge 1: Rating --}}
                        <div
                            class="absolute -top-6 -right-6 bg-white rounded-2xl shadow-xl px-5 py-3.5 flex items-center gap-3 animate-float-delay">
                            <div class="flex">
                                @for ($i = 0; $i < 5; $i++)
                                    <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.967a1 1 0 00.95.69h4.175c.969 0 1.371 1.24.588 1.81l-3.376 2.453a1 1 0 00-.364 1.118l1.286 3.966c.3.921-.755 1.688-1.538 1.118L10 14.347l-3.958 2.872c-.783.57-1.838-.197-1.538-1.118l1.286-3.966a1 1 0 00-.364-1.118L2.05 9.394c-.783-.57-.38-1.81.588-1.81h4.175a1 1 0 00.95-.69L9.049 2.927z" />
                                    </svg>
                                    @endfor
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-800">4.9/5</p>
                                <p class="text-xs text-slate-400">Rating konselor</p>
                            </div>
                        </div>

                        {{-- Floating badge 2: Users --}}
                        <div class="absolute -bottom-6 -left-8 bg-white rounded-2xl shadow-xl px-5 py-3.5">
                            <div class="flex items-center gap-3">
                                <div class="flex -space-x-2">
                                    <div
                                        class="w-9 h-9 rounded-full bg-purple-200 border-2 border-white flex items-center justify-center text-xs font-bold text-purple-700">
                                        A</div>
                                    <div
                                        class="w-9 h-9 rounded-full bg-blue-200 border-2 border-white flex items-center justify-center text-xs font-bold text-blue-700">
                                        R</div>
                                    <div
                                        class="w-9 h-9 rounded-full bg-teal-200 border-2 border-white flex items-center justify-center text-xs font-bold text-teal-700">
                                        D</div>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-800">400+</p>
                                    <p class="text-xs text-slate-400">Konseli bergabung</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    
    {{-- ===================== STATISTIK STRIP ===================== --}}
    <div class="relative bg-white border-y border-slate-100 py-8">
        <div class="max-w-5xl mx-auto px-6 grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            @foreach ([['400+', 'Konseli aktif'], ['50+', 'Konselor bersertifikat'], ['98%', 'Kepuasan sesi'], ['24/7', 'Layanan tersedia']] as [$num, $label])
            <div class="reveal">
                <p class="text-3xl font-bold gradient-text mb-1">{{ $num }}</p>
                <p class="text-sm text-slate-500">{{ $label }}</p>
            </div>
            @endforeach
        </div>
    </div>


    {{-- ===================== LAYANAN ===================== --}}
    <section id="layanan" class="py-24">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center max-w-2xl mx-auto mb-16 reveal">
                <span class="text-xs font-bold uppercase tracking-widest text-brand-500 mb-3 block">Apa yang Kami
                    Tawarkan</span>
                <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mb-4">
                    Dukungan yang Tepat<br>untuk Setiap Perasaan
                </h2>
                <p class="text-slate-500 text-lg">Apapun yang sedang kamu hadapi, kami punya ruang dan konselor yang
                    siap menemanimu.</p>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ([
                [
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round"d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" /><path stroke-linecap="round" stroke-linejoin="round"d="M12 9v4" /><path stroke-linecap="round" stroke-linejoin="round"d="M12 17h.01" />',
                'color' => 'bg-rose-50 text-rose-500',
                'title' => 'Kecemasan & Stress',
                'desc' => 'Belajar mengelola kecemasan, stres akademik, pekerjaan, atau kehidupan sehari-hari bersama konselor kami.',
                ],
                [
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round"d="M15 15s-1.5-2-3-2-3 2-3 2m-1-6h.01M16 9h.01M12 21a9 9 0 100-18 9 9 0 000 18z" />',
                'color' => 'bg-amber-50 text-amber-500',
                'title' => 'Depresi & Mood Rendah',
                'desc' => 'Dapatkan pendampingan emosional dan strategi berbasis bukti untuk melewati periode gelap dalam hidupmu.',
                ],
                [
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round"d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />',
                'color' => 'bg-brand-50 text-brand-500',
                'title' => 'Masalah Relasi & Keluarga',
                'desc' => 'Navigasi dinamika hubungan yang rumit dengan panduan konselor berpengalaman di bidangnya.',
                ],
                [
               'icon' => '<path stroke-linecap="round" stroke-linejoin="round"d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /><path stroke-linecap="round" stroke-linejoin="round"d="M8 12h2l1.5-3 3 6 1.5-3H18" />',
                'color' => 'bg-teal-50 text-teal-500',
                'title' => 'Kesehatan Mental Mahasiswa',
                'desc' => 'Khusus untuk kamu yang berjuang dengan tekanan akademik, identitas diri, dan transisi ke dunia kerja.',
                ],
                [
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round"d="M12 2l7 3v6c0 5-3.5 9-7 11-3.5-2-7-6-7-11V5l7-3z" />',
                'color' => 'bg-purple-50 text-purple-500',
                'title' => 'Trauma & Pemulihan',
                'desc' => 'Ruang aman untuk memproses pengalaman sulit dan memulai perjalanan pemulihan yang bermakna.',
                ],
                [
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round"d="M4 16l5-5 4 4 7-7M14 8h6v6" />',
                'color' => 'bg-green-50 text-green-500',
                'title' => 'Pengembangan Diri',
                'desc' => 'Tingkatkan kepercayaan diri, bangun kebiasaan sehat, dan raih versi terbaik dirimu bersama konselor kami.',
                ],
                ] as $service)
                <div class="bg-white rounded-2xl p-6 border border-slate-100 card-lift reveal cursor-default">
                    <div
                        class="w-11 h-11 rounded-xl {{ $service['color'] }} flex items-center justify-center mb-4">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            {!! $service['icon'] !!}
                        </svg>
                    </div>
                    <h3 class="font-bold text-slate-800 mb-2">{{ $service['title'] }}</h3>
                    <p class="text-slate-500 text-sm leading-relaxed">{{ $service['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>


    {{-- ===================== CARA KERJA ===================== --}}
    <section id="cara-kerja" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center max-w-xl mx-auto mb-16 reveal">
                <span class="text-xs font-bold uppercase tracking-widest text-brand-500 mb-3 block">Mudah &
                    Sederhana</span>
                <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mb-4">
                    Mulai dalam 3 Langkah
                </h2>
                <p class="text-slate-500">Kamu tidak perlu pengalaman sebelumnya. Cukup hadir dengan segala perasaanmu.
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8 relative">
                {{-- Connector line --}}
                <div class="hidden md:block absolute top-10 left-1/6 right-1/6 h-0.5 bg-gradient-to-r from-brand-200 via-brand-400 to-teal-400"
                    style="left:20%;right:20%;"></div>

                @foreach ([['01', 'Daftar Akun', 'Buat akun gratis dalam 2 menit. Tidak perlu data sensitif untuk memulai.', 'bg-brand-600 text-white'], ['02', 'Pilih Konselor', 'Lihat profil, keahlian, dan jadwal konselor kami. Pilih yang paling nyaman untukmu.', 'bg-teal-500 text-white'], ['03', 'Mulai Bercerita', 'Jalani sesi konseling via chat atau video. Fleksibel sesuai waktumu.', 'bg-purple-500 text-white']] as $step)
                <div class="relative text-center reveal">
                    <div
                        class="w-16 h-16 rounded-2xl {{ $step[3] }} font-bold text-xl mx-auto mb-6 flex items-center justify-center shadow-lg z-10 relative">
                        {{ $step[0] }}
                    </div>
                    <h3 class="font-bold text-slate-800 text-lg mb-2">{{ $step[1] }}</h3>
                    <p class="text-slate-500 text-sm leading-relaxed max-w-xs mx-auto">{{ $step[2] }}</p>
                </div>
                @endforeach
            </div>

            <div class="text-center mt-12 reveal">
                <a href="{{ route('register') }}"
                    class="inline-flex items-center gap-2 bg-brand-600 hover:bg-brand-700 text-white font-semibold px-8 py-4 rounded-full shadow-lg shadow-brand-300/40 transition-all hover:shadow-xl hover:-translate-y-0.5">
                    Daftar Sekarang – Gratis
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                    </svg>
                </a>
            </div>
        </div>
    </section>


    {{-- ===================== KONSELOR ===================== --}}
    <section id="konselor" class="py-24">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center max-w-xl mx-auto mb-16 reveal">
                <span class="text-xs font-bold uppercase tracking-widest text-brand-500 mb-3 block">Tim Kami</span>
                <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mb-4">
                    Konselor yang Peduli<br>dan Berpengalaman
                </h2>
                <p class="text-slate-500">Semua konselor kami memiliki latar belakang psikologi klinis dan
                    bersertifikat resmi.</p>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach ([['Nisa Rahmawati', 'M.Psi., Psikolog', 'Kecemasan & Trauma', '#dbeafe', '#1d4ed8', 'N', '5 thn'], ['Denny Kurniawan', 'M.Psi., Psikolog', 'Depresi & Stres', '#dcfce7', '#15803d', 'D', '7 thn'], ['Sari Pratiwi', 'M.Psi., Psikolog', 'Relasi & Keluarga', '#fce7f3', '#be185d', 'S', '4 thn'], ['Arief Santoso', 'M.Psi., Psikolog', 'Pengembangan Diri', '#fef3c7', '#b45309', 'A', '6 thn']] as [$name, $title, $spec, $bg, $color, $init, $exp])
                <div class="bg-white rounded-2xl p-6 border border-slate-100 card-lift reveal text-center">
                    <div class="w-16 h-16 rounded-2xl mx-auto mb-4 flex items-center justify-center text-xl font-bold"
                        style="background:{{ $bg }};color:{{ $color }}">
                        {{ $init }}
                    </div>
                    <h3 class="font-bold text-slate-800 mb-0.5">{{ $name }}</h3>
                    <p class="text-xs text-slate-400 mb-3">{{ $title }}</p>
                    <span class="inline-block text-xs font-medium px-3 py-1 rounded-full"
                        style="background:{{ $bg }};color:{{ $color }}">{{ $spec }}</span>
                    <p class="text-xs text-slate-400 mt-3">Pengalaman {{ $exp }}</p>
                    <div class="flex justify-center gap-0.5 mt-2">
                        @for ($i = 0; $i < 5; $i++)
                            <svg class="w-3.5 h-3.5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.967a1 1 0 00.95.69h4.175c.969 0 1.371 1.24.588 1.81l-3.376 2.453a1 1 0 00-.364 1.118l1.286 3.966c.3.921-.755 1.688-1.538 1.118L10 14.347l-3.958 2.872c-.783.57-1.838-.197-1.538-1.118l1.286-3.966a1 1 0 00-.364-1.118L2.05 9.394c-.783-.57-.38-1.81.588-1.81h4.175a1 1 0 00.95-.69L9.049 2.927z" />
                            </svg>
                            @endfor
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>


    {{-- ===================== TESTIMONI ===================== --}}
    <section id="testimoni" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center max-w-xl mx-auto mb-16 reveal">
                <span class="text-xs font-bold uppercase tracking-widest text-brand-500 mb-3 block">Cerita
                    Mereka</span>
                <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mb-4">
                    Ribuan Orang Telah<br>Merasakan Manfaatnya
                </h2>
            </div>

            <div class="grid md:grid-cols-3 gap-6">
                @foreach ([['Awalnya ragu mau cerita ke orang lain. Tapi konselor di sini beneran ngerti dan gak pernah judge. Sekarang aku jauh lebih bisa ngatur emosi.', 'Rizky A.', 'Mahasiswa, 21 thn'], ['Setelah putus sama pacar 3 tahun, aku ngerasa dunia runtuh. Kawan Cerito bantu aku bangkit lagi. Makasih banget.', 'Dewi S.', 'Karyawan swasta, 26 thn'], ['Sebagai cowok, aku selalu dibilang "harus kuat". Di sini aku baru ngerti, minta bantuan itu bukan kelemahan.', 'Fajar M.', 'Mahasiswa, 23 thn']] as [$quote, $name, $role])
                <div class="bg-soft rounded-2xl p-6 border border-brand-100 reveal card-lift">
                    <div class="quote-mark mb-2">"</div>
                    <p class="text-slate-700 leading-relaxed mb-5 font-serif italic">{{ $quote }}</p>
                    <div class="flex items-center gap-3">
                        <div
                            class="w-9 h-9 rounded-full bg-brand-200 flex items-center justify-center text-sm font-bold text-brand-700">
                            {{ substr($name, 0, 1) }}
                        </div>
                        <div>
                            <p class="font-semibold text-slate-800 text-sm">{{ $name }}</p>
                            <p class="text-xs text-slate-400">{{ $role }}</p>
                        </div>
                        <div class="ml-auto flex gap-0.5">
                            @for ($i = 0; $i < 5; $i++)
                                <svg class="w-3 h-3 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.967a1 1 0 00.95.69h4.175c.969 0 1.371 1.24.588 1.81l-3.376 2.453a1 1 0 00-.364 1.118l1.286 3.966c.3.921-.755 1.688-1.538 1.118L10 14.347l-3.958 2.872c-.783.57-1.838-.197-1.538-1.118l1.286-3.966a1 1 0 00-.364-1.118L2.05 9.394c-.783-.57-.38-1.81.588-1.81h4.175a1 1 0 00.95-.69L9.049 2.927z" />
                                </svg>
                                @endfor
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>


    {{-- ===================== CTA BANNER ===================== --}}
    <section class="py-20">
        <div class="max-w-4xl mx-auto px-6">
            <div
                class="relative bg-gradient-to-br from-brand-600 to-brand-700 rounded-3xl overflow-hidden p-10 md:p-16 text-center reveal">
                {{-- Decorative circles --}}
                <div
                    class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2">
                </div>
                <div
                    class="absolute bottom-0 left-0 w-48 h-48 bg-white/10 rounded-full translate-y-1/2 -translate-x-1/2">
                </div>

                <span class="relative text-brand-200 text-xs font-bold uppercase tracking-widest mb-4 block">Mulai Hari
                    Ini</span>
                <h2 class="relative text-3xl md:text-4xl font-bold text-white mb-4 leading-tight">
                    Kamu Layak Merasa Baik-Baik Saja.
                </h2>
                <p class="relative text-brand-200 text-lg mb-8 max-w-xl mx-auto">
                    Satu langkah kecil hari ini bisa mengubah segalanya. Bergabunglah dan ceritakan apa yang ada di
                    hatimu.
                </p>
                <a href="{{ route('register') }}"
                    class="relative inline-flex items-center gap-2 bg-white text-brand-700 font-bold px-8 py-4 rounded-full shadow-xl hover:bg-brand-50 transition-all hover:-translate-y-0.5">
                    Coba Gratis Sekarang
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                    </svg>
                </a>
                <p class="relative text-brand-300 text-sm mt-4">Tidak perlu kartu kredit · Langsung bisa mulai</p>
            </div>
        </div>
    </section>


    {{-- ===================== FAQ ===================== --}}
    <section class="py-20 bg-white">
        <div class="max-w-2xl mx-auto px-6">
            <div class="text-center mb-12 reveal">
                <span class="text-xs font-bold uppercase tracking-widest text-brand-500 mb-3 block">Pertanyaan
                    Umum</span>
                <h2 class="text-3xl font-bold text-slate-900">Ada yang Ingin Ditanyakan?</h2>
            </div>
            <div class="space-y-3" x-data="{ open: null }">
                @foreach ([['Apakah konseling di sini benar-benar rahasia?', 'Ya, sepenuhnya. Seluruh sesi dan data pribadi kamu dilindungi dengan enkripsi dan tidak akan dibagikan ke pihak manapun tanpa izin kamu.'], ['Berapa biaya per sesi?', 'Sesi pertama gratis! Selanjutnya kami menawarkan paket yang fleksibel dan terjangkau mulai dari Rp 75.000 per sesi.'], ['Apakah saya harus menggunakan nama asli?', 'Kamu bisa menggunakan nama samaran jika merasa lebih nyaman. Yang penting adalah kenyamanan kamu dalam bercerita.'], ['Bagaimana jika saya tidak cocok dengan konselornya?', 'Kamu bisa mengajukan pergantian konselor kapan saja tanpa biaya tambahan.'], ['Apakah ini pengganti psikiater atau terapi klinis?', 'Kawan Cerito adalah layanan konseling suportif. Untuk kondisi klinis berat, konselor kami akan merekomendasikan profesional yang tepat.']] as $i => [$q, $a])
                <div class="border border-slate-100 rounded-2xl overflow-hidden reveal" x-data="{ open: false }">
                    <button @click="open = !open"
                        class="w-full flex items-center justify-between px-6 py-4 text-left font-semibold text-slate-800 hover:bg-slate-50 transition-colors">
                        {{ $q }}
                        <svg class="w-4 h-4 text-slate-400 flex-shrink-0 transition-transform"
                            :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor"
                            stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" x-transition
                        class="px-6 pb-4 text-slate-500 text-sm leading-relaxed border-t border-slate-50">
                        <p class="pt-3">{{ $a }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>



    {{-- ===================== FOOTER ===================== --}}
    <footer class="bg-slate-900 text-slate-400 py-16">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid md:grid-cols-4 gap-10 mb-12">
                {{-- Brand --}}
                <div class="md:col-span-2">
                    <a href="#" class="flex items-center gap-2.5 mb-4">
                        <div
                            class="w-9 h-9 rounded-xl bg-gradient-to-br from-brand-500 to-teal-400 flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <span class="font-bold text-white text-lg">Kawan Cerito</span>
                    </a>
                    <p class="text-sm leading-relaxed max-w-xs">
                        Platform telekonseling yang hadir untuk menemani perjalananmu menuju kesehatan mental yang lebih
                        baik.
                    </p>
                    <p class="text-xs mt-4 text-slate-500">© {{ date('Y') }} Kawan Cerito. All rights reserved.
                    </p>
                </div>

                {{-- Links --}}
                <div>
                    <p class="font-semibold text-white mb-4 text-sm">Layanan</p>
                    <ul class="space-y-2.5 text-sm">
                        @foreach (['Konseling Individual', 'Konseling Keluarga', 'Asesmen Psikologi', 'Panduan Self-Help'] as $link)
                        <li><a href="#" class="hover:text-white transition-colors">{{ $link }}</a>
                        </li>
                        @endforeach
                    </ul>
                </div>

                <div>
                    <p class="font-semibold text-white mb-4 text-sm">Perusahaan</p>
                    <ul class="space-y-2.5 text-sm">
                        @foreach (['Tentang Kami', 'Karir', 'Blog Kesehatan Mental', 'Kontak'] as $link)
                        <li><a href="#" class="hover:text-white transition-colors">{{ $link }}</a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div
                class="border-t border-slate-800 pt-8 flex flex-col md:flex-row items-center justify-between gap-4 text-xs text-slate-500">
                <p>Dibuat dengan cinta untuk kesehatan mental Indonesia</p>
                <div class="flex gap-5">
                    <a href="#" class="hover:text-white transition-colors">Kebijakan Privasi</a>
                    <a href="#" class="hover:text-white transition-colors">Syarat & Ketentuan</a>
                </div>
            </div>
        </div>
    </footer>

    {{-- Alpine.js for accordion --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- Scroll reveal --}}
    <script>
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(e => {
                if (e.isIntersecting) {
                    e.target.classList.add('visible');
                    observer.unobserve(e.target);
                }
            });
        }, {
            threshold: 0.1
        });

        document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
    </script>

</body>

</html>