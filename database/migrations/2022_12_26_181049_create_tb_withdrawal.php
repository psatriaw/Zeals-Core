<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbWithdrawal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_withdrawal', function (Blueprint $table) {
            $table->id('id_withdrawal');
            $table->integer('id_user');
            $table->string('nomor_rekening',32);
            $table->string('nama_bank',64);
            $table->string('nama_pemilik_rekening',128);
            $table->integer('time_created');
            $table->integer('last_update');
            $table->string('status',16);
            $table->integer('totla_pencairan');
            $table->text('callback_response');
            $table->string('trx_status',32);
            $table->string('withdrawal_code',16);
            $table->double('fee');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_withdrawal');
    }
}
