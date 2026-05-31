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
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>',
            'color' => 'bg-rose-50 text-rose-500',
            'title' => 'Kecemasan & Stress',
            'desc' => 'Belajar mengelola kecemasan, stres akademik, pekerjaan, atau kehidupan sehari-hari bersama konselor kami.',
        ],
        [
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>',
            'color' => 'bg-amber-50 text-amber-500',
            'title' => 'Depresi & Mood Rendah',
            'desc' => 'Dapatkan pendampingan emosional dan strategi berbasis bukti untuk melewati periode gelap dalam hidupmu.',
        ],
        [
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>',
            'color' => 'bg-brand-50 text-brand-500',
            'title' => 'Masalah Relasi & Keluarga',
            'desc' => 'Navigasi dinamika hubungan yang rumit dengan panduan konselor berpengalaman di bidangnya.',
        ],
        [
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>',
            'color' => 'bg-teal-50 text-teal-500',
            'title' => 'Kesehatan Mental Mahasiswa',
            'desc' => 'Khusus untuk kamu yang berjuang dengan tekanan akademik, identitas diri, dan transisi ke dunia kerja.',
        ],
        [
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>',
            'color' => 'bg-purple-50 text-purple-500',
            'title' => 'Trauma & Pemulihan',
            'desc' => 'Ruang aman untuk memproses pengalaman sulit dan memulai perjalanan pemulihan yang bermakna.',
        ],
        [
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>',
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

