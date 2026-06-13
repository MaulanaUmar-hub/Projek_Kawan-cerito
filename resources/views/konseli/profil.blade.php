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

        // Status Kelengkapan untuk Badge Penanda (Sesuai field Konseli)
        $statusNama = filled($profile->nama);
        $statusEmail = filled($profile->email);
        $statusAsal = filled($profile->asal);
        $statusHp = filled($profile->no_hp);
        $statusGender = filled($profile->gender) && !in_array($profile->gender, ['Belum dipilih', '']);

        // Hitung Persentase Kelengkapan (5 Indikator Data Utama)
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

    <!-- Toast Notification (Melayang & Auto-dismiss jika ada session success) -->
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

    <section class="space-y-4 max-w-[1400px] mx-auto text-slate-700">
        
        <!-- Header Profil Ringkas + Progress Persentase -->
        <div class="kc-card p-4 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-kc-heading">Profil Saya</h2>
                <p class="text-xs text-slate-400">Lihat informasi dasar akun konseli kamu untuk kebutuhan layanan.</p>
            </div>
            
            <div class="flex flex-wrap items-center gap-3">
                <div class="flex items-center gap-2 rounded-lg border border-slate-100 bg-slate-50 px-3 py-1.5 text-xs">
                    <span class="font-semibold text-slate-500">Status Akun:</span>
                    <span class="inline-flex items-center gap-1 font-bold text-emerald-600">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Aktif
                    </span>
                </div>
                
                <div class="flex items-center gap-3 rounded-lg border border-emerald-100 bg-emerald-50/60 px-3 py-1.5 text-xs">
                    <span class="font-semibold text-emerald-700">Kelengkapan Profil: <span class="font-bold text-emerald-600">{{ $profileProgress }}%</span></span>
                    <div class="w-20 h-2 bg-white rounded-full overflow-hidden border border-emerald-100 hidden sm:block">
                        <div class="h-full bg-emerald-400 transition-all duration-500" style="width: {{ $profileProgress }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid gap-4 xl:grid-cols-[340px_1fr] items-stretch">
            
            <!-- KOLOM KIRI: Live Preview Card -->
            <article class="kc-card p-5 flex flex-col justify-between border border-slate-100 bg-white">
                <div class="space-y-5">
                    <!-- Foto & Identitas Utama -->
                    <div class="flex flex-col items-center text-center pt-2">
                        <div class="relative inline-block">
                            @if ($profile->foto_url)
                            <img src="{{ $profile->foto_url }}" alt="Foto profil {{ $profile->nama }}" class="h-24 w-24 rounded-full object-cover shadow-sm ring-4 ring-slate-50" onerror="this.style.display='none'">
                            @else
                            <div class="flex h-24 w-24 items-center justify-center rounded-full bg-indigo-500 text-3xl font-bold text-white shadow-sm">
                                {{ $initial }}
                            </div>
                            @endif
                            <span class="absolute bottom-1 right-1 h-3.5 w-3.5 rounded-full bg-emerald-400 ring-2 ring-white"></span>
                        </div>

                        <h3 class="mt-3 text-lg font-bold text-kc-heading">{{ $profile->nama ?: 'Konseli' }}</h3>
                        <p class="text-xs text-slate-400 break-all max-w-full">{{ $profile->email ?: '-' }}</p>
                    </div>

                    <!-- Note Info Box -->
                    <div class="rounded-xl bg-slate-50/80 p-4 border border-slate-100">
                        <p class="text-[10px] font-bold uppercase tracking-wider text-indigo-500 mb-1">Ringkasan</p>
                        <p class="text-xs leading-relaxed text-slate-600">
                            Profil ini digunakan sebagai data rekam dasar medis atau akademis sebelum kamu mengajukan proses jadwal konseling.
                        </p>
                    </div>
                </div>

                <!-- Detail Meta Bagian Bawah Sejajar -->
                <div class="space-y-4 text-xs pt-4 border-t border-slate-50">
                    <div class="flex justify-between items-center py-0.5">
                        <span class="text-slate-400 font-medium">Asal/Instansi</span>
                        <span class="font-semibold text-kc-heading max-w-[180px] truncate text-right">{{ $profile->asal ?: '-' }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center py-0.5">
                        <span class="text-slate-400 font-medium">Gender</span>
                        <span class="font-semibold text-kc-heading truncate max-w-[180px] text-right">{{ $genderLabel }}</span>
                    </div>

                    <div class="flex justify-between items-center py-0.5">
                        <span class="text-slate-400 font-medium">Nomor HP</span>
                        <span class="font-semibold text-kc-heading truncate max-w-[180px] text-right">{{ $profile->no_hp ?: '-' }}</span>
                    </div>
                </div>
            </article>

            <!-- KOLOM KANAN: Detail Informasi Akun dengan Badge Status Pengisian -->
            <article class="kc-card p-5 bg-white flex flex-col justify-between">
                <div class="space-y-5">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <div>
                            <h3 class="text-base font-bold text-kc-heading">Informasi Profil</h3>
                            <p class="text-xs text-slate-400">Detail rekam data akun kamu yang aktif dan terdaftar di sistem.</p>
                        </div>
                    </div>

                    <!-- Grid Penanda Status Kelengkapan pada Setiap Item -->
                    <div class="grid gap-4 sm:grid-cols-2">
                        
                        <!-- Nama -->
                        <div class="space-y-1 rounded-xl border border-slate-100 bg-slate-50/40 p-3.5 flex flex-col justify-between">
                            <div class="flex items-center justify-between w-full">
                                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Nama Lengkap</span>
                                <span class="rounded px-1.5 py-0.5 text-[9px] font-bold {{ $statusNama ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600' }}">
                                    {{ $statusNama ? 'Lengkap' : 'Perlu diisi' }}
                                </span>
                            </div>
                            <span class="text-sm font-semibold text-kc-heading mt-2 truncate">{{ $profile->nama ?: '-' }}</span>
                        </div>

                        <!-- Email -->
                        <div class="space-y-1 rounded-xl border border-slate-100 bg-slate-50/40 p-3.5 flex flex-col justify-between">
                            <div class="flex items-center justify-between w-full">
                                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Alamat Email</span>
                                <span class="rounded px-1.5 py-0.5 text-[9px] font-bold {{ $statusEmail ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600' }}">
                                    {{ $statusEmail ? 'Lengkap' : 'Perlu diisi' }}
                                </span>
                            </div>
                            <span class="text-sm font-semibold text-kc-heading mt-2 truncate">{{ $profile->email ?: '-' }}</span>
                        </div>

                        <!-- Asal -->
                        <div class="space-y-1 rounded-xl border border-slate-100 bg-slate-50/40 p-3.5 flex flex-col justify-between">
                            <div class="flex items-center justify-between w-full">
                                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Asal / Instansi</span>
                                <span class="rounded px-1.5 py-0.5 text-[9px] font-bold {{ $statusAsal ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600' }}">
                                    {{ $statusAsal ? 'Lengkap' : 'Perlu diisi' }}
                                </span>
                            </div>
                            <span class="text-sm font-semibold text-kc-heading mt-2 truncate">{{ $profile->asal ?: '-' }}</span>
                        </div>

                        <!-- No HP -->
                        <div class="space-y-1 rounded-xl border border-slate-100 bg-slate-50/40 p-3.5 flex flex-col justify-between">
                            <div class="flex items-center justify-between w-full">
                                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Nomor Handphone</span>
                                <span class="rounded px-1.5 py-0.5 text-[9px] font-bold {{ $statusHp ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600' }}">
                                    {{ $statusHp ? 'Lengkap' : 'Perlu diisi' }}
                                </span>
                            </div>
                            <span class="text-sm font-semibold text-kc-heading mt-2">{{ $profile->no_hp ?: '-' }}</span>
                        </div>

                        <!-- Gender (Full width di mobile / menyesuaikan grid di desktop) -->
                        <div class="space-y-1 rounded-xl border border-slate-100 bg-slate-50/40 p-3.5 flex flex-col justify-between sm:col-span-2">
                            <div class="flex items-center justify-between w-full">
                                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Jenis Kelamin (Gender)</span>
                                <span class="rounded px-1.5 py-0.5 text-[9px] font-bold {{ $statusGender ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600' }}">
                                    {{ $statusGender ? 'Lengkap' : 'Perlu diisi' }}
                                </span>
                            </div>
                            <span class="text-sm font-semibold text-kc-heading mt-2">{{ $genderLabel }}</span>
                        </div>
                    </div>

                    <!-- Catatan Privasi Keamanan -->
                    <div class="rounded-xl border border-slate-100 bg-slate-50/70 p-4">
                        <h4 class="text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Catatan Privasi Akun</h4>
                        <p class="text-xs leading-relaxed text-slate-500">
                            Seluruh data profil kamu dienkripsi dengan aman untuk mendukung efektivitas proses pendaftaran konseling, dijamin kerahasiaannya, serta tidak ditampilkan sebagai konsumsi umum/publik.
                        </p>
                    </div>
                </div>

                <!-- Tombol Aksi Menuju Setup / Perbarui Data -->
                <div class="flex flex-col gap-2 pt-3 sm:flex-row sm:justify-end border-t border-slate-50 mt-6">
                    <a href="{{ route('konseli.dashboard') }}" class="inline-flex justify-center items-center rounded-xl border border-slate-200 px-4 py-2 text-xs font-semibold text-slate-500 transition hover:bg-slate-50 sm:w-auto">
                        Kembali ke Dashboard
                    </a>
                    <a href="{{ route('konseli.profile.setup') }}" class="inline-flex justify-center items-center rounded-xl bg-indigo-500 px-4 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-indigo-600 sm:w-auto">
                        Perbarui Profil
                    </a>
                </div>
            </article>

        </div>
    </section>
</x-app-layout>