<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipToReturTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('retur', function (Blueprint $table) {
            $table->foreign('id_penjualan')
                ->references('id_penjualan')
                ->on('penjualan')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreign('id_user')
                ->references('id')
                ->on('users')
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
        Schema::table('retur', function (Blueprint $table) {
            $table->dropForeign('retur_id_penjualan_foreign');
            $table->dropForeign('retur_id_user_foreign');
        });
    }
}
