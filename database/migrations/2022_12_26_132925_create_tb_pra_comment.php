<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbPraComment extends Migration
{
    //TIDAK DIGUNAKAN 
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_pra_comment', function (Blueprint $table) {
            $table->id('id_comment');
            $table->integer('time_created');
            $table->integer('last_update');
            $table->string('status');
            $table->integer('id_penerbit');
            $table->text('content');
            $table->integer('id_user');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_pra_comment');
    }
}
