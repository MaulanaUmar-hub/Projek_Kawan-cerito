{{-- ===================== HERO ===================== --}}
<section
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

                        <svg class="w-5 h-5 text-[#5B67F1]" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" />
                            <polygon points="10 8 16 12 10 16 10 8" fill="currentColor" stroke="none" />
                        </svg>

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
