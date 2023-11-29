<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbRupsAttendance extends Migration
{
    //TIDAK DIGUNAKAN 
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_rups_attendance', function (Blueprint $table) {
            $table->id('id_attendance');
            $table->integer('id_rups');
            $table->integer('time_created');
            $table->integer('last_update');
            $table->string('status',16);
            $table->integer('id_user');
            $table->string('persetujuan',8);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_rups_attendance');
    }
}
