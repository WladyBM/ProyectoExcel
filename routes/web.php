<?php

Route::get('/', function () {
    return view('welcome');
});

Route::post('/excel','ExcelController@ImportarExcel')->name('importar.excel');

Route::get('/subir', function(){
    return view('subir');
})->name('subir.excel');