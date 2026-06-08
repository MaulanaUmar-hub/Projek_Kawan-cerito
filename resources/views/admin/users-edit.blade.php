<x-app-layout>
    <x-slot name="dashboardRole">admin</x-slot>
    <x-slot name="headerTitle">Edit Pengguna</x-slot>

    <section class="space-y-6">
        <x-dashboard.page-header
            title="Edit Pengguna"
            subtitle="Perbarui data dasar pengguna tanpa mengubah informasi sensitif seperti password."
        />

        <div class="grid gap-6 xl:grid-cols-[1fr_360px]">
            <article class="kc-card p-6">
                <form method="POST" action="{{ route('admin.users.update', $user->id_user) }}" class="space-y-5">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label for="nama" class="mb-2 block text-sm font-semibold text-kc-heading">Nama</label>
                        <input
                            id="nama"
                            name="nama"
                            type="text"
                            value="{{ old('nama', $user->nama) }}"
                            class="w-full rounded-lg border px-4 py-3 text-sm outline-none transition focus:ring-4 {{ $errors->has('nama') ? 'border-red-300 focus:border-red-400 focus:ring-red-100' : 'border-slate-200 focus:border-indigo-400 focus:ring-indigo-100' }}"
                        >
                        @error('nama')
                            <p class="mt-2 text-sm font-medium text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="mb-2 block text-sm font-semibold text-kc-heading">Email</label>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            value="{{ old('email', $user->email) }}"
                            class="w-full rounded-lg border px-4 py-3 text-sm outline-none transition focus:ring-4 {{ $errors->has('email') ? 'border-red-300 focus:border-red-400 focus:ring-red-100' : 'border-slate-200 focus:border-indigo-400 focus:ring-indigo-100' }}"
                        >
                        @error('email')
                            <p class="mt-2 text-sm font-medium text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="role" class="mb-2 block text-sm font-semibold text-kc-heading">Role</label>
                        <select
                            id="role"
                            name="role"
                            class="w-full rounded-lg border px-4 py-3 text-sm capitalize outline-none transition focus:ring-4 {{ $errors->has('role') ? 'border-red-300 focus:border-red-400 focus:ring-red-100' : 'border-slate-200 focus:border-indigo-400 focus:ring-indigo-100' }}"
                        >
                            @foreach (['admin', 'konseli', 'konselor'] as $role)
                                <option value="{{ $role }}" @selected(old('role', $user->role) === $role)>
                                    {{ ucfirst($role) }}
                                </option>
                            @endforeach
                        </select>
                        @error('role')
                            <p class="mt-2 text-sm font-medium text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex flex-col gap-3 pt-2 sm:flex-row sm:justify-end">
                        <a
                            href="{{ route('admin.users.index') }}"
                            class="inline-flex justify-center rounded-lg border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-500 transition hover:bg-slate-50"
                        >
                            Batal
                        </a>
                        <button
                            type="submit"
                            class="inline-flex justify-center rounded-lg bg-indigo-500 px-5 py-3 text-sm font-semibold text-white shadow-sm shadow-indigo-100 transition hover:bg-indigo-600"
                        >
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </article>

            <aside class="kc-card p-6">
                <h2 class="text-lg font-semibold text-kc-heading">Ringkasan Akun</h2>
                <dl class="mt-5 space-y-4 text-sm">
                    <div>
                        <dt class="text-slate-400">Nama</dt>
                        <dd class="mt-1 font-semibold text-kc-heading">{{ $user->nama }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-400">Email</dt>
                        <dd class="mt-1 font-semibold text-kc-heading">{{ $user->email }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-400">Role Saat Ini</dt>
                        <dd class="mt-1 font-semibold capitalize text-kc-heading">{{ $user->role }}</dd>
                    </div>
                </dl>
            </aside>
        </div>
    </section>
</x-app-layout>
