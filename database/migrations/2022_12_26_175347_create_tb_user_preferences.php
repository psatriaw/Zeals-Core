<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbUserPreferences extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_user_preferences', function (Blueprint $table) {
            $table->id('id_preferences');
            $table->integer('time_created');
            $table->integer('last_update');
            $table->string('status',8);
            $table->integer('id_user');
            $table->integer('id_sektor_industri');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_user_preferences');
    }
}
