<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbCartDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_cart_detail', function (Blueprint $table) {
            $table->id('id_cart_detail');
            $table->integer('id_cart');
            $table->integer('id_user');
            $table->integer('time_created');
            $table->integer('last_update');
            $table->integer('id_campaign_product');
            $table->integer('quantity');
            $table->string('status');
            $table->double('harga_beli');
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
        Schema::dropIfExists('tb_cart_detail');
    }
}
