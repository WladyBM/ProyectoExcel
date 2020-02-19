<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use App;
use DB;


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
        
        $Fechas = new App\Fecha;
        $Fechas->nombre = Date::excelToDateTimeObject($Libro->getSheetByName('Detalle Pozos')->getCellByColumnAndRow(7, 5)->getValue());
        $Fechas->save();


        for ($i = 12; $i<$final; $i++){
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

            }else{
                dd('si hay');
                
            }
        }

        //$dato = App\Pozos::all();
       // return view('vista', compact('dato'));
    }

    public function VerExcel(){
        $dato = array();

        //$fechas[]=DB::table('fechas')->select('nombre')->get();
        $pozos = App\Pozo::all();
        $fechas = App\Fecha::all();
        $producciones = App\Produccion::all();
        
        foreach($pozos as $pozo){
            foreach($pozo->fechas as $fechas){
                foreach($pozo->producciones as $produccion){
                    $dato[] = $produccion->cantidad;
                }
            }
        }

        dd($dato);

        return view('verexcel', compact('pozos','fechas'));
    }
}