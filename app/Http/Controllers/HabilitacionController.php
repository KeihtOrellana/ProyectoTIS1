<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proyecto; 
use App\Models\PracticaTutelada; 
use App\Support\HabilProfValidator;
use Carbon\Carbon; 
use App\Models\Profesor;
use App\Models\Alumno;

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
                    $errores = implode(' ', \Illuminate\Support\Arr::flatten($validacionProyecto['errors']));
                    return back()->with('error', 'Error en datos del proyecto: ' . $errores)->withInput();
                }
                
                // Limite de proyectos
                $proyectosActivosGuia = Proyecto::where('profesor_guia_rut', $profesorGuiaRut)
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
    // --- FUNCIONALIDAD R4: LISTADOS VARIOS ---

    /**
     * R4.6: Muestra la vista para seleccionar el tipo de listado.
     */
    public function listado()
    {
        return view('listado');
    }

    /**
     * R4.8 y R4.9: Genera el reporte según el tipo seleccionado.
     */
    public function generarReporte(Request $request)
    {
        // R4.1: Validación de entrada
        $request->validate([
            'tipo_listado' => 'required|string|in:Semestral,Histórico',
            // R4.7 y R4.2: Validación condicional del semestre
            'semestre_ano' => 'required_if:tipo_listado,Semestral|nullable|integer|min:2025|max:2045',
            'semestre_periodo' => 'required_if:tipo_listado,Semestral|nullable|integer|min:1|max:2',
        ], [
            'semestre_ano.required_if' => 'Semestre no válido (R4.7)',
            'semestre_periodo.required_if' => 'Semestre no válido (R4.7)',
        ]);

        $tipo = $request->tipo_listado;
        $resultados = [];
        $semestreBuscado = null;

        // --- R4.8: LISTADO SEMESTRAL ---
        if ($tipo === 'Semestral') {
            // Combinar año y periodo (AAAA-Y)
            $semestreBuscado = $request->semestre_ano . '-' . $request->semestre_periodo;

            // R4.8.1: Buscar en Proyectos
            $proyectos = Proyecto::with(['alumno', 'profesorGuia', 'profesorComision', 'profesorCoguia'])
                ->where('semestre_inicio', $semestreBuscado)
                ->get();

            // R4.8.1: Buscar en Prácticas
            $practicas = PracticaTutelada::with(['alumno', 'profesorTutor'])
                ->where('semestre_inicio', $semestreBuscado)
                ->get();

            // Validar si no hay registros (R4.8.1)
            if ($proyectos->isEmpty() && $practicas->isEmpty()) {
                return back()->withErrors(['error' => 'No se han encontrado registros para este semestre']);
            }

            // Unificar resultados
            $resultados = [
                'proyectos' => $proyectos,
                'practicas' => $practicas
            ];
        }

        // --- R4.9: LISTADO HISTÓRICO ---
        elseif ($tipo === 'Histórico') {
            // R4.9.1: Buscar profesores con sus relaciones
            // Usamos 'with' para optimizar (Eager Loading)
            $profesores = Profesor::with([
                'proyectosComoGuia.alumno',
                'proyectosComoComision.alumno',
                'proyectosCoguia.alumno',
                'practicasComoTutor.alumno'
            ])
            ->orderBy('nombre_profesor')
            ->get();

            $resultados = $profesores;
        }

        // R4.10: Desplegar listado
        return view('reporte', compact('tipo', 'resultados', 'semestreBuscado'));
    }
}