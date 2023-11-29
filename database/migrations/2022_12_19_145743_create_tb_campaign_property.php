<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableCampaignProperty extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_campaign_property', function (Blueprint $table) {
            $table->id('id_property');
            $table->integer('time_created');
            $table->integer('last_update');
            $table->string('status',8);
            $table->integer('id_campaign');
            $table->string('property_type',16);
            $table->text('value');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('campaign_property');
    }
}
