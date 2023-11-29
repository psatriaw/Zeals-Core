<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_campaign_bookmark', function (Blueprint $table) {
            $table->id('id_bookmark');
            $table->integer('time_created');
            $table->integer('last_update');
            $table->integer('id_user');
            $table->integer('id_campaign');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_campaign_bookmark');
    }
};
