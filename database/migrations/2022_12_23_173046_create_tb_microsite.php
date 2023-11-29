<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbMicrosite extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_microsite', function (Blueprint $table) {
            $table->id('id_microsite');
            $table->integer('id_penerbit');
            $table->integer('is_deleted');
            $table->integer('created_at');
            $table->integer('updated_at');
            $table->string('nama_microsite',128);
            $table->string('status',8);
            $table->text('notes');
            $table->string('banner',128);
            $table->string('keyword',128);
            $table->string('ccs_source',64);
            $table->timestamps('start_date');
            $table->timestamps('end_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_microsite');
    }
}
