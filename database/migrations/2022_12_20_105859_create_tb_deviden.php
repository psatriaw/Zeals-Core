<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbDepartementDeviden extends Migration
{
    //TIDAK DIGUNAKAN 
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_deviden', function (Blueprint $table) {
            $table->id('id_deviden');
            $table->integer('last_update');
            $table->integer('time_created');
            $table->integer('deviden_month');
            $table->integer('deviden_year');
            $table->string('invoice_code',16);
            $table->integer('id_author');
            $table->string('status',8);
            $table->double('total_charge');
            $table->string('data_callback');
            $table->string('data_response');
            $table->integer('id_campaign');
            $table->double('keuntungan');
            $table->integer('id_rups');
            $table->double('pajak_total');
            $table->double('fee_pajak');
            $table->string('pajak_type',8);
            $table->string('fee_type',8);
            $table->double('total_tagihan');
            $table->double('besar_deviden');
            $table->string('id_payment');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_departement_deviden');
    }
}
