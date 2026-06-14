<x-app-layout>
    <x-slot name="dashboardRole">konseli</x-slot>
    <x-slot name="headerTitle">Assessment Awal</x-slot>

    <section class="space-y-6">
        <x-dashboard.page-header title="Bagaimana Kondisimu saat ini ?"
            subtitle="Ceritakan kondisi awalmu agar konselor dapat memahami kebutuhanmu." />

        @if (session('success'))
            <div class="kc-card border-emerald-100 bg-emerald-50 p-4 text-sm font-medium text-emerald-700">
                {{ session('success') }}
                <a href="{{ route('konseli.pengajuan') }}" class="ml-2 font-semibold text-emerald-800 underline">
                    Ajukan konseling
                </a>
            </div>
        @endif

        @if ($errors->any())
            <div class="kc-card border-red-100 bg-red-50 p-4 text-sm font-medium text-red-700">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="grid gap-6 xl:grid-cols-[1fr_360px]">
            <x-dashboard.form-card title="Form Assessment"
                description="Isi dengan bahasa yang nyaman. Informasi ini membantu konselor memahami kondisi awalmu.">
                <form method="POST" action="{{ route('konseli.assessment.store') }}" class="space-y-5">
                    @csrf

                    {{-- Nomor WhatsApp --}}
                    <div>
                        <label for="no_hp" class="mb-2 block text-sm font-semibold text-kc-heading">
                            Nomor WhatsApp
                            <span class="text-red-400">*</span>
                        </label>
                        <div
                            class="flex rounded-lg border {{ $errors->has('no_hp') ? 'border-red-300' : 'border-slate-200' }} overflow-hidden focus-within:border-indigo-400 focus-within:ring-4 focus-within:ring-indigo-100 transition">
                            <span
                                class="flex items-center bg-slate-50 px-3 text-sm text-slate-400 border-r border-slate-200">
                                +62 / 08
                            </span>
                            <input id="no_hp" name="no_hp" type="tel"
                                value="{{ old('no_hp', $konseli->no_hp ?? '') }}" placeholder="08123456789"
                                class="flex-1 px-4 py-3 text-sm outline-none bg-white" />
                        </div>
                        @error('no_hp')
                            <p class="mt-2 text-xs font-medium text-red-500">{{ $message }}</p>
                        @enderror
                        <p class="mt-2 text-xs text-slate-400">
                            Nomor ini digunakan konselor untuk menghubungimu via WhatsApp mengenai jadwal dan sesi
                            konseling.
                        </p>
                    </div>

                    {{-- Keluhan --}}
                    <div>
                        <label for="keluhan" class="mb-2 block text-sm font-semibold text-kc-heading">
                            Keluhan / Permasalahan
                            <span class="text-red-400">*</span>
                        </label>
                        <textarea id="keluhan" name="keluhan" rows="5"
                            class="w-full rounded-lg border px-4 py-3 text-sm outline-none transition focus:border-indigo-400 focus:ring-4 focus:ring-indigo-100 {{ $errors->has('keluhan') ? 'border-red-300' : 'border-slate-200' }}"
                            placeholder="Contoh: Akhir-akhir ini saya mudah cemas, sulit tidur, dan sulit fokus.">{{ old('keluhan') }}</textarea>
                        @error('keluhan')
                            <p class="mt-2 text-xs font-medium text-red-500">{{ $message }}</p>
                        @enderror
                        <p class="mt-2 text-xs text-slate-400">
                            Cukup ceritakan garis besarnya. Kamu tetap bisa menjelaskan lebih detail saat sesi.
                        </p>
                    </div>

                    <div class="flex flex-wrap gap-3 pt-2">
                        <button type="submit"
                            class="rounded-lg bg-indigo-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-indigo-700">
                            Simpan Assessment
                        </button>
                        <a href="{{ route('konseli.dashboard') }}"
                            class="rounded-lg border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
                            Batal / Kembali
                        </a>
                    </div>
                </form>
            </x-dashboard.form-card>

            <aside class="space-y-6">
                <article class="kc-card p-6">
                    <h2 class="text-lg font-semibold text-kc-heading">Assessment Terakhir</h2>

                    @if ($lastAssessment)
                        <div class="mt-5 space-y-4 text-sm">
                            <div>
                                <p class="text-slate-400">Tanggal</p>
                                <p class="mt-1 font-semibold text-kc-heading">
                                    {{ \Illuminate\Support\Carbon::parse($lastAssessment->created_at)->translatedFormat('d M Y') }}
                                </p>
                            </div>
                            <div>
                                <p class="text-slate-400">Keluhan</p>
                                <p class="mt-1 leading-6">{{ $lastAssessment->keluhan }}</p>
                            </div>
                        </div>
                    @else
                        <p class="mt-5 text-sm text-slate-400">Belum ada assessment yang tersimpan.</p>
                    @endif
                </article>

                <article class="kc-card p-6">
                    <h2 class="text-lg font-semibold text-kc-heading">Langkah Berikutnya</h2>
                    <ol class="mt-5 space-y-4 text-sm text-slate-500">
                        <li class="flex gap-3">
                            <span class="font-bold text-indigo-500">1</span>
                            Isi nomor WhatsApp dan keluhan, lalu simpan assessment.
                        </li>
                        <li class="flex gap-3">
                            <span class="font-bold text-indigo-500">2</span>
                            Ajukan konseling dengan memilih konselor dan jadwal.
                        </li>
                        <li class="flex gap-3">
                            <span class="font-bold text-indigo-500">3</span>
                            Konselor akan menghubungimu via WhatsApp untuk konfirmasi jadwal.
                        </li>
                    </ol>
                </article>

                @if ($konseli->no_hp)
                    <article class="kc-card p-6">
                        <h2 class="text-sm font-semibold text-kc-heading">Nomor WhatsApp Tersimpan</h2>
                        <p class="mt-2 text-sm text-slate-500">{{ $konseli->no_hp }}</p>
                        <p class="mt-1 text-xs text-slate-400">Mengisi form di atas akan memperbarui nomor ini.</p>
                    </article>
                @endif
            </aside>
        </div>
    </section>
</x-app-layout>
