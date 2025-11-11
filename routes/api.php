<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SimulacionUCSCController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/simulacion/profesores', [SimulacionUCSCController::class, 'obtenerProfesores']);
Route::get('/simulacion/alumnos', [SimulacionUCSCController::class, 'obtenerAlumnos']);
