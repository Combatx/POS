<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReturDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('retur_detail', function (Blueprint $table) {
            $table->increments('id_retur_detail');
            $table->unsignedInteger('id_retur');
            $table->unsignedInteger('id_produk');
            $table->integer('harga_jual');
            $table->integer('jumlah');
            $table->integer('jumlah_lama');
            $table->integer('subtotal');
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
        Schema::dropIfExists('retur_detail');
    }
}
