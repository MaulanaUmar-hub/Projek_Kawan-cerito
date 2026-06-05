<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\KonseliController;
use App\Http\Controllers\KonselorController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing.index');
});

Route::get('/dashboard', function () {
    $role = auth()->user()->role;
    return redirect(match ($role) {
        'admin'    => route('admin.dashboard'),
        'konselor' => route('konselor.dashboard'),
        default    => route('konseli.dashboard'),
    });
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/konseli/profile/setup', [KonseliController::class, 'setupProfile'])->name('konseli.profile.setup');
    Route::post('/konseli/profile/setup', [KonseliController::class, 'storeProfileSetup'])->name('konseli.profile.setup.store');
});

Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
Route::get('/konseli/dashboard', [KonseliController::class, 'index'])->name('konseli.dashboard');
Route::get('/konseli/assessment', [KonseliController::class, 'assessment'])->name('konseli.assessment');
Route::post('/konseli/assessment', [KonseliController::class, 'storeAssessment'])->name('konseli.assessment.store');
Route::get('/konseli/pengajuan', [KonseliController::class, 'pengajuan'])->name('konseli.pengajuan');
Route::post('/konseli/pengajuan', [KonseliController::class, 'storePengajuan'])->name('konseli.pengajuan.store');
Route::get('/konseli/riwayat', [KonseliController::class, 'riwayat'])->name('konseli.riwayat');
Route::get('/konseli/jadwal', [KonseliController::class, 'jadwal'])->name('konseli.jadwal');
Route::get('/konseli/profil', [KonseliController::class, 'profil'])->name('konseli.profil');
Route::get('/konselor/dashboard', [KonselorController::class, 'index'])->name('konselor.dashboard');
Route::get('/konselor/pengajuan', [KonselorController::class, 'pengajuan'])->name('konselor.pengajuan');
Route::get('/konselor/jadwal', [KonselorController::class, 'jadwal'])->name('konselor.jadwal');
Route::get('/konselor/riwayat', [KonselorController::class, 'riwayat'])->name('konselor.riwayat');
Route::get('/konselor/profil', [KonselorController::class, 'profil'])->name('konselor.profil');

require __DIR__ . '/auth.php';
