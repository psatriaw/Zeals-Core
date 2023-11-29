<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbCampaignComponent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_campaign_component', function (Blueprint $table) {
            $table->id('id_component');
            $table->integer('id_campaign');
            $table->integer('is_deleted')->default(0);
            $table->string('input_type');
            $table->string('input_source');
            $table->string('field_name');
            $table->string('rules');
            $table->string('status');
            $table->text('notes');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_campaign_component');
    }
}
