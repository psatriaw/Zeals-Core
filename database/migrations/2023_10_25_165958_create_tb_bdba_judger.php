<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_bdba_judger', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('institution');
            $table->string('email');
            $table->string('title');
            $table->string('role');
            $table->text('phone_number');
            $table->string('password');
            $table->string('token');
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
        Schema::dropIfExists('tb_bdba_judger');
    }
};
