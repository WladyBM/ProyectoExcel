<?php

use Illuminate\Database\Seeder;
use App\Pad;

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

    }
}
