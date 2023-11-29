<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableCampaignProgram extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_campaign_program', function (Blueprint $table) {
            $table->id('id_program');
            $table->integer('id_campaign');
            $table->integer('time_created');
            $table->integer('last_update');
            $table->string('type_program',16);
            $table->string('status',8);
            $table->double('commission');
            $table->double('fee');
            $table->integer('total_item');
            $table->string('reff_code',32);
            $table->text('custom_link');
            $table->string('type',8);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('campaign_program');
    }
}
