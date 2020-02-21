<?php

Use App\Pozo;
Use App\Fecha;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/excel','ExcelController@ImportarExcel')->name('importar.excel');

Route::get('/excel','ExcelController@VerExcel');

Route::get('/dbexcel', 'ExcelController@VerExcel')->name('ver.excel');

Route::get('/subir', function(){
    return view('subir');
})->name('subir.excel');