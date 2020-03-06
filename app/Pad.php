<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pad extends Model
{
    public function equipos(){
        return $this->belongsToMany(Equipo::class); //Mucho a mucho
    }

    public function horas(){
        return $this->belongsToMany(Hora::class); //Mucho a mucho
    }
}
