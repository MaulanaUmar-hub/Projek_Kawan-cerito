<x-app-layout>
    <x-slot name="dashboardRole">konseli</x-slot>
    <x-slot name="headerTitle">Profil Saya</x-slot>

    @php
        $initial = strtoupper(mb_substr($profile->nama ?: 'K', 0, 1));
        $genderLabel = match ($profile->gender) {
            'L' => 'Laki-laki',
            'P' => 'Perempuan',
            'N' => 'Tidak ingin memberi tahu',
            default => 'Belum dipilih',
        };

        $statusNama = filled($profile->nama);
        $statusEmail = filled($profile->email);
        $statusAsal = filled($profile->asal);
        $statusHp = filled($profile->no_hp);
        $statusGender = filled($profile->gender) && !in_array($profile->gender, ['Belum dipilih', '']);

        $profileItems = [
            ['done' => $statusNama],
            ['done' => $statusEmail],
            ['done' => $statusAsal],
            ['done' => $statusHp],
            ['done' => $statusGender],
        ];
        $completedItems = collect($profileItems)->where('done', true)->count();
        $profileProgress = round(($completedItems / count($profileItems)) * 100);
    @endphp

    @if (session('success'))
    <div id="toast-success" class="fixed top-5 right-5 z-50 flex items-center gap-2 rounded-xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white shadow-xl transition-all duration-300 transform translate-y-0 opacity-100">
        <span class="text-emerald-400">✓</span>
        <span>{{ session('success') }}</span>
    </div>
    <script>
        setTimeout(() => {
            const toast = document.getElementById('toast-success');
            if (toast) {
                toast.classList.add('opacity-0', '-translate-y-2');
                setTimeout(() => toast.remove(), 300);
            }
        }, 3000);
    </script>
    @endif

    {{-- PEMBARUAN: Hapus pembatas max-width kaku agar melebar penuh sesuai screen --}}
    <section class="space-y-4 text-slate-700">
        
        {{-- HEADER PROFIL RINGKAS --}}
        <div class="bg-white rounded-[20px] p-4 border border-slate-200 shadow-[0_4px_16px_-4px_rgba(0,0,0,0.02)] flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-xl font-black tracking-tight text-slate-900">Profil Saya</h2>
                <p class="text-xs text-slate-500">Lihat informasi dasar akun konseli kamu untuk kebutuhan layanan.</p>
            </div>
            
            <div class="flex flex-wrap items-center gap-3">
                <div class="flex items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-3 py-1.5 text-xs">
                    <span class="font-semibold text-slate-500">Status Akun:</span>
                    <span class="inline-flex items-center gap-1 font-bold text-emerald-700">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Aktif
                    </span>
                </div>
                
                {{-- KELENGKAPAN PROFIL DENGAN WARNA HIJAU PASTEL BALANCED --}}
                <div class="flex items-center gap-3 rounded-xl border border-green-200 bg-[#E6F5EA] px-3 py-1.5 text-xs">
                    <span class="font-bold text-green-800">Kelengkapan Profil: <span class="font-black text-green-700">{{ $profileProgress }}%</span></span>
                    <div class="w-20 h-1.5 bg-white rounded-full overflow-hidden border border-green-100 hidden sm:block">
                        <div class="h-full bg-green-500 transition-all duration-500" style="width: {{ $profileProgress }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- MAIN CONTENT GRID (LEBAR PENH KANAN KIRI) --}}
        <div class="grid gap-4 xl:grid-cols-[340px_1fr] items-stretch">
            
            {{-- KOLOM KIRI: LIVE PREVIEW --}}
            <article class="overflow-hidden bg-white rounded-[20px] border border-slate-200 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="relative h-28 bg-gradient-to-br from-indigo-500 via-violet-400 to-cyan-300">
                        <div class="absolute inset-0 bg-white/10"></div>
                    </div>

                    <div class="-mt-12 flex flex-col items-center px-5 text-center">
                        <div class="relative">
                            <div class="flex h-24 w-24 items-center justify-center overflow-hidden rounded-full bg-gradient-to-br from-purple-500 to-indigo-400 text-3xl font-black text-white shadow-md ring-4 ring-white">
                                <span>{{ $initial }}</span>
                                @if ($profile->foto_url)
                                <img
                                    src="{{ $profile->foto_url }}"
                                    alt="Foto profil {{ $profile->nama ?: 'Konseli' }}"
                                    class="absolute inset-0 h-full w-full rounded-full object-cover"
                                >
                                @endif
                            </div>
                            <span class="absolute bottom-1 right-1 h-3.5 w-3.5 rounded-full bg-green-500 ring-2 ring-white"></span>
                        </div>

                        <h3 class="mt-3 text-base font-bold text-slate-900">{{ $profile->nama ?: 'Konseli' }}</h3>
                        <p class="text-xs text-slate-400 break-all max-w-full font-medium">{{ $profile->email ?: '-' }}</p>
                    </div>

                    {{-- NOTE INFO BOX UNGU PASTEL --}}
                    <div class="mx-5 mt-4 rounded-xl bg-[#F3E8FF] p-4 border border-purple-200/60">
                        <p class="text-[10px] font-bold uppercase tracking-wider text-purple-700 mb-1">Ringkasan</p>
                        <p class="text-xs leading-relaxed text-slate-700 font-medium">
                            Profil ini digunakan sebagai data rekam dasar medis atau akademis sebelum kamu mengajukan proses jadwal konseling.
                        </p>
                    </div>
                </div>

                <div class="mx-5 mb-5 space-y-3 text-xs pt-4 border-t border-slate-100 mt-4">
                    <div class="flex justify-between items-center py-0.5">
                        <span class="text-slate-500 font-medium">Asal/Instansi</span>
                        <span class="font-bold text-slate-900 max-w-[180px] truncate text-right">{{ $profile->asal ?: '-' }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center py-0.5">
                        <span class="text-slate-500 font-medium">Gender</span>
                        <span class="font-bold text-slate-900 truncate max-w-[180px] text-right">{{ $genderLabel }}</span>
                    </div>

                    <div class="flex justify-between items-center py-0.5">
                        <span class="text-slate-500 font-medium">Nomor HP</span>
                        <span class="font-bold text-slate-900 truncate max-w-[180px] text-right">{{ $profile->no_hp ?: '-' }}</span>
                    </div>
                </div>
            </article>

            {{-- KOLOM KANAN: DETAIL INFORMASI AKUN (DENGAN RE-DESIGN ROUNDED-2XL) --}}
            <article class="bg-white rounded-[20px] border border-slate-200 shadow-sm p-5 flex flex-col justify-between">
                <div class="space-y-5">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <div>
                            <h3 class="text-sm font-bold text-slate-900">Informasi Profil</h3>
                            <p class="text-xs text-slate-500">Detail rekam data akun kamu yang aktif dan terdaftar di sistem.</p>
                        </div>
                    </div>

                    <div class="grid gap-3.5 sm:grid-cols-2">
                        
                        <div class="space-y-1 rounded-2xl border border-slate-200 bg-slate-50/50 p-3.5 flex flex-col justify-between">
                            <div class="flex items-center justify-between w-full">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Nama Lengkap</span>
                                <span class="rounded-lg px-2 py-0.5 text-[9px] font-bold {{ $statusNama ? 'bg-green-100 text-green-700 border border-green-200/50' : 'bg-amber-100 text-amber-700 border border-amber-200/50' }}">
                                    {{ $statusNama ? 'Lengkap' : 'Perlu diisi' }}
                                </span>
                            </div>
                            <span class="text-xs font-bold text-slate-900 mt-2 truncate">{{ $profile->nama ?: '-' }}</span>
                        </div>

                        <div class="space-y-1 rounded-2xl border border-slate-200 bg-slate-50/50 p-3.5 flex flex-col justify-between">
                            <div class="flex items-center justify-between w-full">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Alamat Email</span>
                                <span class="rounded-lg px-2 py-0.5 text-[9px] font-bold {{ $statusEmail ? 'bg-green-100 text-green-700 border border-green-200/50' : 'bg-amber-100 text-amber-700 border border-amber-200/50' }}">
                                    {{ $statusEmail ? 'Lengkap' : 'Perlu diisi' }}
                                </span>
                            </div>
                            <span class="text-xs font-bold text-slate-900 mt-2 truncate">{{ $profile->email ?: '-' }}</span>
                        </div>

                        <div class="space-y-1 rounded-2xl border border-slate-200 bg-slate-50/50 p-3.5 flex flex-col justify-between">
                            <div class="flex items-center justify-between w-full">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Asal / Instansi</span>
                                <span class="rounded-lg px-2 py-0.5 text-[9px] font-bold {{ $statusAsal ? 'bg-green-100 text-green-700 border border-green-200/50' : 'bg-amber-100 text-amber-700 border border-amber-200/50' }}">
                                    {{ $statusAsal ? 'Lengkap' : 'Perlu diisi' }}
                                </span>
                            </div>
                            <span class="text-xs font-bold text-slate-900 mt-2 truncate">{{ $profile->asal ?: '-' }}</span>
                        </div>

                        <div class="space-y-1 rounded-2xl border border-slate-200 bg-slate-50/50 p-3.5 flex flex-col justify-between">
                            <div class="flex items-center justify-between w-full">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Nomor Handphone</span>
                                <span class="rounded-lg px-2 py-0.5 text-[9px] font-bold {{ $statusHp ? 'bg-green-100 text-green-700 border border-green-200/50' : 'bg-amber-100 text-amber-700 border border-amber-200/50' }}">
                                    {{ $statusHp ? 'Lengkap' : 'Perlu diisi' }}
                                </span>
                            </div>
                            <span class="text-xs font-bold text-slate-900 mt-2">{{ $profile->no_hp ?: '-' }}</span>
                        </div>

                        <div class="space-y-1 rounded-2xl border border-slate-200 bg-slate-50/50 p-3.5 flex flex-col justify-between sm:col-span-2">
                            <div class="flex items-center justify-between w-full">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Jenis Kelamin (Gender)</span>
                                <span class="rounded-lg px-2 py-0.5 text-[9px] font-bold {{ $statusGender ? 'bg-green-100 text-green-700 border border-green-200/50' : 'bg-amber-100 text-amber-700 border border-amber-200/50' }}">
                                    {{ $statusGender ? 'Lengkap' : 'Perlu diisi' }}
                                </span>
                            </div>
                            <span class="text-xs font-bold text-slate-900 mt-2">{{ $genderLabel }}</span>
                        </div>
                    </div>

                    {{-- CATATAN PRIVASI (BLUE PASTEL - INFORMASI UMUM) --}}
                    <div class="rounded-2xl border border-sky-200 bg-[#E0F2FE] p-4">
                        <h4 class="text-[10px] font-bold uppercase tracking-wider text-sky-800 mb-1">Catatan Privasi Akun</h4>
                        <p class="text-xs leading-relaxed text-sky-900/90 font-medium">
                            Seluruh data profil kamu dienkripsi dengan aman untuk mendukung efektivitas pendaftaran konseling, dijamin kerahasiaannya, serta tidak akan dipublikasikan ke publik.
                        </p>
                    </div>
                </div>

                {{-- BUTTONS ACTION --}}
                <div class="flex flex-col gap-2 pt-3 sm:flex-row sm:justify-end border-t border-slate-100 mt-5">
                    <a href="{{ route('konseli.dashboard') }}" class="inline-flex justify-center items-center rounded-xl border border-slate-200 bg-white px-4 py-2 text-xs font-bold text-slate-500 transition hover:bg-slate-50 sm:w-auto">
                        Kembali
                    </a>
                    <a href="{{ route('konseli.profile.setup') }}" class="inline-flex justify-center items-center rounded-xl bg-indigo-600 px-4 py-2 text-xs font-bold text-white shadow-md shadow-indigo-100 transition hover:bg-indigo-700 sm:w-auto">
                        Perbarui Profil
                    </a>
                </div>
            </article>

        </div>
    </section>
</x-app-layout>
