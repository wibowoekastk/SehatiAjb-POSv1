<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKadarAndGramToProdukTable extends Migration
{
    public function up()
    {
        Schema::table('produk', function (Blueprint $table) {
            // Menambahkan kolom 'kadar' setelah kolom 'nama_produk'
            $table->string('kadar')->after('nama_produk')->nullable()->comment('Kadar emas, cth: 24K, 75%');

            // Menambahkan kolom 'gram' dengan tipe data decimal untuk presisi
            $table->decimal('gram', 8, 3)->after('kadar')->nullable()->comment('Berat emas dalam gram');
        });
    }

    public function down()
    {
        Schema::table('produk', function (Blueprint $table) {
            $table->dropColumn(['kadar', 'gram']);
        });
    }
}