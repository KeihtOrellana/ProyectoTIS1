<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proyecto; 
use App\Models\PracticaTutelada; 
use App\Support\HabilProfValidator;
use Carbon\Carbon; 

class HabilitacionController extends Controller
{
    /* Almacena una nueva habilitacion profesional. */
    public function store(Request $request)
    {
        $rutAlumno = (int)$request->input('rut_alumno');
        $semestreInicio = $request->input('semestre-ano') . '-' . $request->input('semestre-periodo');
        $tipo = $request->input('tipo_habilitacion');

        // Inicializamos variables en null para la vista
        $nuevoProyecto = null;
        $nuevaPractica = null;

        try {
            if ($tipo == 'PrInv' || $tipo == 'PrIng') {
                
                // Validacion: si ya existe proyecto
                $yaExisteProyecto = Proyecto::where('alumno_rut', $rutAlumno)
                                            ->where('semestre_inicio', $semestreInicio)
                                            ->exists();

                if ($yaExisteProyecto) {
                    return back()->with('error', 'Error: Este alumno ya tiene un proyecto registrado para el semestre ' . $semestreInicio . '.');
                }

                $profesorGuiaRut = (int)$request->input('profesor_guia_rut');
                $profesorComisionRut = (int)$request->input('profesor_comision_rut');
                
                $profesorCoguiaRut = null;
                if ($request->input('toggle_coguia') == 'si') {
                    $profesorCoguiaRut = (int)$request->input('profesor_coguia_rut');
                    if ($profesorCoguiaRut === 0) {
                        $profesorCoguiaRut = null;
                    }
                }

                // Validaciones de profesores
                if ($profesorGuiaRut === $profesorComisionRut) {
                    return back()->with('error', 'Error: El Profesor Guía y el Profesor de Comisión no pueden ser la misma persona.');
                }

                if ($profesorCoguiaRut !== null) {
                    if ($profesorCoguiaRut === $profesorGuiaRut) {
                        return back()->with('error', 'Error: El Profesor Co-Guía no puede ser el mismo que el Profesor Guía.');
                    }
                    if ($profesorCoguiaRut === $profesorComisionRut) {
                        return back()->with('error', 'Error: El Profesor Co-Guía no puede ser el mismo que el Profesor de Comisión.');
                    }
                }

                // Validador externo
                $validacionProyecto = HabilProfValidator::validarProyecto($request->all());

                if (!$validacionProyecto['ok']) {
                    return back()->with('error', 'Los datos ingresados no son válidos')->withInput();
                }
                
                // Limite de proyectos
                $proyectosActivosGuia = Proyecto::where('profesor_guia_rut', $profesorGuiaRut)
                                                ->where('semestre_inicio', $semestreInicio)
                                                ->whereNull('nota_final')
                                                ->count();

                if ($proyectosActivosGuia >= 5) {
                    return back()->with('error', 'Error: Límite de asignaciones alcanzado.');
                }

                // Crear Proyecto
                $nuevoProyecto = Proyecto::create([
                    'alumno_rut' => $rutAlumno,
                    'semestre_inicio' => $semestreInicio,
                    'tipo_proyecto' => $tipo,
                    'descripcion' => $request->input('descripcion'),
                    'fecha_inicio' => Carbon::now(),
                    'nota_final' => NULL, 
                    'titulo' => $request->input('titulo'),
                    'profesor_guia_rut' => $profesorGuiaRut,
                    'profesor_comision_rut' => $profesorComisionRut,
                    'profesor_coguia_rut' => $profesorCoguiaRut
                ]);

            } elseif ($tipo == 'PrTut') {
                
                $profesorTutorRut = (int)$request->input('profesor_guia_rut');
                
                // Validacin: si ya existe practica
                $yaExistePractica = PracticaTutelada::where('alumno_rut', $rutAlumno)
                                                ->where('semestre_inicio', $semestreInicio)
                                                ->exists();

                if ($yaExistePractica) {
                    return back()->with('error', 'Error: Este alumno ya tiene una práctica registrada para el semestre ' . $semestreInicio . '.');
                }
                
                $dataToValidate = [
                    'nombre_empresa'       => $request->input('nombre-empresa'),
                    'nombre_supervisor'    => $request->input('nombre_supervisor'),
                    'descripcion_practica' => $request->input('descripcion_practica'),
                    'profesor_tutor_rut'   => $profesorTutorRut,
                    'semestre_inicio'      => $semestreInicio,
                ];

                $validationResult = HabilProfValidator::validarPracticaTutelada($dataToValidate);

                if (!$validationResult['ok']) {
                    return back()->with('error', 'Los datos ingresados no son válidos')->withInput();
                }
                
                $practicasActivosGuia = PracticaTutelada::where('profesor_tutor_rut', $profesorTutorRut)
                                                        ->where('semestre_inicio', $semestreInicio)
                                                        ->whereNull('nota_final')
                                                        ->count();

                if ($practicasActivosGuia >= 5) {
                    return back()->with('error', 'Error: Límite de asignaciones alcanzado.');
                }

                // Crear Practica
                $nuevaPractica = PracticaTutelada::create([
                    'alumno_rut' => $rutAlumno,
                    'semestre_inicio' => $semestreInicio,
                    'nombre_empresa' => $request->input('nombre-empresa'),
                    'nombre_supervisor' => $request->input('nombre_supervisor'),
                    'profesor_tutor_rut' => $profesorTutorRut,
                    'descripcion' => $request->input('descripcion_practica'),
                    'fecha_inicio' => Carbon::now(),
                    'nota_final' => NULL
                ]);
            }

            // Redireccion final con los datos creados
            return redirect('/formulario')
                    ->with('success', 'Habilitación registrada con éxito.')
                    ->with('proyecto_creado', $nuevoProyecto)
                    ->with('practica_creada', $nuevaPractica);

        } catch (\Exception $e) {
            // Cualquier error general sin especificar tipo
            return back()->with('error', 'Ocurrió un error al guardar: ' . $e->getMessage())->withInput();
        }
    }
}