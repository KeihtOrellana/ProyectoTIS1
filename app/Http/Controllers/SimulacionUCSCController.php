<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // ¡Importante!

class SimulacionUCSCController extends Controller
{
    public function obtenerAlumnos()
    {
        $alumnosSimulados = DB::connection('mysql_simulacion')->table('sim_alumnos')->get();

        return response()->json($alumnosSimulados);
    }
    public function obtenerProfesores()
    {
        $profesoresSimulados = DB::connection('mysql_simulacion')->table('sim_profesores')->get();
        
        return response()->json($profesoresSimulados);
    }
    public function obtenerNotas()
    {
        $notasSimuladas = DB::connection('mysql_simulacion')->table('sim_notas')->get();
        
        return response()->json($notasSimuladas);
    }
}