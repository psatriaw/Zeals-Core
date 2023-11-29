<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbDepartement extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_departement', function (Blueprint $table) {
            $table->id('id_departement');
            $table->string('name',256);
            $table->integer('time_created');
            $table->integer('last_update');
            $table->string('status');
            $table->string('default');
            $table->string('departement_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_departement');
    }
}
