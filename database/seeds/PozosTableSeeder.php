<?php

use Illuminate\Database\Seeder;
use App\Fecha;
use App\Pozo;
use App\Produccion;

class PozosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Fecha::truncate();

        $fecha = new Fecha();
        $fecha->nombre = "19-12-19";
        $fecha->save();

        $fecha = new Fecha();
        $fecha->nombre = "19-12-20";
        $fecha->save();

        $fecha = new Fecha();
        $fecha->nombre = "19-12-21";
        $fecha->save();

        //Pozo::truncate();

        $pozo = new Pozo();
        $pozo->nombre = "ARAUCANO_1";
        $pozo->save();

        $pozo->fechas()->attach([1, 2, 3]);

        $pozo = new Pozo();
        $pozo->nombre = "ARAUCANO_ZG-1A";
        $pozo->save();

        $pozo->fechas()->attach([1, 2, 3]);

        $pozo = new Pozo();
        $pozo->nombre = "ARAUCANO_ZG-1B";
        $pozo->save();

        $pozo->fechas()->attach([1, 2, 3]);

        $pozo = new Pozo();
        $pozo->nombre = "ARAUCANO_ZG-1C";
        $pozo->save();

        $pozo->fechas()->attach([1, 2, 3]);

        $produccion = new Produccion();
        $produccion->cantidad = 1;
        $produccion->pozo_id=1;
        $produccion->save();

        $produccion = new Produccion();
        $produccion->cantidad = 2;
        $produccion->pozo_id=2;
        $produccion->save();

        $produccion = new Produccion();
        $produccion->cantidad = 3;
        $produccion->pozo_id=3;
        $produccion->save();

        $produccion = new Produccion();
        $produccion->cantidad = 10;
        $produccion->pozo_id=4;
        $produccion->save();

    }
}
