<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableCampaignTracker extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_campaign_tracker', function (Blueprint $table) {
            $table->id('id_tracker');
            $table->integer('time_created');
            $table->integer('last_update');
            $table->string('unique_link',32);
            $table->integer('id_campaign');
            $table->integer('id_user');
            $table->string('ip');
            $table->text('browser');
            $table->date('date');
            $table->string('status',8);
            $table->double('commission');
            $table->integer('id_program');
            $table->text('referrer');
            $table->text('os');
            $table->text('device_info');
            $table->double('fee');
            $table->string('type_conversion',16);
            $table->string('encrypted_code',32);
            $table->text('callback_url');
            $table->string('protocol',16)->nullable();
            $table->string('domain',128)->nullable();
            $table->string('info',128)->nullable();
            $table->string('city',64)->nullable();
            $table->string('region_code',16)->nullable();
            $table->text('connection_info')->nullable();
            $table->string('country',16)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('campaign_tracker');
    }
}
