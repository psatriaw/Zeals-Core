<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbRups extends Migration
{
    //TIDAK DIGUNAKAN 
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_rups', function (Blueprint $table) {
            $table->id('id_rups');
            $table->integer('time_created');
            $table->integer('last_update');
            $table->date('tanggal_rups');
            $table->text('keputusan');
            $table->text('agenda');
            $table->double('keuntungan');
            $table->double('besar_pembagian');
            $table->integer('id_penerbit');
            $table->integer('id_campaign');
            $table->string('status',16);
            $table->string('jam_rups',8);
            $table->string('rups_code',64);
            $table->string('video_link',256);
            $table->string('notif_email',4);
            $table->string('notif_push',4);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_rups');
    }
}
