<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbSahamJual extends Migration
{
    //TIDAK DIGUNAKAN 
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_saham_jual', function (Blueprint $table) {
            $table->id('id_jual');
            $table->integer('time_created');
            $table->integer('last_update');
            $table->string('status',8);
            $table->integer('id_user');
            $table->integer('id_campaign');
            $table->integer('quantity');
            $table->double('harga_jual');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_saham_jual');
    }
}
