<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbProduk extends Migration
{
    
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_produk', function (Blueprint $table) {
            $table->id('id_produk');
            $table->string('kode_produk',32);
            $table->string('nama_produk',128);
            $table->integer('time_created');
            $table->integer('last_update');
            $table->string('status',16);
            $table->string('sumber_pembagian_deviden',32);
            $table->text('hak_suara');
            $table->text('resiko_investasi');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_produk');
    }
}
