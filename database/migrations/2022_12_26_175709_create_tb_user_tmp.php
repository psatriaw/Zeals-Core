<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbUserTmp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_user_tmp', function (Blueprint $table) {
            $table->increments('id_member');
            $table->string('name',128);
            $table->string('email',64);
            $table->string('no_rek',32);
            $table->string('account_bank_name',128);
            $table->string('bank_code',8);
            $table->string('password',64);
            $table->string('address',256);
            $table->tinyInteger('status');
            $table->string('member_key',32);
            $table->string('facebook_account_id',64);
            $table->string('twitter_account_id',64);
            $table->string('google.account_id',64);
            $table->string('no_telp',32);
            $table->date('birth_date');
            $table->integer('id_location');
            $table->tinyInteger('sex');
            $table->tinyInteger('education');
            $table->tinyInteger('marital_status');
            $table->string('job',128);
            $table->tinyInteger('salary');
            $table->tinyInteger('have_children');
            $table->tinyInteger('is_hapus');
            $table->date('join_date');
            $table->dateTime('last_login');
            $table->dateTime('last_update');
            $table->string('avatar',256);
            $table->integer('notif_email');
            $table->integer('notif_push');
            $table->integer('skip_intro_email');
            $table->string('affiliate_code',32);
            $table->string('instagra_username',30);
            $table->dateTime('last_request');
            $table->string('referral_code',16);
            $table->tinyInteger('id_job');
            $table->string('longitude',30);
            $table->string('latitude',30);
            $table->text('domisili');
            $table->string('npwp',64);
            $table->integer('invited');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_user_tmp');
    }
}
