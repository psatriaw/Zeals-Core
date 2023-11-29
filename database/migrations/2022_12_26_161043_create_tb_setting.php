<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbSetting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_setting', function (Blueprint $table) {
            $table->id('id_setting');
            $table->text('setting_value');
            $table->string('code_setting',32);
            $table->integer('time_created');
            $table->integer('last_update');
            $table->string('description',128);
            $table->integer('order_number');
            $table->integer('id_order');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_setting');
    }
}
