<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbPayment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_payment', function (Blueprint $table) {
            $table->id('id_payment');
            $table->integer('payment_time');
            $table->integer('time_created');
            $table->integer('last_update');
            $table->double('total_amount');
            $table->double('total_pajak');
            $table->double('total_fee');
            $table->string('fee_type',16);
            $table->string('pajak_type',16);
            $table->string('bank_account',32);
            $table->string('bank_account_number',64);
            $table->string('bank_account_name',128);
            $table->text('trx_callback');
            $table->string('trx_status',32);
            $table->string('invoice_code',32);
            $table->text('description');
            $table->string('trx_sign',16);
            $table->string('trx_type',16);
            $table->text('trx_response');
            $table->string('va_number',32);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_payment');
    }
}
