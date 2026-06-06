<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\KonseliController;
use App\Http\Controllers\KonselorController;
use App\Http\Controllers\Auth\KonselorRegisterController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing.index');
});

Route::get('/dashboard', function () {
    $user = auth()->user();
    $role = $user->role;

    if ($role === 'konselor') {
        $konselor = \App\Models\Konselor::where('id_user', $user->id_user)->first();
        if ($konselor && $konselor->status === 'pending') {
            return redirect()->route('konselor.pending');
        }
        if ($konselor && $konselor->status === 'ditolak') {
            return redirect()->route('login')
                ->withErrors(['email' => 'Pendaftaran Anda telah ditolak oleh admin.']);
        }
    }

    return redirect(match ($role) {
        'admin'    => route('admin.dashboard'),
        'konselor' => route('konselor.dashboard'),
        default    => route('konseli.dashboard'),
    });
})->middleware(['auth'])->name('dashboard');

Route::middleware('guest')->group(function () {
    Route::get('/register/konselor', [KonselorRegisterController::class, 'create'])
        ->name('register.konselor');
    Route::post('/register/konselor', [KonselorRegisterController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/konseli/profile/setup', [KonseliController::class, 'setupProfile'])->name('konseli.profile.setup');
    Route::post('/konseli/profile/setup', [KonseliController::class, 'storeProfileSetup'])->name('konseli.profile.setup.store');
});

Route::middleware('auth')->group(function () {
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
        Route::get('/approval-konselor', [AdminController::class, 'index'])->name('approval-konselor.index');
        Route::get('/users', [AdminController::class, 'index'])->name('users.index');
        Route::get('/konselor', [AdminController::class, 'index'])->name('konselor.index');
        Route::get('/konselor/detail', [AdminController::class, 'index'])->name('konselor.show');
        Route::post('/konselor/approve', fn () => back()->with('success', 'Pengajuan konselor disetujui.'))->name('konselor.approve');
        Route::post('/konselor/reject', fn () => back()->with('success', 'Pengajuan konselor ditolak.'))->name('konselor.reject');
        Route::get('/jadwal', [AdminController::class, 'index'])->name('jadwal.index');
        Route::get('/activity-log', [AdminController::class, 'index'])->name('activity-log.index');
    });

    Route::prefix('konseli')->group(function () {
        Route::get('/dashboard', [KonseliController::class, 'index'])->name('konseli.dashboard');
        Route::get('/assessment', [KonseliController::class, 'assessment'])->name('konseli.assessment');
        Route::post('/assessment', [KonseliController::class, 'storeAssessment'])->name('konseli.assessment.store');
        Route::get('/pengajuan', [KonseliController::class, 'pengajuan'])->name('konseli.pengajuan');
        Route::post('/pengajuan', [KonseliController::class, 'storePengajuan'])->name('konseli.pengajuan.store');
        Route::get('/riwayat', [KonseliController::class, 'riwayat'])->name('konseli.riwayat');
        Route::get('/jadwal', [KonseliController::class, 'jadwal'])->name('konseli.jadwal');
        Route::get('/profil', [KonseliController::class, 'profil'])->name('konseli.profil');
    });

    Route::prefix('konselor')->group(function () {
        Route::get('/pending', [KonselorController::class, 'pending'])->name('konselor.pending');
        Route::get('/dashboard', [KonselorController::class, 'index'])->name('konselor.dashboard');
        Route::get('/pengajuan', [KonselorController::class, 'pengajuan'])->name('konselor.pengajuan');
        Route::get('/jadwal', [KonselorController::class, 'jadwal'])->name('konselor.jadwal');
        Route::get('/riwayat', [KonselorController::class, 'riwayat'])->name('konselor.riwayat');
        Route::get('/profil', [KonselorController::class, 'profil'])->name('konselor.profil');
    });
});

require __DIR__ . '/auth.php';
