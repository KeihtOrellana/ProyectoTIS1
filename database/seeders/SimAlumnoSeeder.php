<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // ¡Importante!

class SimAlumnoSeeder extends Seeder
{
    public function run(): void
    {
        // Especificamos la conexión ANTES de la tabla
        DB::connection('mysql_simulacion')->table('sim_alumnos')->insert([
            ['rut' => 11111111, 'nombre' => 'Ana Martínez', 'semestre_inscrito' => '2025-1', 'created_at' => now(), 'updated_at' => now()],
            ['rut' => 22222222, 'nombre' => 'Bruno Díaz', 'semestre_inscrito' => '2025-1', 'created_at' => now(), 'updated_at' => now()],
            ['rut' => 1234567, 'nombre' => 'Daniel Soto', 'semestre_inscrito' => '2025-2', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}