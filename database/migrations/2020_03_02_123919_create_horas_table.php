<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHorasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('horas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->Integer('hora');
            $table->unsignedBigInteger('pozo_id');
            $table->foreign('pozo_id')->references('id')->on('pozos'); //foranea tabla pozos
            $table->unsignedBigInteger('fecha_id');
            $table->foreign('fecha_id')->references('id')->on('fechas'); //foranea tabla fecha
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
        Schema::dropIfExists('horas');
    }
}
