<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\AdminUserController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\CheckUserBlacklist;

// Public Routes
Route::get('/', function () {
    return view('welcome');
});

// Authentication Required Routes
Route::middleware(['auth', 'verified', CheckUserBlacklist::class])->group(function () {
    // Dashboard Routes
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard-siswa', function () {
        return view('dashboard-siswa');
    })->name('dashboard.siswa');

    // Katalog Buku - Accessible by all authenticated users
    Route::get('/buku', [BukuController::class, 'index'])->name('buku.index');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Siswa Routes - Peminjaman
    Route::prefix('peminjaman')->name('peminjaman.')->group(function () {
        Route::get('/', [PeminjamanController::class, 'index'])->name('index');
        Route::post('/ajukan/{buku}', [PeminjamanController::class, 'ajukan'])->name('ajukan');
        Route::get('/riwayat', [PeminjamanController::class, 'riwayat'])->name('riwayat');
        Route::get('/pengembalian', [PeminjamanController::class, 'pengembalian'])->name('pengembalian');
        Route::post('/kembalikan/{id}', [PeminjamanController::class, 'kembalikan'])->name('kembalikan');
    });
});

// Admin Only Routes
Route::middleware(['auth', AdminMiddleware::class])->group(function () {
    // User Management
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/users', [AdminUserController::class, 'index'])->name('users');
        Route::post('/users/{id}/blacklist', [AdminUserController::class, 'blacklist'])->name('users.blacklist');
    });

    // Buku Management
    Route::prefix('buku')->name('buku.')->group(function () {
        Route::get('/create', [BukuController::class, 'create'])->name('create');
        Route::post('/', [BukuController::class, 'store'])->name('store');
        Route::get('/{buku}/edit', [BukuController::class, 'edit'])->name('edit');
        Route::put('/{buku}', [BukuController::class, 'update'])->name('update');
        Route::delete('/{buku}', [BukuController::class, 'destroy'])->name('destroy');
    });

    // Peminjaman Management for Admin
    Route::prefix('peminjaman')->name('peminjaman.')->group(function () {
        Route::get('/pengajuan', [PeminjamanController::class, 'pengajuan'])->name('pengajuan');
        Route::post('/setujui/{id}', [PeminjamanController::class, 'setujui'])->name('setujui');
        Route::post('/tolak/{id}', [PeminjamanController::class, 'tolak'])->name('tolak');

        // Book return management routes
        Route::get('/daftar-pengembalian', [PeminjamanController::class, 'daftarPengembalian'])
            ->name('daftar-pengembalian');

        Route::post('/{peminjaman}/konfirmasi-pengembalian', [PeminjamanController::class, 'konfirmasiPengembalian'])
            ->name('konfirmasi-pengembalian');
    });
});

require __DIR__.'/auth.php';