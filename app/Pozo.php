<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pozo extends Model
{
    public function fechas(){
        return $this->belongsToMany(Fecha::class); //Relacion n - n
    }

    public function producciones(){
        return $this->hasMany(Produccion::class);
    }
}
