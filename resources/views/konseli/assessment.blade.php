<x-app-layout>
    <x-slot name="dashboardRole">konseli</x-slot>
    <x-slot name="headerTitle">Assessment Awal</x-slot>

    <section class="space-y-5">
        {{-- PAGE HEADER MAKSIMAL TANPA PEMBATAS WIDTH --}}
        <header class="pt-2">
            <h1 class="text-2xl font-black tracking-tight text-slate-900">
                Bagaimana Kondisimu Saat Ini?
            </h1>
            <p class="mt-1 text-sm text-slate-500">
                Ceritakan kondisi awalmu secara jujur agar konselor dapat memahami kebutuhan psikologismu dengan tepat.
            </p>
        </header>

        {{-- ALERT NOTIFIKASI --}}
        @if (session('success'))
        <div class="rounded-2xl border border-green-200 bg-[#E6F5EA] p-4 text-xs font-semibold text-green-800 shadow-sm flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="bi bi-check-circle-fill text-green-600 text-sm"></i>
                <span>{{ session('success') }}</span>
            </div>
            <a href="{{ route('konseli.pengajuan') }}" class="inline-flex items-center gap-1 bg-white text-green-700 px-3 py-1 rounded-xl shadow-sm hover:bg-green-600 hover:text-white transition duration-300">
                Lanjut Ajukan Konseling →
            </a>
        </div>
        @endif

        @if ($errors->any())
        <div class="rounded-2xl border border-red-200 bg-red-50 p-4 text-xs font-semibold text-red-700 shadow-sm flex items-center gap-2">
            <i class="bi bi-exclamation-triangle-fill text-red-500 text-sm"></i>
            <span>{{ $errors->first() }}</span>
        </div>
        @endif

        {{-- MAIN CONTENT GRID (LEBAR PENH KANAN KIRI) --}}
        <div class="grid gap-5 xl:grid-cols-[1fr_360px]">

            {{-- FORM UTAMA --}}
            <x-dashboard.form-card title="Form Assessment"
                description="Informasi di bawah ini bersifat privat dan aman bersama kami.">

                <form method="POST" action="{{ route('konseli.assessment.store') }}" class="space-y-4">
                    @csrf

                    {{-- Nomor WhatsApp --}}
                    <div>
                        <label for="no_hp" class="mb-1.5 block text-xs font-bold text-slate-800 uppercase tracking-wider">
                            Nomor WhatsApp <span class="text-red-500">*</span>
                        </label>

                        <div class="flex rounded-2xl border {{ $errors->has('no_hp') ? 'border-red-400 ring-4 ring-red-100' : 'border-slate-200 focus-within:border-indigo-500 focus-within:shadow-[0_0_0_4px_rgba(91,103,241,0.15)]' }} overflow-hidden bg-white transition-all duration-200">

                            <span class="flex items-center bg-slate-50 px-4 text-xs font-bold text-slate-400 border-r border-slate-200 select-none">
                                +62 / 08
                            </span>

                            <input id="no_hp" name="no_hp" type="tel"
                                value="{{ old('no_hp', $konseli->no_hp ?? '') }}" placeholder="8123456789"
                                class="flex-1 px-4 py-2.5 text-xs font-medium bg-white text-slate-900 placeholder:text-slate-300 border-0 ring-0 focus:ring-0 focus:outline-none" />
                        </div>

                        @error('no_hp')
                        <p class="mt-1.5 text-[11px] font-semibold text-red-500 flex items-center gap-1">
                            <i class="bi bi-x-circle"></i> {{ $message }}
                        </p>
                        @enderror
                        <p class="mt-1.5 text-[11px] text-slate-400 leading-normal">
                            Digunakan untuk koordinasi ruang temu sesi daring langsung oleh konselormu.
                        </p>
                    </div>

                    {{-- Keluhan --}}
                    <div>
                        <label for="keluhan" class="mb-1.5 block text-xs font-bold text-slate-800 uppercase tracking-wider">
                            Keluhan / Permasalahan <span class="text-red-500">*</span>
                        </label>
                        
                        <textarea id="keluhan" name="keluhan" rows="6"
                            class="w-full rounded-2xl border px-4 py-3 text-xs font-medium text-slate-900 outline-none resize-none transition-all duration-300 focus:border-indigo-400 focus:ring-4 focus:ring-indigo-100 placeholder:text-slate-300 {{ $errors->has('keluhan') ? 'border-red-300 ring-4 ring-red-50' : 'border-slate-200' }}"
                            placeholder="Contoh: Akhir-akhir ini saya merasa cemas berlebih, pola tidur terganggu, dan sulit fokus saat belajar.">{{ old('keluhan') }}</textarea>
                        @error('keluhan')
                        <p class="mt-1.5 text-[11px] font-semibold text-red-500 flex items-center gap-1">
                            <i class="bi bi-x-circle"></i> {{ $message }}
                        </p>
                        @enderror
                        <p class="mt-1.5 text-[11px] text-slate-400 leading-normal">
                            Ceritakan secara ringkas garis besarnya. Cerita mendalam bisa dituangkan saat sesi tatap muka berjalan.
                        </p>
                    </div>

                    {{-- BUTTONS --}}
                    <div class="flex items-center gap-3 pt-3 border-t border-slate-50">
                        <button type="submit"
                            class="rounded-xl bg-indigo-600 px-5 py-2.5 text-xs font-bold text-white shadow-md shadow-indigo-100 transition-all duration-300 hover:scale-[1.01] hover:bg-indigo-700">
                            Simpan Assessment
                        </button>
                        <a href="{{ route('konseli.dashboard') }}"
                            class="rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-xs font-bold text-slate-600 transition-all duration-300 hover:bg-slate-50">
                            Batal
                        </a>
                    </div>
                </form>
            </x-dashboard.form-card>

            {{-- ASIDE SIDE PANEL --}}
            <aside class="space-y-4">

                {{-- ASSESSMENT TERAKHIR (HIJAU PASTEL) --}}
                <article class="bg-[#E6F5EA] rounded-2xl p-5 border border-green-200/80 shadow-sm">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xs font-black uppercase tracking-wider text-green-800">Assessment Terakhir</h2>
                        <i class="bi bi-journal-check text-green-600 text-base"></i>
                    </div>

                    @if ($lastAssessment)
                    <div class="mt-4 space-y-3 text-xs">
                        <div>
                            <p class="text-green-700/80 font-medium">Tanggal Pengisian</p>
                            <p class="mt-0.5 font-bold text-slate-900">
                                {{ \Illuminate\Support\Carbon::parse($lastAssessment->created_at)->translatedFormat('d M Y') }}
                            </p>
                        </div>
                        <div class="border-t border-green-300/30 pt-2.5">
                            <p class="text-green-700/80 font-medium">Keluhan Tercatat</p>
                            <p class="mt-1 leading-relaxed text-slate-800 line-clamp-4">{{ $lastAssessment->keluhan }}</p>
                        </div>
                    </div>
                    @else
                    <p class="mt-4 text-xs font-medium text-green-700/70">Belum ada assessment yang tersimpan dalam sistem.</p>
                    @endif
                </article>

                {{-- LANGKAH BERIKUTNYA (UNGU PASTEL) --}}
                <article class="bg-[#F3E8FF] rounded-2xl p-5 border border-purple-200/80 shadow-sm">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xs font-black uppercase tracking-wider text-purple-800">Langkah Berikutnya</h2>
                        <i class="bi bi-arrow-down-up text-purple-600"></i>
                    </div>
                    <ol class="mt-4 space-y-3 text-xs font-medium text-slate-700">
                        <li class="flex gap-2.5">
                            <span class="flex h-5 w-5 flex-none items-center justify-center rounded-md bg-white text-purple-700 font-bold shadow-sm">1</span>
                            <span>Isi nomor WhatsApp aktif serta keluhan utama Anda, lalu simpan.</span>
                        </li>
                        <li class="flex gap-2.5">
                            <span class="flex h-5 w-5 flex-none items-center justify-center rounded-md bg-white text-purple-700 font-bold shadow-sm">2</span>
                            <span>Pindah ke halaman Ajukan Konseling untuk memilih profil konselor.</span>
                        </li>
                        <li class="flex gap-2.5">
                            <span class="flex h-5 w-5 flex-none items-center justify-center rounded-md bg-white text-purple-700 font-bold shadow-sm">3</span>
                            <span>Pantau WhatsApp secara berkala menunggu konfirmasi jadwal masuk.</span>
                        </li>
                    </ol>
                </article>

                {{-- WHATSAPP AKTIF TERSIMPAN (BIRU PASTEL) --}}
                @if ($konseli->no_hp)
                <article class="bg-[#E0F2FE] rounded-2xl p-4 border border-sky-200 shadow-sm flex items-center justify-between">
                    <div class="space-y-0.5">
                        <h2 class="text-[10px] font-black uppercase tracking-wider text-sky-800">WhatsApp Aktif</h2>
                        <p class="text-xs font-extrabold text-slate-900">+62 {{ $konseli->no_hp }}</p>
                    </div>
                    <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-white text-sky-600 shadow-sm">
                        <i class="bi bi-whatsapp"></i>
                    </span>
                </article>
                @endif
            </aside>
        </div>
    </section>
</x-app-layout>