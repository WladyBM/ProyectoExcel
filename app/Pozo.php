<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pozo extends Model
{
    public function producciones(){
        return $this->hasMany(Produccion::class);
    }
}
