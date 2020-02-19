<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fecha extends Model
{
    public function pozos(){
        return $this->belongsToMany(Pozo::class); //Relacion n - n
    }
}
