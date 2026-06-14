<x-app-layout>
    <x-slot name="dashboardRole">admin</x-slot>
    <x-slot name="headerTitle">Kelola Pengguna</x-slot>

    @php
        $formatDate = fn ($value) => \Illuminate\Support\Carbon::parse($value)->translatedFormat('d M Y');
    @endphp

    <section class="space-y-6">
        <x-dashboard.page-header
            title="Kelola Pengguna"
            subtitle="Lihat, edit, dan hapus data pengguna Kawan Cerito."
        />

        @if (session('success'))
            <div class="rounded-2xl border border-emerald-100 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-600">
                {{ session('success') }}
            </div>
        @endif

        @if (session('warning'))
            <div class="rounded-2xl border border-amber-100 bg-amber-50 px-5 py-4 text-sm font-medium text-amber-700">
                {{ session('warning') }}
            </div>
        @endif

        <article class="kc-card p-6">
            <form method="GET" action="{{ route('admin.users.index') }}" class="grid gap-3 md:grid-cols-[1fr_220px_auto_auto]">
                <input
                    type="search"
                    name="search"
                    value="{{ request('search') }}"
                    class="rounded-lg border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-indigo-400 focus:ring-4 focus:ring-indigo-100"
                    placeholder="Cari nama atau email pengguna"
                >
                <select
                    name="role"
                    onchange="this.form.submit()"
                    class="rounded-lg border border-slate-200 px-4 py-3 text-sm capitalize outline-none transition focus:border-indigo-400 focus:ring-4 focus:ring-indigo-100"
                >
                    <option value="">Semua Role</option>
                    <option value="admin" @selected(request('role') === 'admin')>Admin</option>
                    <option value="konselor" @selected(request('role') === 'konselor')>Konselor</option>
                    <option value="konseli" @selected(request('role') === 'konseli')>Konseli</option>
                </select>
                <button
                    type="submit"
                    class="rounded-lg bg-indigo-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-indigo-700"
                >
                    Terapkan
                </button>
                @if (request()->hasAny(['search', 'role']))
                    <a
                        href="{{ route('admin.users.index') }}"
                        class="inline-flex items-center justify-center rounded-lg border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-500 transition hover:bg-slate-50"
                    >
                        Reset
                    </a>
                @endif
            </form>
        </article>

        <article class="kc-card">
            <div class="border-b border-slate-100 px-6 py-5">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <h2 class="text-lg font-semibold text-kc-heading">Daftar Pengguna</h2>
                        <p class="mt-1 text-sm text-slate-400">
                            Menampilkan {{ $users->count() }} pengguna
                            @if (request('role'))
                                dengan role {{ ucfirst(request('role')) }}
                            @endif
                        </p>
                    </div>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[760px] text-left text-sm">
                    <thead class="text-xs uppercase tracking-wide text-slate-400">
                        <tr>
                            <th class="px-6 py-4 font-semibold">Nama</th>
                            <th class="px-6 py-4 font-semibold">Email</th>
                            <th class="px-6 py-4 font-semibold">Role</th>
                            <th class="px-6 py-4 font-semibold">Bergabung</th>
                            <th class="px-6 py-4 font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($users as $user)
                            <tr>
                                <td class="px-6 py-4 font-semibold text-kc-heading">{{ $user['nama'] }}</td>
                                <td class="px-6 py-4 text-slate-500">{{ $user['email'] }}</td>
                                <td class="px-6 py-4 capitalize text-slate-500">{{ $user['role'] }}</td>
                                <td class="px-6 py-4 text-slate-500">{{ $formatDate($user['created_at']) }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-2">
                                        <a
                                            href="{{ route('admin.users.edit', $user['id_user']) }}"
                                            class="inline-flex whitespace-nowrap rounded-md border border-indigo-200 px-3 py-1.5 text-xs font-semibold text-indigo-600 transition hover:bg-indigo-50"
                                        >
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('admin.users.destroy', $user['id_user']) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                type="submit"
                                                onclick="return confirm('Hapus data pengguna ini?')"
                                                class="inline-flex whitespace-nowrap rounded-md border border-red-100 bg-red-50 px-3 py-1.5 text-xs font-semibold text-red-600 transition hover:bg-red-100"
                                            >
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-slate-400">
                                    Tidak ada pengguna yang sesuai dengan filter.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </article>
    </section>
</x-app-layout>
