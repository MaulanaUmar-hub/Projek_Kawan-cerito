@extends('layouts.guest')

@section('content')
    <form method="POST" action="{{ route('register') }}">

        @csrf

        <div class="grid md:grid-cols-2 gap-6">

            {{-- NAMA --}}
            <div class="md:col-span-2">

                <label class="block mb-3 font-semibold text-[#111827]">
                    Nama Lengkap
                </label>

                <input type="text" name="name"
                    class="w-full rounded-2xl border border-gray-200 px-5 py-4 outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all duration-300">
            </div>

            {{-- INSTANSI --}}
            <div class="md:col-span-2">

                <label class="block mb-3 font-semibold text-[#111827]">
                    Asal Instansi/Sekolah
                </label>

                <input type="text" name="instansi"
                    class="w-full rounded-2xl border border-gray-200 px-5 py-4 outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all duration-300">
            </div>

            {{-- EMAIL --}}
            <div>

                <label class="block mb-3 font-semibold text-[#111827]">
                    Email
                </label>

                <input type="email" name="email"
                    class="w-full rounded-2xl border border-gray-200 px-5 py-4 outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all duration-300">
            </div>

            {{-- NOMOR HP --}}
            <div>

                <label class="block mb-3 font-semibold text-[#111827]">
                    Nomor HP
                </label>

                <input type="text" name="phone"
                    class="w-full rounded-2xl border border-gray-200 px-5 py-4 outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all duration-300">
            </div>

            {{-- JENIS KELAMIN --}}
            <div>

                <label class="block mb-3 font-semibold text-[#111827]">
                    Jenis Kelamin
                </label>

                <div class="flex gap-6 mt-2">

                    <label class="flex items-center gap-2">
                        <input type="radio" name="gender" value="L">
                        <span>Laki-laki</span>
                    </label>

                    <label class="flex items-center gap-2">
                        <input type="radio" name="gender" value="P">
                        <span>Perempuan</span>
                    </label>

                </div>
            </div>

            {{-- ROLE --}}
            <div>

                <label class="block mb-3 font-semibold text-[#111827]">
                    Daftar Sebagai
                </label>

                <select name="role"
                    class="w-full rounded-2xl border border-gray-200 px-5 py-4 outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all duration-300">
                    <option value="konseli">Konseli</option>
                    <option value="konselor">Konselor</option>
                </select>
            </div>

            {{-- PASSWORD --}}
            <div>

                <label class="block mb-3 font-semibold text-[#111827]">
                    Password
                </label>

                <input type="password" name="password"
                    class="w-full rounded-2xl border border-gray-200 px-5 py-4 outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all duration-300">
            </div>

            {{-- CONFIRM --}}
            <div>

                <label class="block mb-3 font-semibold text-[#111827]">
                    Konfirmasi Password
                </label>

                <input type="password" name="password_confirmation"
                    class="w-full rounded-2xl border border-gray-200 px-5 py-4 outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all duration-300">
            </div>

        </div>

        {{-- FOOTER --}}
        <div class="flex flex-col md:flex-row items-center justify-between gap-5 mt-10">

            <a href="{{ route('login') }}" class="text-gray-500 hover:text-indigo-500 transition-all duration-300">
                Sudah punya akun?
            </a>

            <button type="submit"
                class="rounded-2xl px-10 py-4 text-white font-bold bg-[#1E293B] hover:bg-[#0F172A] transition-all duration-300">
                DAFTAR
            </button>
        </div>

    </form>
@endsection
