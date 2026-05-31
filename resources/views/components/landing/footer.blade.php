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