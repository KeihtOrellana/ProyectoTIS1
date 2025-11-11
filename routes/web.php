<?php

use Illuminate\Support\Facades\Route;
use App\Models\Profesor;
use App\Models\Alumno;
use App\Http\Controllers\HabilitacionController;

Route::get('/', function () {
    return view('indexPrincipal');
});

Route::get('/formulario', function () {
    return view('formulario');
});

//Para resatar datos de alumnos y profesores
Route::get('/formulario', function () {
    
    // 3. Busca los datos en tu base de datos principal
    $profesores = Profesor::orderBy('nombre_profesor')->get();
    $alumnos = Alumno::orderBy('nombre_alumno')->get();
    
    // 4. Pasa las variables a la vista
    return view('formulario', [
        'profesores' => $profesores,
        'alumnos' => $alumnos
    ]);
});

Route::post('/registrar-habilitacion', [HabilitacionController::class, 'store']);
