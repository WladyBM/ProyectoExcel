<?php

use Illuminate\Database\Seeder;
use App\Fecha;
use App\Pozo;

class PozosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Fecha::truncate();

        $fecha = new Fecha();
        $fecha->nombre = "19-12-19";
        $fecha->save();

        $fecha = new Fecha();
        $fecha->nombre = "19-12-20";
        $fecha->save();

        Pozo::truncate();

        $pozo = new Pozo();
        $pozo->nombre = "ARAUCANO_1";
        $pozo->produccion = 0;
        $pozo->save();

        $pozo->fechas()->attach([1, 2]);

        $pozo = new Pozo();
        $pozo->nombre = "ARAUCANO_ZG-1A";
        $pozo->produccion = 6.307;
        $pozo->save();

        $pozo->fechas()->attach([1, 2]);

        $pozo = new Pozo();
        $pozo->nombre = "ARAUCANO_ZG-1B";
        $pozo->produccion = 7.066;
        $pozo->save();

        $pozo->fechas()->attach([1, 2]);

    }
}
