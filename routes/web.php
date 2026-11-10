<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Rute aplikasi to-do list berbobot UKK RPL.
| Memisahkan rute publik untuk tamu (guest) dan rute terproteksi (auth).
|
*/

// REASONING UKK: Middleware guest mencegah user yang sudah login mengakses kembali halaman login/register
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// REASONING UKK: Middleware auth memastikan seluruh operasi to-do list hanya dapat diakses oleh user yang telah terotentikasi
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Task Management Routes
    Route::get('/', [TaskController::class, 'index'])->name('tasks.index');
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::put('/tasks/{id}', [TaskController::class, 'update'])->name('tasks.update');
    // REASONING UKK: Menggunakan metode HTTP PATCH karena hanya mengubah status parsial satu atribut (is_completed)
    Route::patch('/tasks/{id}/toggle', [TaskController::class, 'toggle'])->name('tasks.toggle');
    // REASONING UKK: Rute PATCH untuk mengupdate slider progres tugas (0-100%) dan auto-selesai jika mentok kanan (100%)
    Route::patch('/tasks/{id}/progress', [TaskController::class, 'updateProgress'])->name('tasks.progress');
    Route::delete('/tasks/{id}', [TaskController::class, 'destroy'])->name('tasks.destroy');
});

