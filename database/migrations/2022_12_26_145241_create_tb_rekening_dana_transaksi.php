<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbRekeningDanaTransaksi extends Migration
{
    //TIDAK DIGUNAKAN 
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_rekening_dana_transaksi', function (Blueprint $table) {
            $table->id('id_transaksi');
            $table->integer('time_created');
            $table->integer('last_update');
            $table->text('description');
            $table->double('debit');
            $table->double('kredit');
            $table->string('status');
            $table->integer('id_rekening_dana');
            $table->integer('id_user');
            $table->integer('id_author');
            $table->string('trx_code',32);
            $table->text('trx_callback');
            $table->text('trx_request');
            $table->string('va_code',32);
            $table->double('trx_amount');
            $table->string('trx_type',12);
            $table->integer('reff_id');
            $table->string('trx_action',16);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_rekening_dana_transaksi');
    }
}
