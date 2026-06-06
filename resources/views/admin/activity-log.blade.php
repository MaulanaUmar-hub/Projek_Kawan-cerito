<x-app-layout>
    <x-slot name="dashboardRole">admin</x-slot>
    <x-slot name="headerTitle">Activity Log</x-slot>

    @php
        $formatDateTime = fn ($value) => \Illuminate\Support\Carbon::parse($value)->translatedFormat('d M Y H:i');
    @endphp

    <section class="space-y-6">
        <x-dashboard.page-header
            title="Activity Log"
            subtitle="Pantau aktivitas penting yang terjadi di sistem Kawan Cerito."
        />

        <article class="kc-card p-6">
            <div class="grid gap-3 md:grid-cols-4">
                <input type="text" class="rounded-lg border border-slate-200 px-4 py-3 text-sm" placeholder="User">
                <select class="rounded-lg border border-slate-200 px-4 py-3 text-sm">
                    <option>Semua Role</option>
                    <option>Admin</option>
                    <option>Konselor</option>
                    <option>Konseli</option>
                </select>
                <input type="text" class="rounded-lg border border-slate-200 px-4 py-3 text-sm" placeholder="Aktivitas">
                <input type="date" class="rounded-lg border border-slate-200 px-4 py-3 text-sm">
            </div>
        </article>

        <article class="kc-card">
            <div class="border-b border-slate-100 px-6 py-5">
                <h2 class="text-lg font-semibold text-kc-heading">Log Terbaru</h2>
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
                        @foreach ($activityLogs as $log)
                            <tr>
                                <td class="px-6 py-4 text-slate-500">{{ $formatDateTime($log['waktu']) }}</td>
                                <td class="px-6 py-4 font-semibold text-kc-heading">{{ $log['user'] }}</td>
                                <td class="px-6 py-4 capitalize text-slate-500">{{ $log['role'] }}</td>
                                <td class="px-6 py-4 text-slate-500">{{ $log['aktivitas'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </article>
    </section>
</x-app-layout>
