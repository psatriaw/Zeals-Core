<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbPenerbitReport extends Migration
{
    //TIDAK DIGUNAKAN 
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_penerbit_report', function (Blueprint $table) {
            $table->id('id_report');
            $table->integer('id_campaign');
            $table->integer('time_created');
            $table->integer('last_update');
            $table->date('report_date');
            $table->string('report_code',12);
            $table->integer('id_user');
            $table->string('status',8);
            $table->integer('id_penerbit');
            $table->integer('report_month');
            $table->integer('report_year');
            $table->text('catatan');
            $table->text('file_path');
            $table->double('profit');
            $table->string('calculated',8);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_penerbit_report');
    }
}
