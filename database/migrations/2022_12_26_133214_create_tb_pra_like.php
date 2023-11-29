<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbPraLike extends Migration
{
    //TIDAK DIGUNAKAN 
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_pra_like', function (Blueprint $table) {
            $table->id('id_like');
            $table->integer('id_user');
            $table->integer('time_created');
            $table->integer('last_update');
            $table->string('status',16);
            $table->integer('id_penerbit');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_pra_like');
    }
}
