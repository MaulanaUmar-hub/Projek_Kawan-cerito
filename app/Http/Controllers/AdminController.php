<?php

namespace App\Http\Controllers;

use App\Models\Konselor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    public function index()
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
                ['label' => 'Total Pengguna',    'value' => User::count(),                                 'icon' => 'U', 'color' => 'primary'],
                ['label' => 'Konselor Aktif',    'value' => Konselor::where('status', 'aktif')->count(),   'icon' => 'K', 'color' => 'success'],
                ['label' => 'Pengajuan Pending', 'value' => Konselor::where('status', 'pending')->count(), 'icon' => 'P', 'color' => 'warning'],
                ['label' => 'Konselor Ditolak',  'value' => Konselor::where('status', 'ditolak')->count(), 'icon' => 'D', 'color' => 'danger'],
            ],
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
        return view('admin.konselor-detail', compact('konselor'));
    }

    public function users()
    {
        $users = User::latest()->get();
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

    public function jadwal()
    {
        $jadwal = collect([]);
        return view('admin.jadwal', compact('jadwal'));
    }

    public function activityLog()
    {
        $activityLogs = collect([
            ['waktu' => now(), 'user' => 'Sistem', 'role' => 'system', 'aktivitas' => 'Tidak ada aktivitas terbaru.'],
        ]);
        return view('admin.activity-log', compact('activityLogs'));
    }
}
