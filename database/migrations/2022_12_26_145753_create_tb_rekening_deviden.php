<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbRekeningDeviden extends Migration
{
    //TIDAK DIGUNAKAN 
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_rekening_deviden', function (Blueprint $table) {
            $table->id('id_detail_deviden');
            $table->integer('time_created');
            $table->integer('last_update');
            $table->string('status',8);
            $table->integer('id_campaign');
            $table->integer('id_user');
            $table->date('deviden_date');
            $table->double('amount');
            $table->string('deviden_code',16);
            $table->integer('id_penerbit');
            $table->integer('id_deviden');
            $table->integer('id_rups');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_rekening_deviden');
    }
}
