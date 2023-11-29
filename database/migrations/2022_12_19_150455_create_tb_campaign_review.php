<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbCampaignReview extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_campaign_review', function (Blueprint $table) {
            $table->id('id_review');
            $table->string('status');
            $table->integer('time_created');
            $table->integer('last_update');
            $table->double('nilai');
            $table->integer('id_user');
            $table->integer('id_campaign');
            $table->text('catatan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_campaign_review');
    }
}
