<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbWilayah extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_wilayah', function (Blueprint $table) {
            $table->id('id_wilayah');
            $table->string('kodeprov',8);
            $table->string('namaprov',128);
            $table->string('kodekab',8);
            $table->string('namalab',128);
            $table->string('map_code',16);
            $table->string('status',8);
        }); 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_wilayah');
    }
}
