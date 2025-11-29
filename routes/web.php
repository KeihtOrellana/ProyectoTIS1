<?php

use Illuminate\Support\Facades\Route;
use App\Models\Profesor;
use App\Models\Alumno;
use App\Http\Controllers\HabilitacionController;
use App\Http\Controllers\SimulacionUCSCController;

Route::get(
    '/simulacion/notas',
    [SimulacionUCSCController::class, 'obtenerNotas']
)->name('simulacion.notas');

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
// RUTAS F3
Route::post('/registrar-habilitacion', [HabilitacionController::class, 'store']);

Route::get('/editar_eliminar', [HabilitacionController::class, 'editarEliminar'])->name('habilitacion.editar_eliminar');

Route::delete('/eliminar-habilitacion/{tipo}/{id}', [HabilitacionController::class, 'eliminar'])->name('habilitacion.eliminar');

Route::put('/actualizar-habilitacion/{tipo}/{id}', [HabilitacionController::class, 'update'])->name('habilitacion.update');

// --- RUTAS DE LISTADOS (R4) ---

// R4.6: Mostrar la vista de selección de listado
Route::get('/listado', [HabilitacionController::class, 'listado'])->name('habilitacion.listado');

// R4.8 y R4.9: Procesar la búsqueda y mostrar resultados
Route::post('/generar-listado', [HabilitacionController::class, 'generarReporte'])->name('habilitacion.reporte');