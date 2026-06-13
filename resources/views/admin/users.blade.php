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
            <div class="grid gap-3 md:grid-cols-[1fr_220px]">
                <input type="search" class="rounded-lg border border-slate-200 px-4 py-3 text-sm" placeholder="Cari nama atau email pengguna">
                <select class="rounded-lg border border-slate-200 px-4 py-3 text-sm">
                    <option>Semua Role</option>
                    <option>Admin</option>
                    <option>Konselor</option>
                    <option>Konseli</option>
                </select>
            </div>
        </article>

        <article class="kc-card">
            <div class="border-b border-slate-100 px-6 py-5">
                <h2 class="text-lg font-semibold text-kc-heading">Daftar Pengguna</h2>
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
                        @foreach ($users as $user)
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
                        @endforeach
                    </tbody>
                </table>
            </div>
        </article>
    </section>
</x-app-layout>
