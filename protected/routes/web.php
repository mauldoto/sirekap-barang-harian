<?php

use App\Http\Controllers\AkomodasiController;
use App\Http\Controllers\AktivitasController;
use App\Http\Controllers\AlokasiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\LokasiController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StokController;
use App\Http\Controllers\UserController;
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
    return redirect(route('login'));
});

Route::prefix('auth')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login')->middleware('web', 'guest');
    Route::post('/login', [AuthController::class, 'authenticate'])->name('authenticate');
    Route::post('/logout', [AuthController::class, 'logout'])->middleware(['web', 'auth'])->name('logout');
});

Route::prefix('barang')->middleware(['web', 'auth', 'admin'])->group(function () {
    Route::get('/', [BarangController::class, 'index'])->name('barang.index');
    Route::get('/{id}/detail', [BarangController::class, 'detail'])->name('barang.detail');
    Route::post('/store', [BarangController::class, 'store'])->name('barang.store');
    Route::put('/{id}/update', [BarangController::class, 'update'])->name('barang.update');
    Route::post('/{id}/delete', [BarangController::class, 'delete'])->name('barang.delete');

    Route::get('/download-format', [BarangController::class, 'downloadFormat'])->name('barang.format');
    Route::post('/import', [BarangController::class, 'import'])->name('barang.import');
});

Route::prefix('lokasi')->middleware(['web', 'auth', 'admin'])->group(function () {
    Route::get('/', [LokasiController::class, 'index'])->name('lokasi.index');
    Route::post('/store', [LokasiController::class, 'store'])->name('lokasi.store');
    Route::put('/{id}/update', [LokasiController::class, 'update'])->name('lokasi.update');
    Route::get('/{id}/detail', [LokasiController::class, 'detail'])->name('lokasi.detail');
    Route::post('/{id}/delete', [LokasiController::class, 'delete'])->name('lokasi.delete');
    Route::post('/import', [LokasiController::class, 'importLokasi'])->name('lokasi.import');

    Route::prefix('/sublokasi')->group(function () {
        Route::get('/', [LokasiController::class, 'index'])->name('sublokasi.index');
        Route::post('/store', [LokasiController::class, 'substore'])->name('sublokasi.store');
        Route::put('/{id}/update', [LokasiController::class, 'subupdate'])->name('sublokasi.update');
        Route::get('/{id}/detail', [LokasiController::class, 'subdetail'])->name('sublokasi.detail');
        Route::post('/{id}/delete', [LokasiController::class, 'subdelete'])->name('sublokasi.delete');
        Route::post('/import', [LokasiController::class, 'importSubLokasi'])->name('sublokasi.import');
    });
});

Route::prefix('karyawan')->middleware(['web', 'auth', 'admin'])->group(function () {
    Route::get('/', [KaryawanController::class, 'index'])->name('karyawan.index');
    Route::get('/{id}/detail', [KaryawanController::class, 'detail'])->name('karyawan.detail');
    Route::post('/store', [KaryawanController::class, 'store'])->name('karyawan.store');
    Route::put('/{id}/update', [KaryawanController::class, 'update'])->name('karyawan.update');
    Route::post('/{id}/delete', [KaryawanController::class, 'delete'])->name('karyawan.delete');
    Route::post('/import', [KaryawanController::class, 'import'])->name('karyawan.import');
});

Route::prefix('stok')->middleware(['web', 'auth', 'admin'])->group(function () {
    Route::get('/', [StokController::class, 'index'])->name('stok.index');
    Route::get('/log', [StokController::class, 'log'])->name('stok.log');
    Route::get('/stok-masuk', [StokController::class, 'viewStokMasuk'])->name('stok.masuk.view');
    Route::post('/stok-masuk', [StokController::class, 'storeStokMasuk'])->name('stok.masuk.store');
    Route::get('/stok-keluar', [StokController::class, 'viewStokKeluar'])->name('stok.keluar.view')->middleware('super');
    Route::post('/stok-keluar', [StokController::class, 'storeStokKeluar'])->name('stok.keluar.store')->middleware('super');

    Route::get('/export-pdf', [StokController::class, 'exportPdf'])->name('stok.export.pdf');
    Route::get('/lokasi/{id}', [StokController::class, 'getSubLokasi'])->name('stok.sublokasi');
    Route::get('/rencana-sk', [StokController::class, 'rencanaSK'])->name('stok.rencana');
    Route::post('/rencana-sk', [StokController::class, 'cetakRencanaSK'])->name('stok.rencana.cetak');
});

Route::prefix('aktivitas')->middleware(['web', 'auth', 'admin'])->group(function () {
    Route::get('/', [AktivitasController::class, 'index'])->name('aktivitas.index');
    Route::get('/{id}/detail', [AktivitasController::class, 'getDetail'])->name('aktivitas.getdetail');
    Route::get('/{tiket}/edit', [AktivitasController::class, 'edit'])->name('aktivitas.edit');
    Route::get('/input', [AktivitasController::class, 'input'])->name('aktivitas.input');
    Route::get('/lokasi/{id}', [AktivitasController::class, 'getSubLokasi'])->name('aktivitas.sublokasi');
    Route::post('/store', [AktivitasController::class, 'store'])->name('aktivitas.store');
    Route::post('/{tiket}/update', [AktivitasController::class, 'update'])->name('aktivitas.update');
    Route::post('/{tiket}/hapus', [AktivitasController::class, 'hapusTiket'])->name('aktivitas.hapus');
    Route::put('/{tiket}/update-status', [AktivitasController::class, 'updateStatus'])->name('aktivitas.update.status');

    Route::get('/export-pdf', [AktivitasController::class, 'exportPdf'])->name('aktivitas.export.pdf');
    Route::get('/print-tiket/{tiket}', [AktivitasController::class, 'printTiket'])->name('aktivitas.print.tiket');
    Route::get('/{tiket}/edit-stok-keluar', [AktivitasController::class, 'editStockOut'])->name('aktivitas.editstokout.view');
    Route::post('/{tiket}/edit-stok-keluar', [AktivitasController::class, 'postEditStockOut'])->name('aktivitas.editstokout.post');
    Route::get('/{tiket}/input-stok-keluar', [AktivitasController::class, 'inputStockOut'])->name('aktivitas.inputstokout.view');
    Route::post('/{tiket}/input-stok-keluar', [AktivitasController::class, 'postInputStockOut'])->name('aktivitas.inputstokout.post');
});

Route::prefix('alokasi')->middleware(['web', 'auth', 'admin'])->group(function () {
    Route::get('/', [AlokasiController::class, 'index'])->name('alokasi');
    Route::post('/process', [AlokasiController::class, 'processAlokasi'])->name('alokasi.process');
});

Route::prefix('report')->middleware(['web', 'auth'])->group(function () {
    Route::get('/', [ReportController::class, 'report'])->name('report');
    Route::post('/process', [ReportController::class, 'processReport'])->name('report.process');
});

Route::prefix('akomodasi')->middleware(['web', 'auth', 'finance'])->group(function () {
    Route::get('/', [AkomodasiController::class, 'index'])->name('akomodasi.index');
    Route::get('/input', [AkomodasiController::class, 'inputView'])->name('akomodasi.input');
    Route::get('/{noref}/detail', [AkomodasiController::class, 'getDetail'])->name('akomodasi.detail');
    Route::get('/{noref}/edit', [AkomodasiController::class, 'editView'])->name('akomodasi.edit');
    Route::get('/{noref}/open', [AkomodasiController::class, 'openFile'])->name('akomodasi.open');

    Route::post('/input', [AkomodasiController::class, 'inputAkomodasi'])->name('akomodasi.store');
    Route::post('/{noref}/edit', [AkomodasiController::class, 'editAkomodasi'])->name('akomodasi.update');
    Route::post('/{noref}/delete', [AkomodasiController::class, 'deleteAkomodasi'])->name('akomodasi.delete');
    Route::post('/{noref}/delete-file', [AkomodasiController::class, 'deleteFile'])->name('akomodasi.delete.file');
});

Route::prefix('user')->middleware(['web', 'auth', 'super'])->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('user');
    Route::get('/{id}/detail', [UserController::class, 'getDetail'])->name('user.detail');
    Route::post('/store', [UserController::class, 'store'])->name('user.store');
    Route::put('/{id}/update', [UserController::class, 'update'])->name('user.update');
    Route::post('/{id}/delete', [UserController::class, 'delete'])->name('user.delete');
});
