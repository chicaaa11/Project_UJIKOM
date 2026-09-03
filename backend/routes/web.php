<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\PeminjamController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsPetugas;
use App\Http\Middleware\IsPeminjam;

Route::get('/', function () {
    return view('welcome');
});

// Admin Group
Route::middleware(['auth', IsAdmin::class])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

    // CRUD Alat
    Route::get('/alat', [AdminController::class, 'indexAlat'])->name('alat.index');
    Route::get('/alat/create', [AdminController::class, 'createAlat'])->name('alat.create');
    Route::post('/alat', [AdminController::class, 'storeAlat'])->name('alat.store');
    Route::get('/alat/{id}/edit', [AdminController::class, 'editAlat'])->name('alat.edit');
    Route::put('/alat/{id}', [AdminController::class, 'updateAlat'])->name('alat.update');
    Route::delete('/alat/{id}', [AdminController::class, 'destroyAlat'])->name('alat.destroy');

    // CRUD User (Otomatis menghasilkan route: admin.users.index, admin.users.create, dll)
    Route::resource('/users', UserController::class);
    Route::get('/users-list', [UserController::class, 'index'])->name('users.index');

    // CRUD Kategori
    Route::get('/kategori', [AdminController::class, 'indexKategori'])->name('kategori.index');
    Route::get('/kategori/create', [AdminController::class, 'createKategori'])->name('kategori.create');
    Route::post('/kategori', [AdminController::class, 'storeKategori'])->name('kategori.store');
    Route::get('/kategori/{id}/edit', [AdminController::class, 'editKategori'])->name('kategori.edit');
    Route::put('/kategori/{id}', [AdminController::class, 'updateKategori'])->name('kategori.update');
    Route::delete('/kategori/{id}', [AdminController::class, 'destroyKategori'])->name('kategori.destroy');

    // CRUD Peminjaman
    Route::get('/peminjaman', [AdminController::class, 'indexPeminjaman'])->name('peminjaman.index');
    Route::get('/peminjaman/create', [AdminController::class, 'createPeminjaman'])->name('peminjaman.create');
    Route::post('/peminjaman', [AdminController::class, 'storePeminjaman'])->name('peminjaman.store');
    Route::put('/peminjaman/{id}/status', [AdminController::class, 'updateStatusPeminjaman'])->name('peminjaman.updateStatus');
    Route::delete('/peminjaman/{id}', [AdminController::class, 'destroyPeminjaman'])->name('peminjaman.destroy');
    
    // CRUD Pengembalian
    Route::get('/pengembalian', [AdminController::class, 'indexPengembalian'])->name('pengembalian.index');
    Route::put('/pengembalian/{id}/proses', [AdminController::class, 'prosesPengembalian'])->name('pengembalian.proses');
    Route::delete('/pengembalian/{id}', [AdminController::class, 'destroyPengembalian'])->name('pengembalian.destroy');
});

// Petugas Group
Route::middleware(['auth', IsPetugas::class])->prefix('petugas')->name('petugas.')->group(function () {
    Route::get('/peminjaman', [PetugasController::class, 'indexPeminjaman'])->name('peminjaman.index');
    Route::post('/peminjaman/{id}/setujui', [PetugasController::class, 'setujuiPeminjaman'])->name('peminjaman.setujui');

    // Pengembalian & Denda
    Route::post('/pengembalian/{id}', [PetugasController::class, 'prosesPengembalian'])->name('pengembalian.proses');
});

// Peminjam Group
Route::middleware(['auth', IsPeminjam::class])->prefix('peminjam')->name('peminjam.')->group(function () {
    // Katalog & Pengajuan
    Route::get('/katalog', [PeminjamController::class, 'katalogAlat'])->name('katalog');
    Route::post('/peminjaman/ajukan', [PeminjamController::class, 'ajukanPeminjaman'])->name('peminjaman.ajukan');
    Route::get('/riwayat', [PeminjamController::class, 'riwayatPeminjaman'])->name('riwayat');
});

// Guest Group
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');