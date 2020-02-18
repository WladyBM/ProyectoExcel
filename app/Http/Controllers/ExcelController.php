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
        //$dato = $Libro->getActiveSheet()->toArray(false, true, true, true);
        
        //$pozo = array();
        //$gas = array();
        //$hora = array();
        //foreach(range(0,9 ) as $i){
          //  $array[] = 'Hola';
        //}
        
        for ($i = 12; $i<500; $i++){
            if ($Libro->getSheetByName('Detalle Pozos')->getCellByColumnAndRow(5, $i)->getValue() =='TOTAL BLOQUE :'){
            $final = $i-1;
            break;
            }
        }
        
        for ($i = 12; $i<$final; $i++){
            //$pozo[] = $Libro->getSheetByName('Detalle Pozos')->getCellByColumnAndRow(5, $i)->getValue();
            $Pozos = new App\Pozos;
            $Pozos->nombre = $Libro->getSheetByName('Detalle Pozos')->getCellByColumnAndRow(5, $i)->getValue();
            $Pozos->produccion = $Libro->getSheetByName('Detalle Pozos')->getCellByColumnAndRow(21, $i)->getValue();
            $Pozos->hora = $Libro->getSheetByName('Detalle Pozos')->getCellByColumnAndRow(12, $i)->getValue();
            $Pozos->fecha = Date::excelToDateTimeObject($Libro->getSheetByName('Detalle Pozos')->getCellByColumnAndRow(7, 5)->getValue());
            
            //$Pozos->fecha = $Libro->getSheetByName('Detalle Pozos')->getCellByColumnAndRow(7, 5)->getValue();

            $Pozos->save();
            //$hora[] = $Libro->getSheetByName('Detalle Pozos')->getCellByColumnAndRow(12, $i)->getValue();
            //$gas[] = $Libro->getSheetByName('Detalle Pozos')->getCellByColumnAndRow(21, $i)->getValue();

        }

        $dato = App\Pozos::all();
        //$totalpozos = count($pozo);
        return view('vista', compact('dato'));
    }

    public function VerExcel(){
        $dato = App\Pozos::all();

        return view('vista', compact('dato'));
    }
}