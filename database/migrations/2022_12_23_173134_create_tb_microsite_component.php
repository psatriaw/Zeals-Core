<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbMicrositeComponent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_microsite_component', function (Blueprint $table) {
            $table->integer('id_microsite');
            $table->id('id_component');
            $table->integer('is_deleted');
            $table->string('input_type',128);
            $table->string('input_source',128);
            $table->string('field_name',64);
            $table->string('rules',128);
            $table->string('status');
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
        Schema::dropIfExists('tb_microsite_component');
    }
}
