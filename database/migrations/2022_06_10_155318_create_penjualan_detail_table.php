<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePenjualanDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('penjualan_detail', function (Blueprint $table) {
            $table->increments('id_penjualan_detail');
            $table->unsignedInteger('id_penjualan');
            $table->unsignedInteger('id_produk');
            $table->unsignedInteger('id_pengiriman')
                ->nullable();
            $table->integer('harga_jual');
            $table->integer('jumlah');
            $table->integer('diskon');
            $table->integer('subtotal');
            $table->enum('dikirim', ['ya', 'tidak'])->default('tidak');
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
        Schema::dropIfExists('penjualan_detail');
    }
}
