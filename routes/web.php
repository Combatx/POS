<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\PembelianDetailController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\PenjualanDetailController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\ResetPWController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SatuanController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserCrudController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/login', [LoginController::class, 'index'])->name('login.index')->middleware('guest');
Route::post('/login', [LoginController::class, 'authenticate'])->name('login.auth')->middleware('guest');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::group([
    'middleware' => ['auth']
], function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/cekstok', [DashboardController::class, 'cekstok'])->name('dashboard.cekstok');

    Route::get('/kategori/data', [KategoriController::class, 'data'])->name('kategori.data');
    Route::resource('/kategori', KategoriController::class);

    Route::get('/satuan/data', [SatuanController::class, 'data'])->name('satuan.data');
    Route::resource('/satuan', SatuanController::class);

    Route::get('/supplier/data', [SupplierController::class, 'data'])->name('supplier.data');
    Route::resource('/supplier', SupplierController::class);

    Route::get('/pelanggan/data', [PelangganController::class, 'data'])->name('pelanggan.data');
    Route::resource('/pelanggan', PelangganController::class);

    Route::get('/produk/data', [ProdukController::class, 'data'])->name('produk.data');
    Route::resource('/produk', ProdukController::class);

    Route::get('/pembelian/data', [PembelianController::class, 'data'])->name('pembelian.data');
    //Route::get('/pembelian/{id}/create', [PembelianController::class, 'create'])->name('pembelian.create');
    Route::resource('/pembelian', PembelianController::class);

    Route::get('/pembelian_detail/getsupplier/{id}', [PembelianDetailController::class, 'getsupplier'])->name('pembelian_detail.getsupplier');
    Route::get('/pembelian_detail/supplier/data', [PembelianDetailController::class, 'data_supplier'])->name('pembelian_detail.data_supplier');
    Route::get('/pembelian_detail/{id}/data', [PembelianDetailController::class, 'data'])->name('pembelian_detail.data');
    Route::get('/pembelian_detail/loadform/{diskon}/{total}', [PembelianDetailController::class, 'loadForm'])->name('pembelian_detail.load_form');
    Route::resource('/pembelian_detail', PembelianDetailController::class)
        ->except('create', 'show', 'edit');

    Route::get('/penjualan/data', [PenjualanController::class, 'data'])->name('penjualan.data');
    Route::get('/penjualan', [PenjualanController::class, 'index'])->name('penjualan.index');
    Route::get('/penjualan/{id}', [PenjualanController::class, 'show'])->name('penjualan.show');
    Route::delete('/penjualan/{id}', [PenjualanController::class, 'destroy'])->name('penjualan.destroy');

    Route::get('transaksi/baru', [PenjualanController::class, 'create'])->name('transaksi.baru');
    Route::post('transaksi/simpan', [PenjualanController::class, 'store'])->name('transaksi.simpan');
    Route::get('transaksi/selesai', [PenjualanController::class, 'selesai'])->name('transaksi.selesai');
    Route::get('transaksi/nota-kecil', [PenjualanController::class, 'notaKecil'])->name('transaksi.nota_kecil');
    Route::get('transaksi/nota-besar', [PenjualanController::class, 'notaBesar'])->name('transaksi.nota_besar');

    Route::get('transaksi/{id}/data', [PenjualanDetailController::class, 'data'])->name('transaksi.data');
    Route::get('transaksi/loadform/{id}/{total}/{diterima}', [PenjualanDetailController::class, 'loadForm'])->name('transaksi.load_form');
    Route::resource('/transaksi', PenjualanDetailController::class)
        ->except('show');

    Route::resource('/setting', SettingController::class);

    Route::resource('/profil', UserController::class);

    Route::get('/user/data', [UserCrudController::class, 'data'])->name('user.data');
    Route::post('/user/status/{id}', [UserCrudController::class, 'status'])->name('user.status');
    Route::resource('/user', UserCrudController::class);

    Route::get('/role/data', [RoleController::class, 'data'])->name('role.data');
    Route::resource('/role', RoleController::class);

    Route::get('/reset', [ResetPWController::class, 'index'])->name('reset.index');
    Route::put('/reset/update/{id}', [ResetPWController::class, 'update'])->name('reset.update');
});

Route::group(['middleware' => ['auth', 'role:admin']], function () {
    Route::resource('/setting', SettingController::class);
});
