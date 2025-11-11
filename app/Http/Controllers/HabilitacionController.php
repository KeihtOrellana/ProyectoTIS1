<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proyecto; // Asegúrate de importar tu modelo
use App\Models\PracticaTutelada; 
use Illuminate\Support\Facades\Log; // Para registrar errores
use Carbon\Carbon; // Para manejar fechas

class HabilitacionController extends Controller
{
    /**
     * Almacena una nueva habilitación profesional.
     */
    public function store(Request $request)
    {
        // --- 1. PRE-PROCESAMIENTO DE DATOS ---

        // El RUT ahora viene directamente del campo oculto.
        $rutAlumno = (int)$request->input('rut_alumno');

        // Combinamos año y período en el formato 'YYYY-S'
        $semestreInicio = $request->input('semestre-ano') . '-' . $request->input('semestre-periodo');
        
        // --- 2. LÓGICA CONDICIONAL (Proyecto vs Práctica) ---

        $tipo = $request->input('tipo_habilitacion');

        if ($tipo == 'PrInv' || $tipo == 'PrIng') {
            
            // --- 3. OBTENER RUTS DE PROFESORES ---
            // Los RUTs vienen limpios desde los campos ocultos
            $profesorGuiaRut = (int)$request->input('profesor_guia_rut');
            $profesorComisionRut = (int)$request->input('profesor_comision_rut');
            
            $profesorCoguiaRut = null;
            if ($request->input('toggle_coguia') == 'si') {
                 // Convertimos a (int) solo si existe
                 $profesorCoguiaRut = (int)$request->input('profesor_coguia_rut');
                 // Si es 0 (porque el campo estaba vacío pero se marcó 'si'), lo volvemos null
                 if ($profesorCoguiaRut === 0) {
                    $profesorCoguiaRut = null;
                 }
            }

            // --- 4. GUARDAR EL PROYECTO ---
            try {
                Proyecto::create([
                    'alumno_rut' => $rutAlumno,
                    'semestre_inicio' => $semestreInicio,
                    'tipo_proyecto' => $tipo, // 'PrInv' o 'PrIng'
                    'descripcion' => $request->input('descripcion'),
                    'fecha_inicio' => Carbon::now(), // El form no lo tiene, ponemos la fecha actual
                    'nota_final' => NULL, // El form lo tiene como readonly
                    'titulo' => $request->input('titulo'),
                    'profesor_guia_rut' => $profesorGuiaRut,
                    'profesor_comision_rut' => $profesorComisionRut,
                    'profesor_coguia_rut' => $profesorCoguiaRut // Será null si no se seleccionó o estaba vacío
                ]);

            } catch (\Exception $e) {
                // Registrar el error para depuración
                Log::error('Error al guardar Proyecto: ' . $e->getMessage());
                // Redirigir con un mensaje de error
                return back()->with('error', 'Error real: ' . $e->getMessage());
            }

        } elseif ($tipo == 'PrTut') {
            
            // Obtener el RUT del profesor tutor (reutilizando el campo guía)
            $profesorTutorRut = (int)$request->input('profesor_guia_rut');
            
            // --- LÓGICA PARA PRÁCTICA TUTELADA ---
            try {
                PracticaTutelada::create([
                    'alumno_rut' => $rutAlumno,
                    'semestre_inicio' => $semestreInicio,
                    'nombre_empresa' => $request->input('nombre-empresa'),
                    'nombre_supervisor' => $request->input('nombre_supervisor'),
                    'profesor_tutor_rut' => $profesorTutorRut,
                    'descripcion' => $request->input('descripcion_practica'),
                    'fecha_inicio' => Carbon::now(),
                    'nota_final' => NULL
                ]);
                Log::info('Intento de registro de Práctica Tutelada (lógica comentada en HabilitacionController).');
                // Descomenta lo anterior cuando tengas el modelo PracticaTutelada listo.

            } catch (\Exception $e) {
                Log::error('Error al guardar Práctica: ' . $e->getMessage());
                return back()->with('error', 'Error real: ' . $e->getMessage());
            }
        }

        // --- 5. REDIRIGIR ---
        // Si todo salió bien, redirige de vuelta al formulario con un mensaje de éxito.
        return redirect('/formulario')->with('success', 'Habilitación registrada con éxito.');
    }
}