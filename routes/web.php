<?php

Use App\Pozo;
Use App\Fecha;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/excel','ExcelController@ImportarExcel')->name('importar.excel');

Route::get('/excel','ExcelController@VerProduccion')->name('ver.excel');

Route::get('/dbexcel', 'ExcelController@VerProduccion');

Route::get('/subir', function(){
    return view('subir');
})->name('subir.excel');

Route::delete('/DelFecha/{id}', 'ExcelController@Eliminar')->name('eliminar.fecha');

Route::get('/hora', 'ExcelController@VerConsumo')->name('ver.excel2');

Route::post('/NuevoPAD', 'ExcelController@AñadirPAD')->name('añadir.pad');

Route::post('/NuevoEquipo', 'ExcelController@AñadirEquipo')->name('añadir.equipo');

Route::post('/AsociarEquipo', 'ExcelController@AsociarEquipo')->name('asociar.equipo');

Route::delete('/DesEquipo/{id}', 'ExcelController@DesligarEquipo')->name('eliminar.equipo');