<?php

Use App\Pozo;
Use App\Fecha;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/excel','ExcelController@ImportarExcel')->name('importar.excel');

Route::get('/excel', 'ExcelController@VerExcel')->name('ver.excel');

Route::get('/dbexcel', function () {
    $pozos = Pozo::all();
    $fechas = Fecha::all();
    return view('verexcel', compact('pozos', 'fechas'));
});

Route::get('/subir', function(){
    return view('subir');
})->name('subir.excel');