<?php

namespace App\Http\Controllers;

use App\Models\Konselor;
use App\Models\PengajuanKonseling;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $periodeMulai = $request->date('mulai')
            ? Carbon::parse($request->date('mulai'))->startOfDay()
            : now()->startOfMonth();
        $periodeSelesai = $request->date('selesai')
            ? Carbon::parse($request->date('selesai'))->endOfDay()
            : now()->endOfDay();

        if ($periodeMulai->gt($periodeSelesai)) {
            [$periodeMulai, $periodeSelesai] = [$periodeSelesai->copy()->startOfDay(), $periodeMulai->copy()->endOfDay()];
        }

        $konselingPeriodeQuery = PengajuanKonseling::query()
            ->whereBetween('created_at', [$periodeMulai, $periodeSelesai]);

        $konselingPeriode = [
            'mulai'       => $periodeMulai,
            'akhir'       => $periodeSelesai,
            'total'       => (clone $konselingPeriodeQuery)->count(),
            'konseli'     => (clone $konselingPeriodeQuery)->distinct('id_konseli')->count('id_konseli'),
            'sesi_selesai' => (clone $konselingPeriodeQuery)->where('status_pengajuan', 'selesai')->count(),
            'berlangsung' => (clone $konselingPeriodeQuery)->whereIn('status_pengajuan', ['disetujui', 'berlangsung'])->count(),
        ];

        $konselorPending = Konselor::with('user')
            ->where('status', 'pending')
            ->latest()
            ->get()
            ->map(fn($k) => [
                'id'      => $k->id_konselor,
                'nama'    => $k->user->nama,
                'email'   => $k->user->email,
                'asal'    => $k->spesialisasi ?? '-',
                'no_hp'   => $k->no_hp ?? '-',
                'tanggal' => $k->created_at,
                'status'  => 'pending',
            ]);

        $konselorAktif = Konselor::with('user')
            ->where('status', 'aktif')
            ->latest()
            ->get()
            ->map(fn($k) => [
                'id'           => $k->id_konselor,
                'nama'         => $k->user->nama,
                'email'        => $k->user->email,
                'spesialisasi' => $k->spesialisasi ?? '-',
                'status'       => 'aktif',
            ]);

        $konselorDitolak = Konselor::with('user')
            ->where('status', 'ditolak')
            ->latest()
            ->get()
            ->map(fn($k) => [
                'id'      => $k->id_konselor,
                'nama'    => $k->user->nama,
                'email'   => $k->user->email,
                'tanggal' => $k->created_at,
                'status'  => 'ditolak',
            ]);

        return view('admin.dashboard', [
            'stats' => [
                ['label' => 'Total Pengguna',    'value' => User::count(),                                 'icon' => 'bi bi-people-fill', 'color' => 'primary'],
                ['label' => 'Konselor Aktif',    'value' => Konselor::where('status', 'aktif')->count(),   'icon' => 'bi bi-patch-check-fill', 'color' => 'success'],
                ['label' => 'Pengajuan Menunggu', 'value' => Konselor::where('status', 'pending')->count(), 'icon' => 'bi bi-hourglass-split', 'color' => 'warning'],
                ['label' => 'Konselor Ditolak',  'value' => Konselor::where('status', 'ditolak')->count(), 'icon' => 'bi bi-x-circle-fill', 'color' => 'danger'],
            ],
            'konselingPeriode' => $konselingPeriode,
            'konselorPending'  => $konselorPending,
            'konselorAktif'    => $konselorAktif,
            'konselorDitolak'  => $konselorDitolak,
            'activityLogs'     => collect([
                ['aktivitas' => 'Konselor baru mendaftar', 'user' => 'Sistem', 'role' => 'system'],
            ]),
            'jadwal' => collect([]),
        ]);
    }

    public function approvalKonselor()
    {
        $konselorPending = Konselor::with('user')
            ->where('status', 'pending')
            ->latest()
            ->get()
            ->map(fn($k) => [
                'id'      => $k->id_konselor,
                'nama'    => $k->user->nama,
                'email'   => $k->user->email,
                'asal'    => $k->spesialisasi ?? '-',
                'no_hp'   => $k->no_hp ?? '-',
                'tanggal' => $k->created_at,
                'status'  => 'pending',
            ]);

        return view('admin.approval-konselor', compact('konselorPending'));
    }

    public function approveKonselor($id)
    {
        $konselor = Konselor::findOrFail($id);
        $konselor->update(['status' => 'aktif']);

        return back()->with('success', "Konselor {$konselor->user->nama} berhasil disetujui.");
    }

    public function rejectKonselor($id)
    {
        $konselor = Konselor::findOrFail($id);
        $konselor->update(['status' => 'ditolak']);

        return back()->with('success', "Konselor {$konselor->user->nama} telah ditolak.");
    }

    public function showKonselor($id)
    {
        $konselor = Konselor::with('user')->findOrFail($id);
        $pengajuanKonseling = PengajuanKonseling::where('id_konselor', $konselor->id_konselor);

        $statistikSesi = [
            'total' => (clone $pengajuanKonseling)->count(),
            'berjalan' => (clone $pengajuanKonseling)
                ->whereIn('status_pengajuan', ['disetujui', 'berlangsung', 'aktif'])
                ->count(),
            'selesai' => (clone $pengajuanKonseling)
                ->where('status_pengajuan', 'selesai')
                ->count(),
        ];

        return view('admin.konselor-detail', compact('konselor', 'statistikSesi'));
    }

    public function users(Request $request)
    {
        $users = User::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;

                $query->where(function ($q) use ($search) {
                    $q->where('nama', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%');
                });
            })
            ->when(
                in_array($request->role, ['admin', 'konseli', 'konselor'], true),
                fn($query) => $query->where('role', $request->role)
            )
            ->latest()
            ->get();

        return view('admin.users', compact('users'));
    }

    public function editUser(User $user)
    {
        return view('admin.users-edit', compact('user'));
    }

    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id_user, 'id_user'),
            ],
            'role' => ['required', Rule::in(['admin', 'konseli', 'konselor'])],
        ], [
            'nama.required' => 'Nama pengguna wajib diisi.',
            'email.required' => 'Email pengguna wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan pengguna lain.',
            'role.required' => 'Role pengguna wajib dipilih.',
        ]);

        $user->update($validated);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroyUser(User $user)
    {
        if (auth()->id() === $user->id_user) {
            return back()->with('warning', 'Akun yang sedang digunakan tidak dapat dihapus dari halaman ini.');
        }

        $user->delete();

        return back()->with('success', 'Data pengguna berhasil dihapus.');
    }

    public function konselorList()
    {
        $semua = Konselor::with('user')->latest()->get()
            ->map(fn($k) => [
                'id'           => $k->id_konselor,
                'nama'         => $k->user->nama,
                'email'        => $k->user->email,
                'spesialisasi' => $k->spesialisasi ?? '-',
                'no_hp'        => $k->no_hp ?? '-',
                'status'       => $k->status,
            ]);

        $konselorPending  = $semua->where('status', 'pending')->values();
        $konselorAktif    = $semua->where('status', 'aktif')->values();
        $konselorDitolak  = $semua->where('status', 'ditolak')->values();

        return view('admin.konselor', compact('konselorPending', 'konselorAktif', 'konselorDitolak'));
    }

    public function activityLog(Request $request)
    {
        $role = strtolower((string) $request->input('role'));

        $query = \App\Models\ActivityLog::with('user')
            ->latest('created_at');

        // Filter: nama user
        if ($request->filled('user')) {
            $userSearch = $request->user;

            $query->whereHas(
                'user',
                fn($q) => $q->where(function ($userQuery) use ($userSearch) {
                    $userQuery->where('nama', 'like', '%' . $userSearch . '%')
                        ->orWhere('email', 'like', '%' . $userSearch . '%');
                })
            );
        }

        // Filter: role
        if (in_array($role, ['admin', 'konseli', 'konselor'], true)) {
            $query->whereHas(
                'user',
                fn($q) => $q->where('role', $role)
            );
        }

        // Filter: kata kunci aktivitas
        if ($request->filled('aktivitas')) {
            $query->where('aktivitas', 'like', '%' . $request->aktivitas . '%');
        }

        // Filter: tanggal
        if ($request->filled('tanggal')) {
            $query->whereDate('created_at', $request->tanggal);
        }

        $activityLogs = $query->paginate(25)->withQueryString()
            ->through(fn($log) => [
                'waktu'     => $log->created_at,
                'user'      => $log->user?->nama ?? '(User dihapus)',
                'role'      => $log->user?->role ?? '-',
                'aktivitas' => $log->aktivitas,
            ]);

        return view('admin.activity-log', compact('activityLogs'));
    }
}
