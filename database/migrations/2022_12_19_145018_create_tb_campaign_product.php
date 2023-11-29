<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbCampaignProduct extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_campaign_product', function (Blueprint $table) {
            $table->id('id_campaign_product');
            $table->integer('id_campaign');
            $table->integer('id_product');
            $table->integer('time_created');
            $table->integer('minimum_time_buyback');
            $table->double('minimum_buyback');
            $table->double('maximum_buyback');
            $table->double('deviden');
            $table->string('deviden_type');
            $table->string('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_campaign_product');
    }
}
