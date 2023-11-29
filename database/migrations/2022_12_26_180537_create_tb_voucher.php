<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbVoucher extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_voucher', function (Blueprint $table) {
            $table->id('id_voucher');
            $table->integer('time-created');
            $table->integer('last_update');
            $table->string('status',16);
            $table->string('voucher_code',16);
            $table->integer('time_usage');
            $table->string('optin_name',64);
            $table->string('optin_email',128);
            $table->string('optin_phone',16);
            $table->text('optin_address');
            $table->integer('id_outlet_usage');
            $table->integer('id_tracker');
            $table->integer('id_campaign');
            $table->string('additional_1',128);
            $table->text('disclaimer');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_voucher');
    }
}
