<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHoraPadTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hora_pad', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedbigInteger('hora_id');
            $table->foreign('hora_id')->references('id')->on('horas');
            $table->unsignedbigInteger('pad_id');
            $table->foreign('pad_id')->references('id')->on('pads');
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
        Schema::dropIfExists('hora_pad');
    }
}
