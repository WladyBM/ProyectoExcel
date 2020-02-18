<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFechaPozoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fecha_pozo', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('pozo_id');
            $table->unsignedInteger('fecha_id');
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
        Schema::dropIfExists('fecha_pozo');
    }
}
