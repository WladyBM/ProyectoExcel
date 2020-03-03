<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Hora extends Model
{
    public function fecha(){
        return $this->belongsTo(Fecha::class); //Pertenece a una fecha.
    }

    public function PAD(){
        return $this->belongsTo(Pads::class); //Pertenece a una fecha.
    }
}
