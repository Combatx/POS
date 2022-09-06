<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipToBarangKeluarDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('barang_keluar_detail', function (Blueprint $table) {
            $table->foreign('id_barang_keluar')
                ->references('id_barang_keluar')
                ->on('barang_keluar')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreign('id_produk')
                ->references('id_produk')
                ->on('produk')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('barang_keluar_detail', function (Blueprint $table) {
            $table->dropForeign('barang_keluar_detail_id_barang_keluar_foreign');
            $table->dropForeign('barang_keluar_detail_id_produk_foreign');
        });
    }
}
