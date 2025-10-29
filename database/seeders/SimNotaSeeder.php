<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SimAlumno; 
use App\Models\SimNota; 

class SimNotaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $alumnos = SimAlumno::all();
        foreach ($alumnos as $alumno) {
            SimNota::factory()->create([
                'rut_alumno' => $alumno->rut, 
            ]);
        }
    }
}