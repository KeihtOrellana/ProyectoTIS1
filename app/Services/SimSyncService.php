<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\SimAlumno;
use App\Models\SimProfesor;
use App\Models\SimNota;
use App\Models\Proyecto;
use App\Models\PracticaTutelada;

class SimSyncService
{
    public function syncAlumnos(): int
    {
        Log::info('[SYNC] Iniciando sincronización de alumnos...');
        $total = 0;

        try {
            SimAlumno::query()->orderBy('rut')->chunk(500, function ($chunk) use (&$total) {
                $rows = $chunk->map(function ($a) {
                    return [
                        'rut_alumno'    => $a->rut,
                        'nombre_alumno' => $a->nombre,
                        'created_at'    => now(),
                        'updated_at'    => now(),
                    ];
                })->toArray();

                DB::table('alumnos')->upsert(
                    $rows,
                    ['rut_alumno'],
                    ['nombre_alumno', 'updated_at']
                );

                $total += count($rows);
                Log::info("[SYNC] Se procesaron {$total} alumnos hasta ahora.");
            });

            Log::info("[SYNC] Sincronización de alumnos completada. Total: {$total}");
        } catch (\Throwable $e) {
            Log::error('[SYNC] Error en sincronización de alumnos', ['error' => $e->getMessage()]);
        }

        return $total;
    }

    public function syncProfesores(): int
    {
        Log::info('[SYNC] Iniciando sincronización de profesores...');
        $total = 0;

        try {
            SimProfesor::query()->orderBy('rut')->chunk(500, function ($chunk) use (&$total) {
                $rows = $chunk->map(function ($p) {
                    return [
                        'rut_profesor'    => $p->rut,
                        'nombre_profesor' => $p->nombre,
                        'created_at'      => now(),
                        'updated_at'      => now(),
                    ];
                })->toArray();

                DB::table('profesores')->upsert(
                    $rows,
                    ['rut_profesor'],
                    ['nombre_profesor', 'updated_at']
                );

                $total += count($rows);
                Log::info("[SYNC] Se procesaron {$total} profesores hasta ahora.");
            });

            Log::info("[SYNC] Sincronización de profesores completada. Total: {$total}");
        } catch (\Throwable $e) {
            Log::error('[SYNC] Error en sincronización de profesores', ['error' => $e->getMessage()]);
        }

        return $total;
    }

    public function syncNotas(): int
    {
        Log::info('[SYNC] Iniciando sincronización de notas...');
        $total = 0;

        try {
            SimNota::query()->orderBy('rut_alumno')->chunk(500, function ($chunk) use (&$total) {
                foreach ($chunk as $n) {
                    DB::table('proyectos')
                        ->where('alumno_rut', $n->rut_alumno)
                        ->update([
                            'nota_final' => $n->nota_final,
                            'fecha_nota' => $n->fecha_nota,
                            'updated_at' => now(),
                        ]);
                }
                $total += $chunk->count();
                Log::info("[SYNC] Se procesaron {$total} notas hasta ahora.");
            });

            Log::info("[SYNC] Sincronización de notas completada. Total: {$total}");
        } catch (\Throwable $e) {
            Log::error('[SYNC] Error en sincronización de notas', ['error' => $e->getMessage()]);
        }

        return $total;
    }


    public function syncHabilitacionesCampos(): array
    {
        Log::info('[SYNC] Actualizando campos en habilitaciones existentes (semestre/nota)...');

        $updProy = 0;
        $updPrac = 0;

        // --- PROYECTOS ---
        Proyecto::query()->orderBy('alumno_rut')->chunk(300, function ($chunk) use (&$updProy) {
            foreach ($chunk as $p) {
                // lee origen simulado
                $simAlu  = SimAlumno::find($p->alumno_rut); // tiene semestre_inscrito
                $simNota = SimNota::where('rut_alumno', $p->alumno_rut)
                                ->orderByDesc('fecha_nota')
                                ->first();

                $sem = $simAlu?->semestre_inscrito; // puede ser null si no existe en sim
                $nota = $simNota->nota_final ?? null;
                $fNota = $simNota->fecha_nota ?? null;

                // aplica cambios sólo si hay algo que actualizar
                $changed = false;
                if (!is_null($sem) && $p->semestre_inicio !== $sem) { $p->semestre_inicio = $sem; $changed = true; }
                // si no hay registro en sim_notas => poner NULL explícitamente
                if ($p->nota_final !== $nota) { $p->nota_final = $nota; $changed = true; }
                if ($p->fecha_nota != $fNota) { $p->fecha_nota = $fNota; $changed = true; }

                if ($changed) { $p->updated_at = now(); $p->save(); $updProy++; }
            }
            Log::info("[SYNC] Proyecto: lote actualizado, acumulados={$updProy}");
        });

        // --- PRACTICA TUTELADA ---
        PracticaTutelada::query()->orderBy('alumno_rut')->chunk(300, function ($chunk) use (&$updPrac) {
            foreach ($chunk as $pt) {
                $simAlu  = SimAlumno::find($pt->alumno_rut);
                $simNota = SimNota::where('rut_alumno', $pt->alumno_rut)
                                ->orderByDesc('fecha_nota')
                                ->first();

                $sem = $simAlu?->semestre_inscrito;
                $nota = $simNota->nota_final ?? null;
                $fNota = $simNota->fecha_nota ?? null;

                $changed = false;
                if (!is_null($sem) && $pt->semestre_inicio !== $sem) { $pt->semestre_inicio = $sem; $changed = true; }
                if ($pt->nota_final !== $nota) { $pt->nota_final = $nota; $changed = true; }
                if ($pt->fecha_nota != $fNota) { $pt->fecha_nota = $fNota; $changed = true; }

                if ($changed) { $pt->updated_at = now(); $pt->save(); $updPrac++; }
            }
            Log::info("[SYNC] Práctica: lote actualizado, acumulados={$updPrac}");
        });

        Log::info('[SYNC] Habilitaciones actualizadas', ['proyectos' => $updProy, 'practicas' => $updPrac]);
        return ['proyectos' => $updProy, 'practicas' => $updPrac];
    }

}
