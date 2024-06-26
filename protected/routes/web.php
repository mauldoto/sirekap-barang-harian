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
    Route::get('/{id}/detail', [BarangController::class, 'detail'])->name('barang.detail');
    Route::post('/store', [BarangController::class, 'store'])->name('barang.store');
    Route::put('/{id}/update', [BarangController::class, 'update'])->name('barang.update');
    Route::post('/{id}/delete', [BarangController::class, 'delete'])->name('barang.delete');
});

Route::prefix('lokasi')->group(function () {
    Route::get('/', [LokasiController::class, 'index'])->name('lokasi.index');
    Route::post('/store', [LokasiController::class, 'store'])->name('lokasi.store');
    Route::put('/{id}/update', [LokasiController::class, 'update'])->name('lokasi.update');
    Route::get('/{id}/detail', [LokasiController::class, 'detail'])->name('lokasi.detail');
    Route::post('/{id}/delete', [LokasiController::class, 'delete'])->name('lokasi.delete');

    Route::prefix('/sublokasi')->group(function () {
        Route::get('/', [LokasiController::class, 'index'])->name('sublokasi.index');
        Route::post('/store', [LokasiController::class, 'substore'])->name('sublokasi.store');
        Route::put('/{id}/update', [LokasiController::class, 'subupdate'])->name('sublokasi.update');
        Route::get('/{id}/detail', [LokasiController::class, 'subdetail'])->name('sublokasi.detail');
        Route::post('/{id}/delete', [LokasiController::class, 'subdelete'])->name('sublokasi.delete');
    });
});

Route::prefix('karyawan')->group(function () {
    Route::get('/', [KaryawanController::class, 'index'])->name('karyawan.index');
    Route::get('/{id}/detail', [KaryawanController::class, 'detail'])->name('karyawan.detail');
    Route::post('/store', [KaryawanController::class, 'store'])->name('karyawan.store');
    Route::put('/{id}/update', [KaryawanController::class, 'update'])->name('karyawan.update');
    Route::post('/{id}/delete', [KaryawanController::class, 'delete'])->name('karyawan.delete');
});

Route::prefix('stok')->group(function () {
    Route::get('/', [StokController::class, '']);
    Route::post('/store', [StokController::class, '']);
    Route::post('/update', [StokController::class, '']);
    Route::post('/delete', [StokController::class, '']);
});

Route::prefix('aktivitas')->group(function () {
    Route::get('/', [AktivitasController::class, '']);
    Route::post('/store', [AktivitasController::class, '']);
    Route::post('/update', [AktivitasController::class, '']);
    Route::post('/delete', [AktivitasController::class, '']);
});
