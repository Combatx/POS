<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBarangKeluarDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('barang_keluar_detail', function (Blueprint $table) {
            $table->increments('id_barang_keluar_detail');
            $table->unsignedInteger('id_barang_keluar');
            $table->unsignedInteger('id_produk');
            $table->integer('harga_beli')->default(0);
            $table->integer('jumlah')->default(0);
            $table->integer('subtotal')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('barang_keluar_detail');
    }
}
