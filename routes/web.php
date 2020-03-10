<?php

Route::get('/', function () {
    return view('welcome');
});

Route::post('/Import','ExcelController@ImportarExcel')->name('importar.excel');

Route::get('/Production','ExcelController@VerProduccion')->name('ver.produccion');

Route::get('/Upload', function(){
    return view('subir');
})->name('subir.excel');

Route::delete('/DelDate/{id}', 'ExcelController@Eliminar')->name('eliminar.fecha');

Route::get('/Consumption', 'ExcelController@VerConsumo')->name('ver.consumo');

Route::post('/NewPAD', 'ExcelController@AñadirPAD')->name('añadir.pad');

Route::post('/NewEquipment', 'ExcelController@AñadirEquipo')->name('añadir.equipo');

Route::post('/AttachEquipment', 'ExcelController@AsociarEquipo')->name('asociar.equipo');

Route::delete('/UntieEquipment/{nombre_equipo}/{nombre_pad}', 'ExcelController@DesligarEquipo')->name('eliminar.equipo');

Route::delete('/DelPAD/{id}', 'ExcelController@EliminarPAD')->name('eliminar.pad');

Route::post('/Export','ExcelController@ExportarExcel')->name('exportar.excel');