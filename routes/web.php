<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController; 
use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| Public Routes (Akses Tanpa Login)
|--------------------------------------------------------------------------
| Definisi rute Login harus berada di luar middleware('guest') untuk robust
*/

// --- Rute Login/Auth (Harus diakses Guest) ---

// Halaman utama (root) mengarahkan ke halaman login
Route::get('/', function () {
    return redirect()->route('login');
});

// Tampilkan halaman login (ini adalah rute yang dipanggil oleh middleware Auth)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login'); 

// Proses POST data dari form login
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.auth');


/*
|--------------------------------------------------------------------------
| Protected Routes (Akses Setelah Login)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    
    // Rute Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // --- Rute untuk KASIR (Role: kasir) ---
    Route::middleware('role:kasir')->group(function () {
        Route::get('/kasir', [TransactionController::class, 'index'])->name('kasir');
        Route::post('/kasir/checkout', [TransactionController::class, 'store'])->name('kasir.store');
        Route::get('/kasir/struk/{id}', [TransactionController::class, 'print'])->name('kasir.print');
        // Rute Home untuk Kasir (Redirect dari middleware auth)
        // Sudah ditangani di RedirectIfAuthenticated.php, tapi jaga-jaga
        // Route::get('/', function () { return redirect()->route('kasir'); })->name('home.kasir');
    });
    
    
    // --- Rute untuk ADMIN (Role: admin) ---
    Route::middleware('role:admin')->group(function () {
        
        // Admin akan melihat dashboard sebagai halaman utama
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard'); 

        
        // Rute Home untuk Admin (Redirect dari middleware auth)
        // Sudah ditangani di RedirectIfAuthenticated.php, tapi jaga-jaga
        // Route::get('/', function () { return redirect()->route('dashboard'); })->name('home.admin');
        
        // Manajemen Produk (CRUD) - WAJIB diaktifkan
        Route::resource('produk', ProductController::class)->except(['show']);
    });

});