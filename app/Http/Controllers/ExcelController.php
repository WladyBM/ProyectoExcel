<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App;


class ExcelController extends Controller
{
    public function ImportarExcel(Request $request){
        
        $request->validate([
            'archivo' => 'required|mimes:xlsx,xls'
        ],
        [
            'archivo.required' => 'Debe subir un archivo.',
            'archivo.mimes' => 'Debe ingresarse un archivo excel con formato .xlsx o .xls'
        ]);

        $path = $request->file('archivo')->getRealPath();
        $Libro = IOFactory::load($path);
        
        for ($i = 12; $i<500; $i++){
            if ($Libro->getSheetByName('Detalle Pozos')->getCellByColumnAndRow(5, $i)->getValue() =='TOTAL BLOQUE :'){
            $final = $i;
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

                $Produccion = new App\Produccion;
                $Produccion->cantidad = $Libro->getSheetByName('Detalle Pozos')->getCellByColumnAndRow(21, $i)->getValue();
                $Produccion->pozo_id = $Pozos->id;
                $Produccion->fecha_id = $Fechas->id;
                $Produccion->save();

                $fechita = App\Fecha::all();
                if(count($fechita) > 1){
                    foreach($fechita as $fecha){
                        if($fecha->id == $Fechas->id){

                        }else{

                            $Produccion = new App\Produccion;
                            $Produccion->cantidad = 0;
                            $Produccion->pozo_id = $Pozos->id;
                            $Produccion->fecha_id = $fecha->id;
                            $Produccion->save();

                        }
                    }
                }

            }else{
                
                $Produccion = new App\Produccion;
                $Produccion->cantidad = $Libro->getSheetByName('Detalle Pozos')->getCellByColumnAndRow(21, $i)->getValue();
                $Produccion->pozo_id = $nombre->id;
                $Produccion->fecha_id = $Fechas->id;
                $Produccion->save();
                
            }
        }
        
        $pocito = App\Pozo::all();

        foreach($pocito as $pozo){
            if(in_array($pozo->nombre, $dato)){

            }else{
                
                $Produccion = new App\Produccion;
                $Produccion->cantidad = 0;
                $Produccion->pozo_id = $pozo->id;
                $Produccion->fecha_id = $Fechas->id;
                $Produccion->save();
            }
        }

        $pads = App\Pad::select('id','nombre')->get();
        $hora = array();

        foreach ($pads as $pad) {
            $cantidad = strlen($pad->nombre);

            for ($i = 12; $i<$final; $i++){
                if(strncmp($pad->nombre, $Libro->getSheetByName('Detalle Pozos')->getCellByColumnAndRow(5, $i)->getValue(), $cantidad) === 0){
                    $hora[] = $Libro->getSheetByName('Detalle Pozos')->getCellByColumnAndRow(12, $i)->getValue();
                }
            }
            if(empty($hora)){
                $Horas = new App\Hora;
                $Horas->hora = 0;
                $Horas->fecha_id = $Fechas->id;

                $Horas->save();
                $pad->horas()->attach([$Horas->id]);

            }else{
                // Se puede cambiar el max por min (Valor minimo del array) o sacar promedio, depende lo que se necesita
                $Horas = new App\Hora;
                $Horas->hora = max($hora);
                $Horas->fecha_id = $Fechas->id;

                $Horas->save();
                $pad->horas()->attach([$Horas->id]);

                unset($hora);
            }
        }

        //dd("Bucle terminado sin problemas, revise Database");

        $pozos = App\Pozo::OrderBy('nombre')->get();
        $fechas = App\Fecha::OrderBy('nombre')->paginate(15);

        return back()->with('aviso', 'Excel importado exitosamente, haga clic en el botón para ver produccion.');
    }

    public function VerProduccion(){

        $pozos = App\Pozo::OrderBy('nombre')->paginate(50, ['*'], 'pozos');
        $fechas = App\Fecha::OrderBy('nombre')->paginate(15,['*'], 'fechas');
        
        return view('produccion', compact('pozos','fechas'));
    }

    public function Eliminar($id){

        $Eliminado = App\Fecha::findOrFail($id);
        $Produccion = App\Produccion::where('fecha_id', $Eliminado->id)->delete();

        $Eliminado->delete();

        return back()->with('mensaje', 'Fecha eliminada exitosamente.');
    }

    public function VerConsumo(){
        
        $pads = App\Pad::OrderBy('nombre')->get();
        $fechas = App\Fecha::OrderBy('nombre')->paginate(15);
        $equipos = App\Equipo::OrderBy('nombre')->get();

        return view('consumo', compact('pads','fechas', 'equipos'));
    }

    public function AñadirPAD(Request $request){
        
        $request->validate([
            'pad' => 'required',
        ],
        [
            'pad.required' => 'El campo "Nombre PAD" es requerido' 
        ]);

        $PAD = new App\Pad;
        $PAD->nombre = $request->pad;
        $PAD->save();

        $Fechas = App\Fecha::all();

        foreach ($Fechas as $fecha){
            $Horas = new App\Hora;
            $Horas->hora = 0;
            $Horas->fecha_id = $fecha->id;

            $Horas->save();
            $PAD->horas()->attach([$Horas->id]);
        }

        return back()->with('mensaje', 'PAD añadido exitosamente.');
    }

    public function EliminarPAD($id){

        $Eliminado = App\Pad::findOrFail($id);
        
        $Equipos = App\Equipo::all();
        $Horas = App\Hora::all();

        foreach ($Equipos as $equipo){
            $Eliminado->equipos()->detach($equipo->id);
        }

        foreach ($Horas as $hora){
            $Eliminado->horas()->detach($hora->id);
        }

        $Eliminado->delete();

        return back()->with('mensaje', 'PAD eliminado exitosamente.');
    }

    public function AñadirEquipo(Request $request){

        $request->validate([
            'equipo' => 'required',
            'consumo'=> 'required|integer'
        ],
        [
            'equipo.required' => 'El campo "Nombre equipo" es requerido.',
            'consumo.required' => 'El campo "Consumo" es requerido.',
            'consumo.integer' => 'Verifique que haya ingresado un valor numerico en el campo "Consumo"'
        ]);

        $Equipo = new App\Equipo;
        $Equipo->nombre = $request->equipo;
        $Equipo->consumo = $request->consumo;
        $Equipo->save();

        return back()->with('mensaje', 'Equipo ingresado al sistema de manera exitosa.');
    }

    public function AsociarEquipo(Request $request){
        $request->validate([
            'pad' => 'required',
            'equipo'=> 'required'
        ],
        [
            'pad.required' => 'Seleccione un PAD',
            'equipo.required' => 'Seleccione uno o varios equipos'
        ]);

        $pad = App\Pad::findOrFail($request->pad);
        $pad->equipos()->attach($request->input('equipo'));

        return back()->with('mensaje', 'Se ha(n) asociado(s) el(los) equipo(s) al PAD '.$pad->nombre.'.');
    }

    public function DesligarEquipo($nombre_equipo, $nombre_pad){

        $pad = App\Pad::where('nombre','=', $nombre_pad)->first();
        $equipo = App\Equipo::where('nombre','=', $nombre_equipo)->first();

        $Pivot = $pad->equipos->where('id', $equipo->id)->first();

        $pad->equipos()->wherePivot('id','=',$Pivot->pivot->id)->detach();

        return back()->with('mensaje', 'Equipo removido de exitosamente.');
    }    

    public function ExportarExcel(){

        $Writer = new Xlsx($spreadsheet);
        $Writer->save("Test.xlsx");

    }
}