<?php

Use App\Pozo;
Use App\Fecha;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/excel','ExcelController@ImportarExcel')->name('importar.excel');

Route::get('/excel','ExcelController@VerExcel')->name('ver.excel');

Route::get('/dbexcel', 'ExcelController@VerExcel');

Route::get('/subir', function(){
    return view('subir');
})->name('subir.excel');

Route::delete('/DelFecha/{id}', 'ExcelController@Eliminar')->name('eliminar.fecha');

Route::get('/hora', 'ExcelController@VerExcel2')->name('ver.excel2');

Route::post('/NuevoPAD', 'ExcelController@AñadirPAD')->name('añadir.pad');