<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToDataProdukTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('data_produk', function (Blueprint $table) {
            $table->text('deskripsi_produk')->after('harga_produk')->nullable();
            $table->text('garansi_produk')->after('deskripsi_produk')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('data_produk', function (Blueprint $table) {
            $table->dropColumn('deskripsi_produk');
            $table->dropColumn('garansi_produk');
        });
    }
}
