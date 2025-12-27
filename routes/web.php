<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\BarangKeluarController;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\BarangRuanganController;
use App\Http\Controllers\BarangRusakController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GedungController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LahanController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\RuanganController;
use App\Http\Controllers\ScanController;
use App\Http\Controllers\TelegramController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Auth Routes
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | ADMIN ONLY
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:admin')->group(function () {

        // Master Data
        Route::resource('kategori', KategoriController::class)->except('show');
        Route::resource('lahan', LahanController::class)->except('show');
        Route::resource('gedung', GedungController::class)->except('show');
        Route::resource('ruangan', RuanganController::class)->except('show');
        Route::resource('barang', BarangController::class);
        Route::get('barang/{barang}/download-qr', [BarangController::class, 'downloadQr'])->name('barang.download-qr');

        // User Management
        Route::resource('users', UserController::class)->except('show');
        Route::post('users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
        Route::post('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');

        // Telegram
        Route::get('telegram', [TelegramController::class, 'index'])->name('telegram.index');
        Route::post('telegram', [TelegramController::class, 'update'])->name('telegram.update');
        Route::post('telegram/test', [TelegramController::class, 'testNotifikasi'])->name('telegram.test');
    });

    /*
    |--------------------------------------------------------------------------
    | ADMIN & MANAJEMEN
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:admin,manajemen')->group(function () {

        // Barang Ruangan (CRUD)
        Route::get('barang-ruangan/create', [BarangRuanganController::class, 'create'])->name('barang-ruangan.create');
        Route::post('barang-ruangan', [BarangRuanganController::class, 'store'])->name('barang-ruangan.store');
        Route::delete('barang-ruangan/{barangRuangan}', [BarangRuanganController::class, 'destroy'])->name('barang-ruangan.destroy');

        // Barang Masuk & Keluar
        Route::resource('barang-masuk', BarangMasukController::class)->except(['show', 'edit', 'update']);
        Route::get('barang-masuk/scan', [BarangMasukController::class, 'scan'])->name('barang-masuk.scan');
        Route::post('barang-masuk/scan', [BarangMasukController::class, 'scanStore'])->name('barang-masuk.scan.store');

        Route::resource('barang-keluar', BarangKeluarController::class)->except(['show', 'edit', 'update']);

        // Peminjaman
        Route::resource('peminjaman', PeminjamanController::class)->except(['show', 'edit', 'update', 'destroy']);
        Route::post('peminjaman/{peminjaman}/kembalikan', [PeminjamanController::class, 'kembalikan'])->name('peminjaman.kembalikan');
        Route::delete('peminjaman/{peminjaman}', [PeminjamanController::class, 'destroy'])->name('peminjaman.destroy');
        Route::get('peminjaman/scan', [PeminjamanController::class, 'scan'])->name('peminjaman.scan');
        Route::post('peminjaman/scan', [PeminjamanController::class, 'scanProcess'])->name('peminjaman.scan.process');

        // Barang Rusak
        Route::resource('barang-rusak', BarangRusakController::class)->except(['show', 'edit', 'update']);
        Route::get('barang-rusak/scan', [BarangRusakController::class, 'scan'])->name('barang-rusak.scan');
        Route::post('barang-rusak/scan', [BarangRusakController::class, 'scanStore'])->name('barang-rusak.scan.store');
        Route::post('barang-rusak/{barangRusak}/update-status', [BarangRusakController::class, 'updateStatus'])->name('barang-rusak.update-status');

        // QR Scanner
        Route::get('scan', [ScanController::class, 'index'])->name('scan.index');
        Route::post('scan', [ScanController::class, 'process'])->name('scan.process');
    });

    /*
    |--------------------------------------------------------------------------
    | VIEW BARANG RUANGAN (ALL ROLES)
    |--------------------------------------------------------------------------
    */
    Route::get('barang-ruangan', [BarangRuanganController::class, 'index'])->name('barang-ruangan.index');
    Route::get('barang-ruangan/{ruangan}', [BarangRuanganController::class, 'show'])->name('barang-ruangan.show');

    /*
    |--------------------------------------------------------------------------
    | LAPORAN
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:admin,manajemen,pimpinan')
        ->prefix('laporan')
        ->name('laporan.')
        ->group(function () {

            Route::get('/', [LaporanController::class, 'index'])->name('index');
            Route::get('/barang-masuk', [LaporanController::class, 'barangMasuk'])->name('barang-masuk');
            Route::get('/barang-keluar', [LaporanController::class, 'barangKeluar'])->name('barang-keluar');
            Route::get('/peminjaman', [LaporanController::class, 'peminjaman'])->name('peminjaman');
            Route::get('/barang-rusak', [LaporanController::class, 'barangRusak'])->name('barang-rusak');
            Route::get('/barang-ruangan', [LaporanController::class, 'barangRuangan'])->name('barang-ruangan');
        });
});
