<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\SoftDeletes;


class CreateTableCampaign extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('new_campaign', function (Blueprint $table) {
            $table->id();
            $table->integer('id_penerbit');
            $table->string('campaign_title',128);
            $table->text('campaign_description')->nullable();
            $table->string("status",16)->default('close');
            $table->date('start_date');
            $table->date('end_date');
            $table->double('target_fund')->default(0);
            $table->string('campaign_internal',32);
            $table->double('budget')->default(0);
            $table->double('max_commission')->default(0);
            $table->text('photos');
            $table->text('disclaimer');
            $table->text('prospektus');
            $table->string('campaign_type',128);
            $table->string('aditional_1',128);
            $table->string('running_status',16);
            $table->text('campaign_do_n_dont')->nullable();
            $table->text('campaign_instruction')->nullable();
            $table->string('campaign_link',128);
            $table->text('landing_url')->nullable();
            $table->string('campaign_random_code',32)->nullable();
            $table->string('affiliate_id',16)->nullable();
            $table->string('tipe_url',16);
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
        Schema::dropIfExists('new_campaign');
    }
}
