<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbMicrositeTracker extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_microsite_tracker', function (Blueprint $table) {
            $table->id('id_tracker');
            $table->integer('id_microsite');
            $table->integer();
            $table->string('os');
            $table->string('type_conversion',16);
            $table->string('domain',16)->nullable();
            $table->string('region_code',16)->nullable();
            $table->string('country',16)->nullable();
            $table->text('connection_info')->nullable();
            $table->string('device_info');
            $table->string('info',128)->nullable();
            $table->string('city',64)->nullable();
            $table->string('ip',32);
            $table->string('referrer');
            $table->string('browser');
            $table->string('status',8);
            $table->timestamps('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_microsite_tracker');
    }
}
