<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;


class ExcelController extends Controller
{
    public function ImportarExcel(Request $request){
        
        $this->validate($request, [
            'archivo' => 'required|mimes:xlsx'
        ]);

        $path = $request->file('archivo')->getRealPath();
        $Libro = IOFactory::load($path);
        //$dato = $Libro->getActiveSheet()->toArray(false, true, true, true);
        
        $pozo = array();
        $gas = array();
        $hora = array();
        //foreach(range(0,9 ) as $i){
          //  $array[] = 'Hola';
        //}
        
        for ($i = 11; $i<200; $i++){
            $pozo[] = $Libro->getSheetByName('Detalle Pozos')->getCellByColumnAndRow(5, $i)->getValue();
            if ($Libro->getSheetByName('Detalle Pozos')->getCellByColumnAndRow(5, $i)->getValue() =='TOTAL BLOQUE :'){
                break;
            }
            $hora[] = $Libro->getSheetByName('Detalle Pozos')->getCellByColumnAndRow(12, $i)->getValue();
            $gas[] = $Libro->getSheetByName('Detalle Pozos')->getCellByColumnAndRow(21, $i)->getValue();

        }
        $totalpozos = count($pozo);
        return view('vista', compact('pozo', 'hora', 'gas','totalpozos'));
    }
}