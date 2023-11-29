<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\SoftDeletes;

class CreateTbCampaignComponent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('new_campaign_component', function (Blueprint $table) {
            $table->id('id_component');
            $table->integer('id_campaign');
            $table->string('input_type',100);
            $table->string('input_resource',100);
            $table->string('field_name',100);
            $table->string('rules',100);
            $table->string('status',100);
            $table->integer('is_deleted');
            $table->timestamps();
            $table->SoftDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('new_campaign_component');
    }
}
