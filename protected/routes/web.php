<?php

use App\Http\Controllers\AktivitasController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\LokasiController;
use App\Http\Controllers\StokController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('barang')->group(function () {
    Route::get('/', [BarangController::class, 'index'])->name('barang.index');
    Route::get('/list', [BarangController::class, 'index'])->name('barang.list');
    Route::post('/store', [BarangController::class, 'store'])->name('barang.store');
    Route::post('/update', [BarangController::class, 'update'])->name('barang.update');
    Route::post('/delete', [BarangController::class, 'delete'])->name('barang.delete');
});

Route::prefix('lokasi')->group(function () {
    Route::get('/', [LokasiController::class, 'index'])->name('lokasi.index');
    Route::get('/list', [LokasiController::class, 'index'])->name('lokasi.index');
    Route::post('/create', [LokasiController::class, 'store'])->name('lokasi.store');
    Route::post('/edit', [LokasiController::class, 'update'])->name('lokasi.update');
    Route::post('/delete', [LokasiController::class, 'delete'])->name('lokasi.delete');
});

Route::prefix('karyawan')->group(function () {
    Route::get('/', [KaryawanController::class, 'index'])->name('index');
    Route::get('/list', [KaryawanController::class, 'index'])->name('index');
    Route::post('/create', [KaryawanController::class, 'store'])->name('store');
    Route::post('/edit', [KaryawanController::class, 'update'])->name('update');
    Route::post('/delete', [KaryawanController::class, 'delete'])->name('dekete');
});

Route::prefix('stok')->group(function () {
    Route::get('/', [StokController::class, '']);
    Route::get('/list', [StokController::class, '']);
    Route::post('/create', [StokController::class, '']);
    Route::post('/edit', [StokController::class, '']);
    Route::post('/delete', [StokController::class, '']);
});

Route::prefix('aktivitas')->group(function () {
    Route::get('/', [AktivitasController::class, '']);
    Route::get('/list', [AktivitasController::class, '']);
    Route::post('/create', [AktivitasController::class, '']);
    Route::post('/edit', [AktivitasController::class, '']);
    Route::post('/delete', [AktivitasController::class, '']);
});
