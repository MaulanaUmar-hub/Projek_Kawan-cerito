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