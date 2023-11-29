<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableCampaignLink extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_campaign_unique_link', function (Blueprint $table) {
            $table->id('id_unique_link');
            $table->integer('time_created');
            $table->integer('last_update');
            $table->integer('id_user');
            $table->integer('id_campaign');
            $table->string('unique_link',128);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('campaign_link');
    }
}
