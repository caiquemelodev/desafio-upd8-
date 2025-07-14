<?php

use Illuminate\Support\Facades\Route;


Route::get('/clientes', function () {
    return view('clientes');
});

Route::get('/representantes', function () {
    return view('representantes');
});
