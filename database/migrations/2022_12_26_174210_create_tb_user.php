<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_user', function (Blueprint $table) {
            $table->id('id_user');
            $table->string('first_name',64);
            $table->string('last_name',32);
            $table->string('username',32);
            $table->string('password',64);
            $table->string('email',64);
            $table->integer('date_created');
            $table->string('affiliate_code',32);
            $table->text('address');
            $table->string('status',8);
            $table->string('phone',32);
            $table->string('activation_code',16);
            $table->integer('last_update');
            $table->text('avatar');
            $table->string('level',16);
            $table->integer('id_department');
            $table->integer('is_kyc_stored');
            $table->text('push_id');
            $table->string('otp_code',4);
            $table->integer('is_exported');
            $table->integer('passcode');
            $table->string('nama_bank',32);
            $table->string('nama_pemilik_rekening',128);
            $table->string('nomor_rekening',32);
            $table->integer('id_job');
            $table->integer('id_wilayah');
            $table->string('google_id',128);
            $table->string('referral_code',32);
            $table->integer('id_brand');
            $table->date('dob');
            $table->string('gender',16);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_user');
    }
}
