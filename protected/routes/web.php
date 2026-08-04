<?php

use App\Http\Controllers\AkomodasiController;
use App\Http\Controllers\AktivitasController;
use App\Http\Controllers\AlokasiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\GudangController;
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

Route::prefix('barang')->middleware(['web', 'auth', 'permission:admin,verifikator,superadmin'])->group(function () {
    Route::get('/', [BarangController::class, 'index'])->name('barang.index');
    Route::get('/{id}/detail', [BarangController::class, 'detail'])->name('barang.detail');
    Route::post('/store', [BarangController::class, 'store'])->name('barang.store');
    Route::put('/{id}/update', [BarangController::class, 'update'])->name('barang.update');
    Route::post('/{id}/delete', [BarangController::class, 'delete'])->name('barang.delete');

    Route::get('/download-format', [BarangController::class, 'downloadFormat'])->name('barang.format');
    Route::post('/import', [BarangController::class, 'import'])->name('barang.import');
});

Route::prefix('gudang')->middleware(['web', 'auth', 'permission:admin,verifikator,superadmin'])->group(function () {
    Route::get('/', [GudangController::class, 'index'])->name('gudang.index');
    Route::get('/{id}/detail', [GudangController::class, 'detail'])->name('gudang.detail');
    Route::post('/store', [GudangController::class, 'store'])->name('gudang.store');
    Route::put('/{id}/update', [GudangController::class, 'update'])->name('gudang.update');
    Route::post('/{id}/delete', [GudangController::class, 'delete'])->name('gudang.delete');
});

Route::prefix('lokasi')->middleware(['web', 'auth', 'permission:admin,superadmin'])->group(function () {
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

Route::prefix('karyawan')->middleware(['web', 'auth', 'permission:admin,superadmin'])->group(function () {
    Route::get('/', [KaryawanController::class, 'index'])->name('karyawan.index');
    Route::get('/{id}/detail', [KaryawanController::class, 'detail'])->name('karyawan.detail');
    Route::post('/store', [KaryawanController::class, 'store'])->name('karyawan.store');
    Route::put('/{id}/update', [KaryawanController::class, 'update'])->name('karyawan.update');
    Route::post('/{id}/delete', [KaryawanController::class, 'delete'])->name('karyawan.delete');
    Route::post('/import', [KaryawanController::class, 'import'])->name('karyawan.import');
});

Route::prefix('stok')->middleware(['web', 'auth'])->group(function () {
    Route::get('/', [StokController::class, 'index'])->name('stok.index')->middleware('permission:jpn,admin,verifikator,superadmin');
    Route::get('/daftar-transaksi', [StokController::class, 'listTransaction'])->name('stok.transaksi')->middleware('permission:jpn,admin,verifikator,superadmin');
    Route::get('/transaksi/{noref}/detail', [StokController::class, 'detailTransaksi'])->name('stok.transaksi.detail')->middleware('permission:jpn,admin,verifikator,superadmin');
    Route::get('/log', [StokController::class, 'log'])->name('stok.log')->middleware('permission:jpn,admin,verifikator,superadmin');;
    Route::get('/stok-masuk', [StokController::class, 'viewStokMasuk'])->name('stok.masuk.view')->middleware('permission:admin,verifikator,superadmin');
    Route::post('/stok-masuk', [StokController::class, 'storeStokMasuk'])->name('stok.masuk.store')->middleware('permission:admin,verifikator,superadmin');
    Route::get('/stok-keluar', [StokController::class, 'viewStokKeluar'])->name('stok.keluar.view')->middleware('permission:superadmin,verifikator');
    Route::post('/stok-keluar', [StokController::class, 'storeStokKeluar'])->name('stok.keluar.store')->middleware('permission:superadmin,verifikator');
    Route::get('/stok-koreksi', [StokController::class, 'viewStokKoreksi'])->name('stok.koreksi.view')->middleware('permission:verifikator,superadmin');
    Route::post('/stok-koreksi', [StokController::class, 'storeStokKoreksi'])->name('stok.koreksi.store')->middleware('permission:verifikator,superadmin');
    Route::get('/{noref}/retur', [StokController::class, 'viewRetur'])->name('stok.retur.view')->middleware('permission:superadmin,verifikator');
    Route::post('/{noref}/retur', [StokController::class, 'storeRetur'])->name('stok.retur.store')->middleware('permission:superadmin,verifikator');
    Route::delete('/{noref}/delete-retur', [StokController::class, 'deleteRetur'])->name('stok.retur.delete')->middleware('permission:superadmin,verifikator');

    Route::get('/export-pdf', [StokController::class, 'exportPdf'])->name('stok.export.pdf')->middleware('permission:admin,verifikator,superadmin');
    Route::get('/lokasi/{id}', [StokController::class, 'getSubLokasi'])->name('stok.sublokasi')->middleware('permission:admin,verifikator,superadmin');
    Route::get('/rencana-sk', [StokController::class, 'rencanaSK'])->name('stok.rencana')->middleware('permission:admin,verifikator,superadmin');
    Route::post('/rencana-sk', [StokController::class, 'storeRencanaSK'])->name('stok.rencana.cetak')->middleware('permission:admin,verifikator,superadmin');

    Route::put('/log/update', [StokController::class, 'logupdate'])->name('stok.log.update')->middleware('permission:superadmin');
    Route::delete('/log/delete', [StokController::class, 'logdelete'])->name('stok.log.delete')->middleware('permission:superadmin');

    Route::get('/invoice/{noref}', [StokController::class, 'invoice'])->name('stok.invoice')->middleware('permission:jpn,admin,verifikator,superadmin');
    Route::get('/invoice/{noref}/print', [StokController::class, 'printInvoice'])->name('stok.invoice.print')->middleware('permission:jpn,admin,verifikator,superadmin');

    Route::get('/gudang/{idgudang}/{level?}', [StokController::class, 'getItemWithStock'])->name('stok.keluar.bygudang')->middleware('permission:admin,verifikator,superadmin');
});

Route::prefix('aktivitas')->middleware(['web', 'auth'])->group(function () {
    Route::get('/', [AktivitasController::class, 'index'])->name('aktivitas.index')->middleware('permission:jpn,admin,verifikator,superadmin');
    Route::get('/{id}/detail', [AktivitasController::class, 'getDetail'])->name('aktivitas.getdetail')->middleware('permission:jpn,admin,verifikator,superadmin');
    Route::get('/{tiket}/edit', [AktivitasController::class, 'edit'])->name('aktivitas.edit')->middleware('permission:admin,superadmin');
    Route::get('/input', [AktivitasController::class, 'input'])->name('aktivitas.input')->middleware('permission:admin,superadmin');
    Route::get('/lokasi/{id}', [AktivitasController::class, 'getSubLokasi'])->name('aktivitas.sublokasi')->middleware('permission:admin,superadmin');
    Route::post('/store', [AktivitasController::class, 'store'])->name('aktivitas.store')->middleware('permission:admin,superadmin');
    Route::post('/{tiket}/update', [AktivitasController::class, 'update'])->name('aktivitas.update')->middleware('permission:admin,superadmin');
    Route::post('/{tiket}/hapus', [AktivitasController::class, 'hapusTiket'])->name('aktivitas.hapus')->middleware('permission:superadmin');
    Route::put('/{tiket}/update-status', [AktivitasController::class, 'updateStatus'])->name('aktivitas.update.status')->middleware('permission:admin,superadmin');

    Route::get('/export-pdf', [AktivitasController::class, 'exportPdf'])->name('aktivitas.export.pdf')->middleware('permission:admin,superadmin');
    Route::get('/print-tiket/{tiket}', [AktivitasController::class, 'printTiket'])->name('aktivitas.print.tiket')->middleware('permission:admin,superadmin');
    Route::get('/print-pengajuan/{tiket}', [AktivitasController::class, 'printPengajuan'])->name('aktivitas.print.pengajuan')->middleware('permission:admin,superadmin,verifikator');
    Route::get('/{tiket}/edit-stok-keluar', [AktivitasController::class, 'editStockOut'])->name('aktivitas.editstokout.view')->middleware('permission:superadmin');
    Route::post('/{tiket}/edit-stok-keluar', [AktivitasController::class, 'postEditStockOut'])->name('aktivitas.editstokout.post')->middleware('permission:superadmin');
    Route::get('/{tiket}/input-stok-keluar', [AktivitasController::class, 'inputPengajuanStock'])->name('aktivitas.inputstokout.view')->middleware('permission:admin,superadmin');
    Route::post('/{tiket}/input-stok-keluar', [AktivitasController::class, 'postInputPengajuanStock'])->name('aktivitas.inputstokout.post')->middleware('permission:admin,superadmin');
    Route::get('/{tiket}/review-stok-keluar', [AktivitasController::class, 'reviewStockOut'])->name('aktivitas.reviewstokout.view')->middleware('permission:verifikator,superadmin');
    Route::post('/{tiket}/review-stok-keluar', [AktivitasController::class, 'postReviewStockOut'])->name('aktivitas.reviewstokout.post')->middleware('permission:verifikator,superadmin');
    Route::post('/{tiket}/decline-review-stok-keluar', [AktivitasController::class, 'declineReviewStockOut'])->name('aktivitas.declinereviewstokout.post')->middleware('permission:verifikator,superadmin');
});

Route::prefix('alokasi')->middleware(['web', 'auth', 'permission:admin,superadmin'])->group(function () {
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
