<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbOutlet extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_outlet', function (Blueprint $table) {
            $table->id('id_outlet');
            $table->integer('id_campaign');
            $table->integer('time_created');
            $table->integer('last_update');
            $table->string('status');
            $table->string('outlet_name',128);
            $table->text('outlet_address');
            $table->string('outlet_phone',16);
            $table->integer('max_redemption');
            $table->integer('max_redemption_per_day');
            $table->string('outlet_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_outlet');
    }
}
