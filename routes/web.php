<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\KonseliController;
use App\Http\Controllers\KonselorController;
use App\Http\Controllers\Auth\KonselorRegisterController;
use Illuminate\Support\Facades\Route;

Route::get('/up', fn () => response()->json([
    'status' => 'ok',
    'app' => config('app.name'),
]));

Route::get('/', function () {
    return view('landing.index');
});

Route::get('/dashboard', function () {
    $user = auth()->user();
    if ($user->role === 'konselor') {
        $konselor = \App\Models\Konselor::where('id_user', $user->id_user)->first();
        if ($konselor && $konselor->status === 'pending') return redirect()->route('konselor.pending');
        if ($konselor && $konselor->status === 'ditolak') return redirect()->route('login')->withErrors(['email' => 'Pendaftaran Anda telah ditolak oleh admin.']);
    }
    return redirect(match ($user->role) {
        'admin'    => route('admin.dashboard'),
        'konselor' => route('konselor.dashboard'),
        default    => route('konseli.dashboard'),
    });
})->middleware(['auth'])->name('dashboard');

Route::middleware('guest')->group(function () {
    Route::get('/register/konselor', [KonselorRegisterController::class, 'create'])->name('register.konselor');
    Route::post('/register/konselor', [KonselorRegisterController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Profile setup khusus konseli — pakai role:konseli tapi TANPA konseli.profile.complete
// supaya konseli yang belum lengkap profilnya tetap bisa akses halaman ini
Route::middleware(['auth', 'role:konseli'])->group(function () {
    Route::get('/konseli/profile/setup', [KonseliController::class, 'setupProfile'])->name('konseli.profile.setup');
    Route::post('/konseli/profile/setup', [KonseliController::class, 'storeProfileSetup'])->name('konseli.profile.setup.store');
});

// Admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/approval-konselor', [AdminController::class, 'approvalKonselor'])->name('admin.approval-konselor.index');
    Route::get('/konselor/{id}', [AdminController::class, 'showKonselor'])->name('admin.konselor.show');
    Route::post('/konselor/{id}/approve', [AdminController::class, 'approveKonselor'])->name('admin.konselor.approve');
    Route::post('/konselor/{id}/reject', [AdminController::class, 'rejectKonselor'])->name('admin.konselor.reject');
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users.index');
    Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])->name('admin.users.edit');
    Route::patch('/users/{user}', [AdminController::class, 'updateUser'])->name('admin.users.update');
    Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('admin.users.destroy');
    Route::get('/konselor', [AdminController::class, 'konselorList'])->name('admin.konselor.index');
    Route::get('/activity-log', [AdminController::class, 'activityLog'])->name('admin.activity-log.index');
});

// Konseli
Route::middleware(['auth', 'role:konseli', 'konseli.profile.complete'])->prefix('konseli')->group(function () {
    Route::get('/dashboard', [KonseliController::class, 'index'])->name('konseli.dashboard');
    Route::get('/assessment', [KonseliController::class, 'assessment'])->name('konseli.assessment');
    Route::post('/assessment', [KonseliController::class, 'storeAssessment'])->name('konseli.assessment.store');
    Route::get('/pengajuan', [KonseliController::class, 'pengajuan'])->name('konseli.pengajuan');
    Route::post('/pengajuan', [KonseliController::class, 'storePengajuan'])->name('konseli.pengajuan.store');
    Route::post('/pengajuan/{id}/reschedule/konfirmasi', [KonseliController::class, 'konfirmasiReschedule'])->name('konseli.reschedule.konfirmasi');
    Route::get('/riwayat', [KonseliController::class, 'riwayat'])->name('konseli.riwayat');
    Route::get('/jadwal', [KonseliController::class, 'jadwal'])->name('konseli.jadwal');
    Route::get('/profil', [KonseliController::class, 'profil'])->name('konseli.profil');
});

// Konselor
Route::middleware(['auth', 'role:konselor'])->prefix('konselor')->group(function () {
    Route::get('/pending', [KonselorController::class, 'pending'])->name('konselor.pending');
    Route::get('/dashboard', [KonselorController::class, 'index'])->name('konselor.dashboard');
    Route::get('/pengajuan', [KonselorController::class, 'pengajuan'])->name('konselor.pengajuan');
    Route::post('/pengajuan/{id}/setujui', [KonselorController::class, 'setujuiPengajuan'])->name('konselor.pengajuan.setujui');
    Route::post('/pengajuan/{id}/tolak', [KonselorController::class, 'tolakPengajuan'])->name('konselor.pengajuan.tolak');
    Route::post('/pengajuan/{id}/reschedule', [KonselorController::class, 'reschedulePengajuan'])->name('konselor.pengajuan.reschedule');
    Route::post('/pengajuan/{id}/selesai', [KonselorController::class, 'selesaiKonseling'])->name('konselor.pengajuan.selesai');
    Route::get('/jadwal', [KonselorController::class, 'jadwal'])->name('konselor.jadwal');
    Route::get('/riwayat', [KonselorController::class, 'riwayat'])->name('konselor.riwayat');
    Route::get('/profil', [KonselorController::class, 'profil'])->name('konselor.profil');
    Route::patch('/profil', [KonselorController::class, 'updateProfil'])->name('konselor.profil.update');
});

require __DIR__ . '/auth.php';
