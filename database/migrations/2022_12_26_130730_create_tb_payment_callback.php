<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbPaymentCallback extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_payment_callback', function (Blueprint $table) {
            $table->id('id');
            $table->timestamps('created_at');
            $table->timestamps('updated_at');
            $table->text('json_callback');
            $table->string('provider',16);
            $table->string('status',16);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_payment_callback');
    }
}
