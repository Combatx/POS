<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipToReturDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('retur_detail', function (Blueprint $table) {
            $table->foreign('id_retur')
                ->references('id_retur')
                ->on('retur')
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
        Schema::table('retur_detail', function (Blueprint $table) {
            $table->dropForeign('retur_id_retur_foreign');
            $table->dropForeign('retur_id_produk_foreign');
        });
    }
}
