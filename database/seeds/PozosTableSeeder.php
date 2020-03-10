<?php

use Illuminate\Database\Seeder;
use App\Pad;
use App\Equipo;

class PozosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Pad::truncate();

        $pad = new Pad;
        $pad->nombre = 'ARAUCANO-1';
        $pad->save();

        $pad = new Pad;
        $pad->nombre = 'ARAUCANO_ZG-1';
        $pad->save();
        
        $pad = new Pad;
        $pad->nombre = 'CABANA_ESTE_ZG-1';
        $pad->save();
        
        $pad = new Pad;
        $pad->nombre = 'CABANA_ESTE_ZG-2';
        $pad->save();

        $pad = new Pad;
        $pad->nombre = 'CABANA_OESTE-1';
        $pad->save();

        $pad = new Pad;
        $pad->nombre = 'CABANA_OESTE-2';
        $pad->save();

        $pad = new Pad;
        $pad->nombre = 'CABANA_OESTE_ZG-1';
        $pad->save();

        $pad = new Pad;
        $pad->nombre = 'CABANA_OESTE_ZG-2';
        $pad->save();

        $pad = new Pad;
        $pad->nombre = 'CABANA_OESTE_ZG-3';
        $pad->save();

        $equipo = new Equipo;
        $equipo->nombre = 'URG Q.B.JOHNSON (24"OD"10)';
        $equipo->consumo = 179;
        $equipo->save();

        $equipo = new Equipo;
        $equipo->nombre = 'Motogenerador Ford PG22';
        $equipo->consumo = 197;
        $equipo->save();
        
        $equipo = new Equipo;
        $equipo->nombre = 'URG 500,000 BTU';
        $equipo->consumo = 447;
        $equipo->save();
        
        $equipo = new Equipo;
        $equipo->nombre = 'Generador OLYMPIAN (G45LG1)';
        $equipo->consumo = 197;
        $equipo->save();
    }
}
