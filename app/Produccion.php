<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Produccion extends Model
{
    public function fecha(){
        return $this->belongsTo(Fecha::class); //Pertenece a una fecha.
    }
}