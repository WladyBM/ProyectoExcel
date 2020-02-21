<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use App;


class ExcelController extends Controller
{
    public function ImportarExcel(Request $request){
        
        $this->validate($request, [
            'archivo' => 'required|mimes:xlsx'
        ]);

        $path = $request->file('archivo')->getRealPath();
        $Libro = IOFactory::load($path);
        
        for ($i = 12; $i<500; $i++){
            if ($Libro->getSheetByName('Detalle Pozos')->getCellByColumnAndRow(5, $i)->getValue() =='TOTAL BLOQUE :'){
            $final = $i-1;
            break;
            }
        }

        $dato = array();
        
        $Fechas = new App\Fecha;
        $Fechas->nombre = Date::excelToDateTimeObject($Libro->getSheetByName('Detalle Pozos')->getCellByColumnAndRow(7, 5)->getValue());
        $Fechas->save();


        for ($i = 12; $i<$final; $i++){

            $dato[] = $Libro->getSheetByName('Detalle Pozos')->getCellByColumnAndRow(5, $i)->getValue();
            $nombre = App\Pozo::where('nombre','=', $Libro->getSheetByName('Detalle Pozos')->getCellByColumnAndRow(5, $i)->getValue())->first();
            if($nombre == null){
                
                $Pozos = new App\Pozo;
                $Pozos->nombre = $Libro->getSheetByName('Detalle Pozos')->getCellByColumnAndRow(5, $i)->getValue();
                $Pozos->save();

                $Pozos->fechas()->attach([$Fechas->id]);

                $Produccion = new App\Produccion;
                $Produccion->cantidad = $Libro->getSheetByName('Detalle Pozos')->getCellByColumnAndRow(21, $i)->getValue();
                $Produccion->pozo_id = $Pozos->id;
                $Produccion->save();

                $fechita = App\Fecha::all();
                if(count($fechita) > 1){
                    foreach($fechita as $fecha){
                        if($fecha->id == $Fechas->id){

                        }else{
                            $Pozos->fechas()->attach([$fecha->id]);

                            $Produccion = new App\Produccion;
                            $Produccion->cantidad = 0;
                            $Produccion->pozo_id = $Pozos->id;
                            $Produccion->save();
                        }
                    }
                }

            }else{
                $nombre->fechas()->attach([$Fechas->id]);
                $Produccion = new App\Produccion;
                $Produccion->cantidad = $Libro->getSheetByName('Detalle Pozos')->getCellByColumnAndRow(21, $i)->getValue();
                $Produccion->pozo_id = $nombre->id;
                $Produccion->save();
                
            }
        }
        
        //$dato = App\Pozos::all();
        // return view('vista', compact('dato'));
        
        $pocito = App\Pozo::all();

        foreach($pocito as $pozo){
            if(in_array($pozo->nombre, $dato)){

            }else{
                $pozo->fechas()->attach([$Fechas->id]);
                
                $Produccion = new App\Produccion;
                $Produccion->cantidad = 0;
                $Produccion->pozo_id = $pozo->id;
                $Produccion->save();
            }
        }

        $pozos = App\Pozo::orderBy('nombre')->paginate(100);
        $fechas = App\Fecha::all();
        $producciones = App\Produccion::all();

        return view('verexcel', compact('pozos','fechas'));
    }

    public function VerExcel(){

        //$fechas[]=DB::table('fechas')->select('nombre')->get();
        $pozos = App\Pozo::orderBy('nombre')->paginate(100);
        $fechas = App\Fecha::all();
        $producciones = App\Produccion::all();
        
        return view('verexcel', compact('pozos','fechas'));
    }
}