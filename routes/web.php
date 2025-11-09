<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('indexPrincipal');
});

Route::get('/formulario', function () {
    return view('formulario');
});