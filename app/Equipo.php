<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Equipo extends Model
{
    public function PAD(){
        return $this->belongsTo(Pad::class); //Pertenece a un PAD.
    }
}
