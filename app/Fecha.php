<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fecha extends Model
{
    
    public function producciones(){ //$libro->categoria->nombre
        return $this->hasMany(Produccion::class);
    }
}
