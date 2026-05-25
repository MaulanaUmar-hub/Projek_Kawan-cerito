<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\KonseliController;
use App\Http\Controllers\KonselorController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing_page.home');
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
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
});

Route::middleware(['auth', 'role:konseli'])->group(function () {
    Route::get('/konseli/dashboard', [KonseliController::class, 'index'])->name('konseli.dashboard');
});

Route::middleware(['auth', 'role:konselor'])->group(function () {
    Route::get('/konselor/dashboard', [KonselorController::class, 'index'])->name('konselor.dashboard');
});

require __DIR__ . '/auth.php';
