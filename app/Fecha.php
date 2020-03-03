<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fecha extends Model
{
    public function producciones(){
        return $this->hasMany(Produccion::class);
    }

    public function horas(){
        return $this->hasMany(Hora::class);
    }
}
