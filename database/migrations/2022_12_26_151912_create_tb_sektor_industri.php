<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbSektorIndustri extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_sektor_industri', function (Blueprint $table) {
            $table->id('id_sektor_industri');
            $table->string('nama_sektor_industri',128);
            $table->integer('time_created');
            $table->integer('last_update');
            $table->string('status');
            $table->string('icon',100);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_sektor_industri');
    }
}
