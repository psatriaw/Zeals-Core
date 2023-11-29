<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbPage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_page', function (Blueprint $table) {
            $table->id('id_page');
            $table->string('page_code',64);
            $table->text('content');
            $table->integer('time_created');
            $table->integer('last_update');
            $table->text('title');
            $table->text('keyword');
            $table->string('status',16);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_page');
    }
}
