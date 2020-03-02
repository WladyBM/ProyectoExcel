<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Hora extends Model
{
    public function fecha(){ //$libro->categoria->nombre
        return $this->belongsTo(Fecha::class); //Pertenece a una categoría.
    }
}
