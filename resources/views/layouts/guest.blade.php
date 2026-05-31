<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Kawan Cerito</title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])
</head>

<body class="bg-[#F5F7FF]">

<div class="min-h-screen flex items-center justify-center px-6 py-8">

    <div class="w-full max-w-[1500px] bg-white rounded-[36px] overflow-hidden shadow-sm grid lg:grid-cols-[1.08fr_0.92fr] min-h-[920px]">

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
                        Tempat aman untuk
                        bercerita dan
                        <span class="auth-hero-accent">
                            didengar.
                        </span>
                    </h2>

                    <p class="auth-hero-subtitle">
                        Bergabung bersama ribuan pengguna yang mulai peduli terhadap kesehatan mentalnya.
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
                        Konseling online kapan saja.
                    </p>
                </div>

            </div>
        </div>

        {{-- RIGHT PANEL --}}
        <div class="bg-white overflow-y-auto">

            <div class="max-w-[680px] mx-auto px-10 pt-20 pb-14 lg:px-16">

                {{-- HEADER --}}
                <div class="mb-12">

                    <h2 class="text-[56px] leading-[1.05] font-extrabold tracking-[-0.04em] text-[#0B132B] mb-4">
                        Buat akun baru ✨
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
