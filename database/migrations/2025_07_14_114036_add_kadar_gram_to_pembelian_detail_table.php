<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKadarGramToPembelianDetailTable extends Migration
{
    public function up()
    {
        Schema::table('pembelian_detail', function (Blueprint $table) {
            // Menambahkan kolom setelah id_produk
            $table->string('kadar')->after('id_produk')->nullable();
            $table->decimal('gram', 8, 3)->after('kadar')->nullable();
        });
    }

    public function down()
    {
        Schema::table('pembelian_detail', function (Blueprint $table) {
            $table->dropColumn(['kadar', 'gram']);
        });
    }
}