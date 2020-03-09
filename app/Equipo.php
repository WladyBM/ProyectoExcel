<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Equipo extends Model
{
    public function pads(){
        return $this->belongsToMany(Pad::class)->withPivot('id');
    }
}
