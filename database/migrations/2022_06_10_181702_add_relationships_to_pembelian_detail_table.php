<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipsToPembelianDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pembelian_detail', function (Blueprint $table) {
            $table->foreign('id_pembelian')
                ->references('id_pembelian')
                ->on('pembelian')
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
        Schema::table('pembelian_detail', function (Blueprint $table) {
            $table->dropForeign('pembelian_detail_id_pembelian_foreign');
            $table->dropForeign('pembelian_detail_id_produk_foreign');
        });
    }
}
