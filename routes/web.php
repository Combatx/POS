<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\SatuanController;
use App\Http\Controllers\SupplierController;
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

    Route::get('/kategori/data', [KategoriController::class, 'data'])->name('kategori.data');
    Route::resource('/kategori', KategoriController::class);

    Route::get('/satuan/data', [SatuanController::class, 'data'])->name('satuan.data');
    Route::resource('/satuan', SatuanController::class);

    Route::get('/supplier/data', [SupplierController::class, 'data'])->name('supplier.data');
    Route::resource('/supplier', SupplierController::class);

    Route::get('/produk/data', [ProdukController::class, 'data'])->name('produk.data');
    Route::resource('/produk', ProdukController::class);
});
