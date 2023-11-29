<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbRekeningDana extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_rekening_dana', function (Blueprint $table) {
            $table->id('id_rekening_dana');
            $table->integer('id_user');
            $table->integer('time_created');
            $table->integer('last_update');
            $table->string('status',16);
            $table->integer('saldo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_rekening_dana');
    }
}
