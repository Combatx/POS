<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipsToPenjualanDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('penjualan_detail', function (Blueprint $table) {
            $table->foreign('id_penjualan')
                ->references('id_penjualan')
                ->on('penjualan')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreign('id_produk')
                ->references('id_produk')
                ->on('produk')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreign('id_pengiriman')
                ->references('id_pengiriman')
                ->on('pengiriman')
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
        Schema::table('penjualan_detail', function (Blueprint $table) {
            $table->dropForeign('penjualan_detail_id_penjualan_foreign');
            $table->dropForeign('penjualan_detail_id_produk_foreign');
            $table->dropForeign('penjualan_detail_id_pengiriman_foreign');
        });
    }
}
