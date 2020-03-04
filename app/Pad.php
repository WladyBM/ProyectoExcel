<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pad extends Model
{
    public function equipos(){
        return $this->belongsToMany(Equipo::class); //Mucho a mucho
    }
}
