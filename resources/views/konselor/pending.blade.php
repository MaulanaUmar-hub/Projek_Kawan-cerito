@extends('layouts.guest')

@section('content')
    <div class="space-y-6 text-center">

        <div class="flex justify-center">
            <div class="flex h-20 w-20 items-center justify-center rounded-2xl bg-amber-50">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-amber-500" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M12 6v6l4 2m6-2a10 10 0 11-20 0 10 10 0 0120 0z" />
                </svg>
            </div>
        </div>

        <div>
            <h1 class="text-2xl font-bold text-[#111827]">Pendaftaran Sedang Ditinjau</h1>
            <p class="mt-3 text-sm leading-6 text-slate-500">
                Terima kasih telah mendaftar sebagai konselor di Kawan Cerito.<br>
                Akun Anda sedang menunggu persetujuan dari admin.
            </p>
        </div>

        <div class="rounded-2xl border border-amber-100 bg-amber-50 px-5 py-4 text-sm text-amber-700">
            Proses peninjauan biasanya memerlukan 1&times;24 jam. Anda akan dapat login setelah akun disetujui.
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="w-full rounded-2xl bg-gradient-to-r from-[#5B67F1] to-[#7482FF] px-10 py-4 font-bold text-white shadow-lg shadow-indigo-100 transition-all duration-300 hover:scale-[1.01]">
                Kembali ke Login
            </button>
        </form>

    </div>
@endsection
