<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEquipoPadTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equipo_pad', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedbigInteger('equipo_id');
            $table->foreign('equipo_id')->references('id')->on('equipos');
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
        Schema::dropIfExists('equipo_pad');
    }
}
