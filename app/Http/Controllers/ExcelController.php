<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use Symfony\Component\HttpFoundation\StreamedResponse;

use App;


class ExcelController extends Controller
{
    public function ImportarExcel(Request $request){

        $request->validate([
            'archivo' => 'required'
        ],
        [
            'archivo.required' => 'Debe subir uno o varios archivo.'
        ]);

        foreach($request->file('archivo') as $archivo){
            $path = $archivo->getRealPath();
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
        }

        $pozos = App\Pozo::OrderBy('nombre')->get();
        $fechas = App\Fecha::OrderBy('nombre')->paginate(15);

        return back()->with('aviso', 'Archivo(s) importado(s) exitosamente, haga clic en el botón para ver produccion.');
    }

    public function VerProduccion($paginate){

        $pozos = App\Pozo::OrderBy('nombre')->paginate($paginate, ['*'], 'pozos');
        $fechas = App\Fecha::OrderBy('nombre')->paginate(15,['*'], 'fechas');
        $page = count(App\Pozo::OrderBy('nombre')->get());
        
        return view('produccion', compact('pozos','fechas','page'));
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

    public function ExportarExcel(Request $request){
        $request->validate([
            'modulo' => 'required'
        ]);
                
        $Pozos = App\Pozo::OrderBy('nombre')->get();
        $Fechas = App\Fecha::OrderBy('nombre')->get();
        $Pads = App\Pad::OrderBy('nombre')->get();

        $Libro = new Spreadsheet();
        $Hoja = $Libro->getActiveSheet();
        $Hoja->setTitle('Produccion');

        $styleArray = [
            'borders' => [
                'outline' => [
                    'borderStyle' => Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ];

        $Cell = $Hoja->getColumnDimension('B')->setWidth(40); //Cambiar tamaño columna
        $Cell = $Hoja->getStyle('B1:B2');
        $Cell->getFill()->setFillType(Style\Fill::FILL_SOLID)->getStartColor()->setARGB('76933C'); //Cambia color celda
        $Cell->getFont()->setSize(14);
        $Cell->applyFromArray($styleArray);
        $Cell = $Hoja->mergeCells('B1:B2');
        
        $Cell = $Hoja->getCell('B1');
        $Cell->setValue('Produccion anual'); // (Columna, Fila)
        $Cell->getStyle()->getFont()->setBold(true);

        //$Letra = Coordinate::stringFromColumnIndex(1);  Transforma numero a letra
        //$Numero = Coordinate::columnIndexFromString('A');  Transforma letra a numero

        $count = 3;
        foreach($Fechas as $fecha){
            $Cell = $Hoja->getCellByColumnAndRow($count, 2);
            $Cell->setValue(date("d/m/Y", strtotime($fecha->nombre)));
            $Cell->getStyle()->applyFromArray($styleArray);
            $Cell->getStyle()->getFill()->setFillType(Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C4D79B');
            $Cell = $Hoja->getColumnDimension(Coordinate::stringFromColumnIndex($count))->setWidth(11.5);
            $count++;
        }

        $count = 3;
        foreach($Pozos as $pozo){
            $Cell = $Hoja->getCellByColumnAndRow(2, $count);
            $Cell->setValue($pozo->nombre);
            $Cell->getStyle()->applyFromArray($styleArray);
            $Cell->getStyle()->getFill()->setFillType(Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C4D79B');
            $column = 3;
            foreach($Fechas as $fecha){
                foreach($fecha->producciones as $produccion){
                    if($produccion->pozo_id == $pozo->id){
                        $Cell = $Hoja->getCellByColumnAndRow($column, $count);
                        $Cell->setValue($produccion->cantidad * 1000);
                        $Cell->getStyle()->applyFromArray($styleArray);
                        $Cell->getStyle()->getFill()->setFillType(Style\Fill::FILL_SOLID)->getStartColor()->setARGB('D8E4BC');
                    }
                }
                $column++;
            }
            $count++;
        }
        
        $Hoja2 = $Libro->createSheet();
        $Hoja2->setTitle('Consumo');

        $Cell = $Hoja2->getColumnDimension('B')->setWidth(40); //Cambiar tamaño columna
        $Cell = $Hoja2->getStyle('B1:B2');
        $Cell->getFill()->setFillType(Style\Fill::FILL_SOLID)->getStartColor()->setARGB('E26B0A'); //Cambia color celda
        $Cell->getFont()->setSize(14);
        $Cell->applyFromArray($styleArray);
        $Cell = $Hoja2->mergeCells('B1:B2');
        
        $Cell = $Hoja2->getCell('B1');
        $Cell->setValue('Consumo anual');
        $Cell->getStyle()->getFont()->setBold(true);

        $count = 3;
        foreach($Fechas as $fecha){
            $Cell = $Hoja2->getCellByColumnAndRow($count, 2);
            $Cell->setValue(date("d/m/Y", strtotime($fecha->nombre)));
            $Cell->getStyle()->applyFromArray($styleArray);
            $Cell->getStyle()->getFill()->setFillType(Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FABF8F');
            $Cell = $Hoja2->getColumnDimension(Coordinate::stringFromColumnIndex($count))->setWidth(11.5);
            $count++;
        }

        $count = 3;
        foreach($Pads as $pad){
            $Cell = $Hoja2->getCellByColumnAndRow(2, $count);
            $Cell->setValue($pad->nombre);
            $Cell->getStyle()->applyFromArray($styleArray);
            $Cell->getStyle()->getFill()->setFillType(Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FABF8F');
            $column = 3;
            foreach($Fechas as $fecha){
                foreach($fecha->horas as $hora1){
                    foreach($pad->horas as $hora2){
                        if($hora1->id == $hora2->id){
                            $Cell = $Hoja2->getCellByColumnAndRow($column, $count);
                            $Cell->setValue($hora1->hora);
                            $Cell->getStyle()->applyFromArray($styleArray);
                            $Cell->getStyle()->getFill()->setFillType(Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFE6C8');
                        }
                    }
                }
                $column++;
            }
            $count++;
            foreach($pad->equipos as $equipo){
                $Cell = $Hoja2->getCellByColumnAndRow(2, $count);
                $Cell->setValue($equipo->nombre);
                $Cell->getStyle()->getBorders()->getLeft()->setBorderStyle(Style\Border::BORDER_THIN);
                $Cell->getStyle()->getBorders()->getRight()->setBorderStyle(Style\Border::BORDER_THIN);
                $Cell->getStyle()->getFill()->setFillType(Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FCD5B4');
                $column = 3;
                foreach($Fechas as $fecha){
                    foreach($fecha->horas as $hora1){
                        foreach($pad->horas as $hora2){
                            if($hora1->id == $hora2->id){
                                $Cell = $Hoja2->getCellByColumnAndRow($column, $count);
                                $Cell->setValue('=('.$equipo->consumo.'/24)*'.$hora1->hora);
                                $Cell->getStyle()->getNumberFormat()->setFormatCode('#,##0.00');
                                $Cell->getStyle()->getBorders()->getLeft()->setBorderStyle(Style\Border::BORDER_THIN);
                                $Cell->getStyle()->getBorders()->getRight()->setBorderStyle(Style\Border::BORDER_THIN);
                                $Cell->getStyle()->getFill()->setFillType(Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFE6C8');
                            }
                        }
                    }
                    $column++;
                }
                $count++;
            }
        }

        if(count($request->input('modulo')) == 2){
            $Writer = new Xlsx($Libro);
            $Nombre = 'Informe_ProduccionConsumo';
        }else{
            if(array_values($request->input('modulo'))[0] == 0){
                $Libro->removeSheetByIndex(1);
                $Writer = new Xlsx($Libro);
                $Nombre = 'Informe_Produccion';
            }elseif(array_values($request->input('modulo'))[0] == 1){
                $Libro->removeSheetByIndex(0);
                $Writer = new Xlsx($Libro);
                $Nombre = 'Informe_Consumo';
            }
        }

        $response = new StreamedResponse(
            function () use ($Writer){
                $Writer->save('php://output');
            }
        );

        $response->headers->set('Content-Type', 'application/vnd.ms-excel');
        $response->headers->set('Content-Disposition', 'attachment;filename="'.$Nombre.'.xlsx"');
        $response->headers->set('Cache-Control','max-age=0');
        return $response;
    }
}