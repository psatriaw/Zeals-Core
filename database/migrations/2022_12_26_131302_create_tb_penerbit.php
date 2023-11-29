<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbPenerbit extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_penerbit', function (Blueprint $table) {
            $table->id('id_penerbit');
            $table->string('nama_penerbit',128);
            $table->string('kode_penerbit',64);
            $table->text('alamat');
            $table->string('no_telp',16);
            $table->integer('time_created');
            $table->integer('last_update');
            $table->string('status',16);
            $table->string('siup',64);
            $table->text('nib');
            $table->string('pic_name',64);
            $table->string('pic_telp',64);
            $table->integer('id_sektor_industri');
            $table->text('photos');
            $table->double('longitude');
            $table->double('latitude');
            $table->text('about');
            $table->integer('id_user');
            $table->string('email',128);
            $table->text('nib_file');
            $table->text('pbb_file');
            $table->text('neraca_file');
            $table->text('pos_file');
            $table->text('rab_file');
            $table->text('proyeksi_file');
            $table->text('photo_video_file');
            $table->text('prospektus_file');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_penerbit');
    }
}
