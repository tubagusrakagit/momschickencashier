<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\AuthController; // <--- Import AuthController

/*
|--------------------------------------------------------------------------
| Public Routes (Akses Tanpa Login)
|--------------------------------------------------------------------------
| Meliputi halaman login.
*/

Route::middleware('guest')->group(function () {
    // Rute default mengarahkan ke halaman login
    Route::get('/', [AuthController::class, 'showLogin'])->name('login');
    
    // Tampilkan halaman login
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    
    // Proses POST data dari form login
    Route::post('/login', [AuthController::class, 'authenticate'])->name('login.auth');
});


/*
|--------------------------------------------------------------------------
| Protected Routes (Akses Setelah Login)
|--------------------------------------------------------------------------
| Menggunakan middleware 'auth' untuk mengamankan halaman.
*/

Route::middleware('auth')->group(function () {
    
    // Halaman Kasir
    Route::get('/kasir', [TransactionController::class, 'index'])->name('kasir');

    // Halaman Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

});
