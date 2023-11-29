<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbSaham extends Migration
{
    //TIDAK DIGUNAKAN 
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_saham', function (Blueprint $table) {
            $table->id('id_saham');
            $table->integer('id_cart');
            $table->integer('id_user');
            $table->integer('time_created');
            $table->integer('last_update');
            $table->string('status');
            $table->double('nilai_beli');
            $table->double('quantity');
            $table->integer('id_custodian_record');
            $table->text('custodian_record_callback');
            $table->integer('id_campaign');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_saham');
    }
}
