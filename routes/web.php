<?php

use App\Http\Controllers\BarangKeluarController;
use App\Http\Controllers\BarangKeluarDetailController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LaporanPendapatanController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\PembelianDetailController;
use App\Http\Controllers\PengirimanController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\PenjualanDetailController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\ResetPWController;
use App\Http\Controllers\ReturController;
use App\Http\Controllers\ReturDetailController;
use App\Http\Controllers\SatuanController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserCrudController;
use App\Models\BarangKeluarDetail;
use App\Models\ReturDetail;
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


// tanpa login
Route::get('/login', [LoginController::class, 'index'])->name('login.index')->middleware('guest');
Route::post('/login', [LoginController::class, 'authenticate'])->name('login.auth')->middleware('guest');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// perlu login
Route::group([
    'middleware' => ['auth']
], function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/cekstok', [DashboardController::class, 'cekstok'])->name('dashboard.cekstok');

    Route::resource('/profil', UserController::class);

    Route::get('/reset', [ResetPWController::class, 'index'])->name('reset.index');
    Route::put('/reset/update/{id}', [ResetPWController::class, 'update'])->name('reset.update');
});

// admin
Route::group(['middleware' => ['auth', 'role:admin']], function () {
    Route::resource('/setting', SettingController::class);

    Route::get('/user/data', [UserCrudController::class, 'data'])->name('user.data');
    Route::post('/user/status/{id}', [UserCrudController::class, 'status'])->name('user.status');
    Route::resource('/user', UserCrudController::class);

    // Route::get('/role/data', [RoleController::class, 'data'])->name('role.data');
    // Route::resource('/role', RoleController::class);

    Route::get('/laporan_pendapatan', [LaporanPendapatanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan_pendapatan/data/{awal}/{akhir}', [LaporanPendapatanController::class, 'data'])->name('laporan.data');
    Route::get('/laporan_pendapatan/pdf/{awal}/{akhir}', [LaporanPendapatanController::class, 'exportPDF'])->name('laporan.export_pdf');
});


// gudang !!
Route::group(['middleware' => ['auth', 'role:gudang']], function () {
    Route::get('/kategori/data', [KategoriController::class, 'data'])->name('kategori.data');
    Route::resource('/kategori', KategoriController::class);

    Route::get('/satuan/data', [SatuanController::class, 'data'])->name('satuan.data');
    Route::resource('/satuan', SatuanController::class);

    Route::get('/supplier/data', [SupplierController::class, 'data'])->name('supplier.data');
    Route::resource('/supplier', SupplierController::class);


    Route::get('/produk/data', [ProdukController::class, 'data'])->name('produk.data');
    Route::post('/produk/cetak-barcode', [ProdukController::class, 'cetak_barcode'])->name('produk.cetak_barcode');
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

    Route::get('/barangkeluar/data', [BarangKeluarController::class, 'data'])->name('barangkeluar.data');
    Route::resource('/barangkeluar', BarangKeluarController::class);

    Route::get('/barang_keluar_detail/{id}/data', [BarangKeluarDetailController::class, 'data'])->name('barang_keluar_detail.data');
    Route::get('/barang_keluar_detail/loadform/{total}', [BarangKeluarDetailController::class, 'loadForm'])->name('barang_keluar_detail.load_form');
    Route::resource('/barang_keluar_detail', BarangKeluarDetailController::class)
        ->except('create', 'show', 'edit');
});

//Kasir!!
Route::group(['middleware' => ['auth', 'role:kasir']], function () {

    Route::get('/pelanggan/data', [PelangganController::class, 'data'])->name('pelanggan.data');
    Route::resource('/pelanggan', PelangganController::class);

    Route::get('/penjualan/data', [PenjualanController::class, 'data'])->name('penjualan.data');
    Route::get('/penjualan', [PenjualanController::class, 'index'])->name('penjualan.index');
    Route::get('/penjualan/{id}', [PenjualanController::class, 'show'])->name('penjualan.show');
    Route::delete('/penjualan/{id}', [PenjualanController::class, 'destroy'])->name('penjualan.destroy');

    Route::get('transaksi/baru', [PenjualanController::class, 'create'])->name('transaksi.baru');
    Route::post('transaksi/simpan', [PenjualanController::class, 'store'])->name('transaksi.simpan');
    Route::get('transaksi/selesai', [PenjualanController::class, 'selesai'])->name('transaksi.selesai');
    Route::get('transaksi/nota-kecil', [PenjualanController::class, 'notaKecil'])->name('transaksi.nota_kecil');
    Route::get('transaksi/nota-besar', [PenjualanController::class, 'notaBesar'])->name('transaksi.nota_besar');

    Route::post('transaksi/cekkirim/{id}', [PenjualanDetailController::class, 'cekkirim'])->name('transaksi.cekkirim');
    Route::get('transaksi/pelanggan/data', [PenjualanDetailController::class, 'pelanggan'])->name('transaksi.pelanggan');
    Route::get('transaksi/{id}/data', [PenjualanDetailController::class, 'data'])->name('transaksi.data');
    Route::get('transaksi/loadform/{id}/{total}/{diterima}', [PenjualanDetailController::class, 'loadForm'])->name('transaksi.load_form');
    Route::resource('/transaksi', PenjualanDetailController::class)
        ->except('show');

    Route::get('pengiriman/data', [PengirimanController::class, 'data'])->name('pengiriman.data');
    Route::get('pengiriman/ceknama/{kode}', [PengirimanController::class, 'ceknama'])->name('pengiriman.ceknama');
    Route::put('pengiriman/simpankirim/{kode}', [PengirimanController::class, 'simpankirim'])->name('pengiriman.simpankirim');
    Route::get('pengiriman/printsj/{id}', [PengirimanController::class, 'printsj'])->name('pengiriman.printsj');
    Route::get('pengiriman/detail/bio/{id}', [PengirimanController::class, 'bio'])->name('pengiriman.bio');
    Route::get('pengiriman/detail/data/{id}', [PengirimanController::class, 'detail'])->name('pengiriman.detail');
    Route::resource('/pengiriman', PengirimanController::class);

    Route::get('retur/cekretur/{kode}', [ReturController::class, 'cekretur'])->name('retur.cekretur');
    Route::get('retur/data', [ReturController::class, 'data'])->name('retur.data');
    Route::get('retur/create/{id}', [ReturController::class, 'create'])->name('retur.create');
    Route::resource('retur', ReturController::class)->except('create');

    Route::get('retur_detail/create/{kode}', [ReturDetailController::class, 'create'])->name('retur_detail.create');
    Route::get('retur_detail/data/{id}', [ReturDetailController::class, 'data'])->name('retur_detail.data');
    Route::get('retur_detail/loadform/{total}/{total_lama}', [ReturDetailController::class, 'loadform'])->name('retur_detail.load_form');
    Route::resource('/retur_detail', ReturDetailController::class)
        ->except('create', 'show', 'edit');
});
