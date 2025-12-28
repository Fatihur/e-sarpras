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
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| TEMPORARY MIGRATION FIX ROUTE - HAPUS SETELAH DIGUNAKAN!
|--------------------------------------------------------------------------
*/
Route::get('/run-migration-fix/{secret}', function ($secret) {
    // Secret key untuk keamanan
    if ($secret !== 'esarpras2024fix') {
        abort(404);
    }

    $results = [];

    // Fix 1: Tambah kolom jumlah ke barang_rusak
    if (!Schema::hasColumn('barang_rusak', 'jumlah')) {
        Schema::table('barang_rusak', function ($table) {
            $table->integer('jumlah')->default(1)->after('deskripsi_kerusakan');
        });
        $results[] = '✅ Kolom "jumlah" berhasil ditambahkan ke tabel barang_rusak';
    } else {
        $results[] = '⚠️ Kolom "jumlah" sudah ada di tabel barang_rusak';
    }

    // Fix 2: Tambah kolom luas_bangunan ke lahan (jika belum ada)
    try {
        if (!Schema::hasColumn('lahan', 'luas_bangunan')) {
            Schema::table('lahan', function ($table) {
                $table->decimal('luas_bangunan', 10, 2)->nullable();
            });
            $results[] = '✅ Kolom "luas_bangunan" berhasil ditambahkan ke tabel lahan';
        } else {
            $results[] = '⚠️ Kolom "luas_bangunan" sudah ada di tabel lahan';
        }
    } catch (\Exception $e) {
        $results[] = '⚠️ Tabel lahan: ' . $e->getMessage();
    }

    return '<h1>Migration Fix Results</h1><ul><li>' . implode('</li><li>', $results) . '</li></ul><br><strong>PENTING: Hapus route ini dari web.php setelah selesai!</strong>';
});

// Auth Routes
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | ADMIN ONLY - CRUD MASTER DATA
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:admin')->group(function () {

        // Kategori CRUD
        Route::get('kategori/create', [KategoriController::class, 'create'])->name('kategori.create');
        Route::post('kategori', [KategoriController::class, 'store'])->name('kategori.store');
        Route::get('kategori/{kategori}/edit', [KategoriController::class, 'edit'])->name('kategori.edit');
        Route::put('kategori/{kategori}', [KategoriController::class, 'update'])->name('kategori.update');
        Route::delete('kategori/{kategori}', [KategoriController::class, 'destroy'])->name('kategori.destroy');

        // Lahan CRUD
        Route::get('lahan/create', [LahanController::class, 'create'])->name('lahan.create');
        Route::post('lahan', [LahanController::class, 'store'])->name('lahan.store');
        Route::get('lahan/{lahan}/edit', [LahanController::class, 'edit'])->name('lahan.edit');
        Route::put('lahan/{lahan}', [LahanController::class, 'update'])->name('lahan.update');
        Route::delete('lahan/{lahan}', [LahanController::class, 'destroy'])->name('lahan.destroy');

        // Gedung CRUD
        Route::get('gedung/create', [GedungController::class, 'create'])->name('gedung.create');
        Route::post('gedung', [GedungController::class, 'store'])->name('gedung.store');
        Route::get('gedung/{gedung}/edit', [GedungController::class, 'edit'])->name('gedung.edit');
        Route::put('gedung/{gedung}', [GedungController::class, 'update'])->name('gedung.update');
        Route::delete('gedung/{gedung}', [GedungController::class, 'destroy'])->name('gedung.destroy');

        // Ruangan CRUD
        Route::get('ruangan/create', [RuanganController::class, 'create'])->name('ruangan.create');
        Route::post('ruangan', [RuanganController::class, 'store'])->name('ruangan.store');
        Route::get('ruangan/{ruangan}/edit', [RuanganController::class, 'edit'])->name('ruangan.edit');
        Route::put('ruangan/{ruangan}', [RuanganController::class, 'update'])->name('ruangan.update');
        Route::delete('ruangan/{ruangan}', [RuanganController::class, 'destroy'])->name('ruangan.destroy');

        // Barang CRUD
        Route::get('barang/create', [BarangController::class, 'create'])->name('barang.create');
        Route::post('barang', [BarangController::class, 'store'])->name('barang.store');
        Route::get('barang/{barang}/edit', [BarangController::class, 'edit'])->name('barang.edit');
        Route::put('barang/{barang}', [BarangController::class, 'update'])->name('barang.update');
        Route::delete('barang/{barang}', [BarangController::class, 'destroy'])->name('barang.destroy');

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
    | ADMIN & PIMPINAN - READ ONLY MASTER DATA
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:admin,pimpinan')->group(function () {

        // Master Data (Read Only - Index & Show)
        Route::get('kategori', [KategoriController::class, 'index'])->name('kategori.index');
        Route::get('lahan', [LahanController::class, 'index'])->name('lahan.index');
        Route::get('gedung', [GedungController::class, 'index'])->name('gedung.index');
        Route::get('ruangan', [RuanganController::class, 'index'])->name('ruangan.index');
        Route::get('barang', [BarangController::class, 'index'])->name('barang.index');
        Route::get('barang/{barang}', [BarangController::class, 'show'])->name('barang.show');
        Route::get('barang/{barang}/download-qr', [BarangController::class, 'downloadQr'])->name('barang.download-qr');
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
