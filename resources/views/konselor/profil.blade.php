<x-app-layout>
    <x-slot name="dashboardRole">konselor</x-slot>
    <x-slot name="headerTitle">Profil Konselor</x-slot>

    @php
    $initial = strtoupper(mb_substr($profile['nama'], 0, 1));
    $note = $profile['catatan_profil'] ?: 'Tulis status singkat mengenai pendekatan konseling Anda.';
    $peminatan = $profile['peminatan'] ?: 'Belum ditambahkan';
    $peminatanTags = collect(explode(',', $profile['peminatan'] ?? ''))
    ->map(fn ($item) => trim($item))
    ->filter()
    ->values();

    // Status Kelengkapan untuk Badge Penanda
    $statusFoto = (bool) $profile['foto_url'];
    $statusNote = filled($profile['catatan_profil']);
    $statusPeminatan = filled($profile['peminatan']);
    $statusSpesialisasi = filled($profile['spesialisasi']) && $profile['spesialisasi'] !== '-';

    // Hitung Persentase Kelengkapan
    $profileItems = [
    ['done' => $statusFoto],
    ['done' => $statusNote],
    ['done' => $statusPeminatan],
    ['done' => $statusSpesialisasi],
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

    @if ($errors->any())
    <div class="mb-3 rounded-xl border border-red-100 bg-red-50 px-4 py-2 text-xs font-semibold text-red-600">
        Periksa kembali data profil yang Anda isi.
    </div>
    @endif

    <section class="space-y-4 max-w-[1400px] mx-auto text-slate-700">

        <!-- Header Profil Ringkas + Progress Persentase -->
        <div class="kc-card p-4 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-kc-heading">Profil Konselor</h2>
                <p class="text-xs text-slate-400">Kelola informasi publik dan pendekatan konseling Anda.</p>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <div class="flex items-center gap-2 rounded-lg border border-slate-100 bg-slate-50 px-3 py-1.5 text-xs">
                    <span class="font-semibold t <!-- Toast Notification (Melayang & Auto-dismiss) -->ext-slate-500">Status:</span>
                    <x-dashboard.status-badge :status="$profile['status']" />
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
        <div class="grid gap-4 xl:grid-cols-[340px_1fr] items-start">

            <!-- KOLOM KIRI: Live Preview Card -->
            <article class="kc-card p-5 flex flex-col justify-between h-full space-y-4 border border-slate-100">
                <div class="space-y-4">
                    <div class="flex flex-col items-center text-center pt-2">
                        <div class="relative inline-block">
                            @if ($profile['foto_url'])
                            <img src="{{ $profile['foto_url'] }}" alt="Foto profil {{ $profile['nama'] }}" class="h-24 w-24 rounded-full object-cover shadow-md ring-4 ring-slate-50">
                            @else
                            <div class="flex h-24 w-24 items-center justify-center rounded-full bg-indigo-500 text-3xl font-bold text-white shadow-md">
                                {{ $initial }}
                            </div>
                            @endif
                            <span class="absolute bottom-1 right-1 h-3.5 w-3.5 rounded-full bg-emerald-400 ring-2 ring-white"></span>
                        </div>

                        <h3 class="mt-3 text-lg font-bold text-kc-heading">{{ $profile['nama'] }}</h3>
                        <p class="text-xs text-slate-400">{{ $profile['email'] }}</p>
                    </div>

                    <!-- Note Status Box -->
                    <div class="rounded-xl bg-slate-50 p-3.5 border border-slate-100">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-indigo-500 mb-1">Note Status</p>
                        <p class="text-xs leading-relaxed italic text-slate-600">"{{ $note }}"</p>
                    </div>
                </div>

                <!-- Detail Meta -->
                <div class="space-y-2.5 text-xs pt-2">
                    <div class="flex justify-between items-start py-1.5 border-b border-slate-100">
                        <span class="text-slate-400 font-medium">Spesialisasi</span>
                        <span class="font-semibold text-kc-heading text-right max-w-[180px] truncate">{{ $profile['spesialisasi'] }}</span>
                    </div>
                    <div class="flex justify-between items-start py-1.5 border-b border-slate-100">
                        <span class="text-slate-400 font-medium">WhatsApp</span>
                        <span class="font-semibold text-kc-heading truncate max-w-[180px]">{{ $profile['link_whatsapp'] }}</span>
                    </div>
                    <div class="flex justify-between items-center py-0.5">
                        <span class="text-slate-400 font-medium shrink-0">Peminatan</span>

                        @if ($peminatanTags->isNotEmpty())
                        <!-- Menggunakan flex-row-reverse agar tag otomatis merapat ke sisi kanan -->
                        <div class="flex flex-wrap flex-row-reverse gap-1.5 max-w-[200px]">
                            @foreach ($peminatanTags as $tag)
                            <span class="rounded-md bg-indigo-50 px-2.5 py-0.5 text-[11px] font-semibold text-indigo-600 whitespace-nowrap">
                                {{ $tag }}
                            </span>
                            @endforeach
                        </div>
                        @else
                        <span class="font-semibold text-slate-400 italic text-[11px]">{{ $peminatan }}</span>
                        @endif
                    </div>
                </div>
            </article>

            <!-- KOLOM KANAN: Form Edit Profil -->
            <article class="kc-card p-5 h-full">
                <form method="POST" action="{{ route('konselor.profil.update') }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @method('PATCH')

                    <!-- Input Foto Profil -->
                    <div class="rounded-xl border border-slate-100 bg-slate-50/50 p-3 flex flex-col sm:flex-row items-start sm:items-center gap-4">
                        <div class="shrink-0 mx-auto sm:mx-0">
                            @if ($profile['foto_url'])
                            <img src="{{ $profile['foto_url'] }}" alt="Preview" class="h-12 w-12 rounded-xl object-cover shadow-sm">
                            @else
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-500 text-xl font-bold text-white shadow-sm">
                                {{ $initial }}
                            </div>
                            @endif
                        </div>
                        <div class="w-full space-y-1">
                            <div class="flex items-center justify-between">
                                <label for="foto" class="text-xs font-bold text-kc-heading">Foto Profil</label>
                                <span class="rounded px-1.5 py-0.5 text-[10px] font-bold {{ $statusFoto ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600' }}">
                                    {{ $statusFoto ? 'Lengkap' : 'Perlu diisi' }}
                                </span>
                            </div>
                            <input id="foto" name="foto" type="file" accept="image/*" class="block w-full rounded-lg border border-slate-200 bg-white px-2 py-1 text-xs text-slate-500 file:mr-2 file:rounded file:border-0 file:bg-indigo-500 file:px-2 file:py-0.5 file:text-[11px] file:font-semibold file:text-white">
                        </div>
                    </div>

                    <!-- Input Note Status -->
                    <div class="space-y-1">
                        <div class="flex items-center justify-between">
                            <label for="catatan_profil" class="text-xs font-bold text-kc-heading">Note Status</label>
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] text-slate-400">Maks. 500 karakter</span>
                                <span class="rounded px-1.5 py-0.5 text-[10px] font-bold {{ $statusNote ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600' }}">
                                    {{ $statusNote ? 'Lengkap' : 'Perlu diisi' }}
                                </span>
                            </div>
                        </div>
                        <textarea id="catatan_profil" name="catatan_profil" rows="2" maxlength="500" placeholder="Contoh: Saya mendampingi konseli dengan pendekatan yang tenang dan suportif." class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs leading-relaxed outline-none transition focus:ring-2 focus:ring-indigo-100 focus:border-indigo-400">{{ old('catatan_profil', $profile['catatan_profil']) }}</textarea>
                    </div>

                    <!-- Row Grid: Peminatan & Spesialisasi -->
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="space-y-1">
                            <div class="flex items-center justify-between">
                                <label for="peminatan" class="text-xs font-bold text-kc-heading">Peminatan</label>
                                <span class="rounded px-1.5 py-0.5 text-[10px] font-bold {{ $statusPeminatan ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600' }}">
                                    {{ $statusPeminatan ? 'Lengkap' : 'Perlu diisi' }}
                                </span>
                            </div>
                            <input id="peminatan" name="peminatan" type="text" value="{{ old('peminatan', $profile['peminatan']) }}" placeholder="Pisahkan dengan koma (contoh: Remaja, Kecemasan)" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs outline-none transition focus:ring-2 focus:ring-indigo-100 focus:border-indigo-400">
                        </div>

                        <div class="space-y-1">
                            <div class="flex items-center justify-between">
                                <label for="spesialisasi" class="text-xs font-bold text-kc-heading">Spesialisasi</label>
                                <span class="rounded px-1.5 py-0.5 text-[10px] font-bold bg-slate-100 text-slate-500">
                                    Kunci (Admin)
                                </span>
                            </div>
                            <input id="spesialisasi" name="spesialisasi" type="text" value="{{ old('spesialisasi', $profile['spesialisasi']) }}" class="w-full rounded-xl border border-slate-200 bg-slate-100 px-3 py-2 text-xs text-slate-400 cursor-not-allowed outline-none" readonly>
                        </div>
                    </div>

                    <!-- Informasi Akun (Readonly Grid) -->
                    <div class="rounded-xl border border-slate-100 bg-slate-50/70 p-3">
                        <h4 class="text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-2">Informasi Akun</h4>
                        <dl class="grid gap-x-4 gap-y-2 text-xs grid-cols-2 sm:grid-cols-4">
                            <div>
                                <dt class="text-slate-400 text-[11px]">Nama</dt>
                                <dd class="font-semibold text-kc-heading truncate">{{ $profile['nama'] }}</dd>
                            </div>
                            <div>
                                <dt class="text-slate-400 text-[11px]">Email</dt>
                                <dd class="font-semibold text-kc-heading truncate">{{ $profile['email'] }}</dd>
                            </div>
                            <div>
                                <dt class="text-slate-400 text-[11px]">Nomor HP</dt>
                                <dd class="font-semibold text-kc-heading">{{ $profile['no_hp'] }}</dd>
                            </div>
                            <div>
                                <dt class="text-slate-400 text-[11px]">Gender</dt>
                                <dd class="font-semibold text-kc-heading">{{ $profile['gender'] }}</dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="flex flex-col gap-2 pt-1 sm:flex-row sm:justify-end">
                        <a href="{{ route('konselor.dashboard') }}" class="inline-flex justify-center items-center rounded-xl border border-slate-200 px-4 py-2 text-xs font-semibold text-slate-500 transition hover:bg-slate-50 sm:w-auto">
                            Kembali
                        </a>
                        <button type="submit" class="inline-flex justify-center items-center rounded-xl bg-indigo-500 px-4 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-indigo-600 sm:w-auto">
                            Simpan Profil
                        </button>
                    </div>
                </form>
            </article>

        </div>
    </section>
</x-app-layout>