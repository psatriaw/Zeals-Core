<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbCustodian extends Migration
{
    //TIDAK DIGUNAKAN 
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_custodian', function (Blueprint $table) {
            $table->id('id_custodian');
            $table->string('file_name',256);
            $table->string('file_path',256);
            $table->string('type',128);
            $table->string('status',128);
            $table->integer('time_created');
            $table->integer('last_update');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_custodian');
    }
}
