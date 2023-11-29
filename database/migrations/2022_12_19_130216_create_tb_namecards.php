<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\SoftDeletes;

class CreateTbNamecards extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('new_namecards', function (Blueprint $table) {
            $table->id('id');
            $table->string('full_name');
            $table->string('job_desk');
            $table->string('email');
            $table->string('phone');
            $table->string('address');
           // $table->foreignIdFor(Classname::class)->constrained();
            $table->string('slug');
            $table->timestamps();
            $table->SoftDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('new_namecards');
    }
}
