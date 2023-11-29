<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbTutorialVideos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_tutorial_videos', function (Blueprint $table) {
            $table->id('id_video');
            $table->text('url_video');
            $table->integer('time_created');
            $table->integer('last_update');
            $table->string('status',8);
            $table->integer('id_user');
            $table->string('video_code',32);
            $table->text('title');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_tutorial_videos');
    }
}
