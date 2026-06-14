<x-app-layout>
    <x-slot name="dashboardRole">admin</x-slot>
    <x-slot name="headerTitle">Activity Log</x-slot>

    @php
        $formatDateTime = fn($value) => \Illuminate\Support\Carbon::parse($value)->translatedFormat('d M Y H:i');
    @endphp

    <section class="space-y-6">
        <x-dashboard.page-header title="Activity Log"
            subtitle="Pantau aktivitas penting yang terjadi di sistem Kawan Cerito." />

        {{-- Filter --}}
        <article class="kc-card p-6">
            <form method="GET" action="{{ route('admin.activity-log.index') }}" class="grid gap-3 xl:grid-cols-[1fr_220px_1fr_220px_auto_auto]">
                <input
                    type="search"
                    name="user"
                    value="{{ request('user') }}"
                    class="rounded-lg border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-indigo-400 focus:ring-4 focus:ring-indigo-100"
                    placeholder="Cari nama atau email user..."
                >
                <select
                    name="role"
                    onchange="this.form.submit()"
                    class="rounded-lg border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-indigo-400 focus:ring-4 focus:ring-indigo-100"
                >
                        <option value="">Semua Role</option>
                        <option value="admin" @selected(request('role') === 'admin')>Admin</option>
                        <option value="konselor" @selected(request('role') === 'konselor')>Konselor</option>
                        <option value="konseli" @selected(request('role') === 'konseli')>Konseli</option>
                </select>
                <input
                    type="search"
                    name="aktivitas"
                    value="{{ request('aktivitas') }}"
                    class="rounded-lg border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-indigo-400 focus:ring-4 focus:ring-indigo-100"
                    placeholder="Cari aktivitas..."
                >
                <input
                    type="date"
                    name="tanggal"
                    value="{{ request('tanggal') }}"
                    onchange="this.form.submit()"
                    class="rounded-lg border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-indigo-400 focus:ring-4 focus:ring-indigo-100"
                >
                <button
                    type="submit"
                    class="rounded-lg bg-indigo-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-indigo-700"
                >
                    Terapkan
                </button>
                @if (request()->hasAny(['user', 'role', 'aktivitas', 'tanggal']))
                    <a
                        href="{{ route('admin.activity-log.index') }}"
                        class="rounded-lg border border-slate-200 px-5 py-3 text-center text-sm font-semibold text-slate-600 transition hover:bg-slate-50"
                    >
                        Reset
                    </a>
                @endif
            </form>

            @if (request()->hasAny(['user', 'role', 'aktivitas', 'tanggal']))
                <p class="mt-4 text-xs text-slate-400">
                    Filter aktif:
                    @if (request('user'))
                        user "{{ request('user') }}"
                    @endif
                    @if (request('role'))
                        role {{ ucfirst(request('role')) }}
                    @endif
                    @if (request('aktivitas'))
                        aktivitas "{{ request('aktivitas') }}"
                    @endif
                    @if (request('tanggal'))
                        tanggal {{ \Illuminate\Support\Carbon::parse(request('tanggal'))->translatedFormat('d M Y') }}
                    @endif
                </p>
            @endif
        </article>

        <article class="kc-card">
            <div class="border-b border-slate-100 px-6 py-5 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-kc-heading">Log Terbaru</h2>
                <span class="text-sm text-slate-400">{{ $activityLogs->total() }} entri</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[760px] text-left text-sm">
                    <thead class="text-xs uppercase tracking-wide text-slate-400">
                        <tr>
                            <th class="px-6 py-4 font-semibold">Waktu</th>
                            <th class="px-6 py-4 font-semibold">User</th>
                            <th class="px-6 py-4 font-semibold">Role</th>
                            <th class="px-6 py-4 font-semibold">Aktivitas</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($activityLogs as $log)
                            <tr>
                                <td class="px-6 py-4 text-slate-500 whitespace-nowrap">
                                    {{ $formatDateTime($log['waktu']) }}</td>
                                <td class="px-6 py-4 font-semibold text-kc-heading">{{ $log['user'] }}</td>
                                <td class="px-6 py-4 capitalize text-slate-500">
                                    <span @class([
                                        'inline-block rounded-full px-2 py-0.5 text-xs font-medium',
                                        'bg-purple-100 text-purple-700' => $log['role'] === 'admin',
                                        'bg-blue-100 text-blue-700' => $log['role'] === 'konselor',
                                        'bg-green-100 text-green-700' => $log['role'] === 'konseli',
                                        'bg-slate-100 text-slate-500' => !in_array($log['role'], [
                                            'admin',
                                            'konselor',
                                            'konseli',
                                        ]),
                                    ])>{{ $log['role'] }}</span>
                                </td>
                                <td class="px-6 py-4 text-slate-500">{{ $log['aktivitas'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-slate-400">
                                    Belum ada aktivitas yang tercatat.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($activityLogs->hasPages())
                <div class="border-t border-slate-100 px-6 py-4">
                    {{ $activityLogs->links() }}
                </div>
            @endif
        </article>
    </section>
</x-app-layout>
