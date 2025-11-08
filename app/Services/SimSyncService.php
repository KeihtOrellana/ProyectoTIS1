<?php

namespace App\Services;

use App\Support\HabilProfValidator;
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
                $rows = [];

                foreach ($chunk as $a) {
                    // Mapea a las CLAVES que espera validarProfesor
                    $payload = [
                        'RUT_Profesor'    => (int) $a->rut,
                        'Nombre_Profesor' => (string) $a->nombre,
                    ];

                    $val = \App\Support\HabilProfValidator::validarProfesor($payload);
                    if (!$val['ok']) {
                        Log::warning('[SYNC][ALUMNO] inválido', [
                            'rut' => $a->rut,
                            'errors' => $val['errors']
                        ]);
                        continue;
                    }

                    $rows[] = [
                        'rut_alumno'    => $a->rut,
                        'nombre_alumno' => $a->nombre,
                        'created_at'    => now(),
                        'updated_at'    => now(),
                    ];

                    Log::info('[SYNC][ALUMNO] procesado', [
                        'rut' => $a->rut,
                        'nombre' => $a->nombre
                    ]);
                }

                if (!empty($rows)) {
                    DB::table('alumnos')->upsert(
                        $rows,
                        ['rut_alumno'],
                        ['nombre_alumno', 'updated_at']
                    );
                    $total += count($rows);
                }
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
                $rows = [];
                foreach ($chunk as $p) {
                    $payload = [
                        'RUT_Profesor'    => (int) $p->rut,
                        'Nombre_Profesor' => (string) $p->nombre,
                    ];

                    $val = \App\Support\HabilProfValidator::validarProfesor($payload);
                    if (!$val['ok']) {
                        Log::warning('[SYNC][PROF] inválido', ['rut' => $p->rut, 'errors' => $val['errors']]);
                        continue;
                    }

                    $rows[] = [
                        'rut_profesor'    => $p->rut,
                        'nombre_profesor' => $p->nombre,
                        'created_at'      => now(),
                        'updated_at'      => now(),
                    ];
                }

                if (!empty($rows)) {
                    \DB::table('profesores')->upsert(
                        $rows,
                        ['rut_profesor'],
                        ['nombre_profesor','updated_at']
                    );
                    $total += count($rows);
                }
            });

            Log::info("[SYNC] Sincronización de profesores completada. Total: {$total}");
        } catch (\Throwable $e) {
            Log::error('[SYNC] Error en sincronización de profesores', ['error' => $e->getMessage()]);
        }

        return $total;
    }

    public function syncNotas(): int
    {
        Log::info('[SYNC] Iniciando sincronización de notas (por id_habilitacion)...');
        $total = 0;

        try {
            SimNota::query()->orderBy('rut_alumno')->chunk(500, function ($chunk) use (&$total) {
                foreach ($chunk as $n) {
                    // Validación (usa el semestre de sim_notas)
                    $payload = [
                        'Semestre_Inicio' => (string)$n->semestre_inscrito,
                        'Nota_Final'      => $n->nota_final,
                        'Fecha_Inicio'    => now()->format('d/m/Y'), // requerida por R1.7
                        'Fecha_Nota'      => $n->fecha_nota ? \Carbon\Carbon::parse($n->fecha_nota)->format('d/m/Y') : null,
                    ];

                    Log::debug('[SYNC][NOTA] entrada', [
                        'rut'      => $n->rut_alumno,
                        'semestre' => $n->semestre_inscrito,
                        'nota'     => $n->nota_final,
                        'fecha'    => $n->fecha_nota,
                    ]);

                    $idHab = $this->idHab((int)$n->rut_alumno, (string)$n->semestre_inscrito);
                    Log::debug('[SYNC][NOTA] id_hab calculado', ['id_hab' => $idHab]);

                    $val = \App\Support\HabilProfValidator::validarHabilitacion($payload);
                    if (!$val['ok']) {
                        Log::warning('[SYNC][NOTA] inválida', ['rut' => $n->rut_alumno, 'errors' => $val['errors']]);
                        continue;
                    }

                    // Clave correcta: id_habilitacion (RUT + AAAAY)
                    $idHab = $this->idHab((int)$n->rut_alumno, (string)$n->semestre_inscrito);

                    // ¿Existe en proyectos o en práctica?
                    $actualProy = Proyecto::query()
                        ->select('id_habilitacion','nota_final','fecha_nota')
                        ->where('id_habilitacion', $idHab)
                        ->first();

                    $actualPrac = $actualProy ? null : PracticaTutelada::query()
                        ->select('id_habilitacion','nota_final','fecha_nota')
                        ->where('id_habilitacion', $idHab)
                        ->first();

                    if (!$actualProy && !$actualPrac) {
                        Log::info('[SYNC][NOTA] id_habilitacion no encontrado', ['id_hab' => $idHab]);
                        continue;
                    }

                    $nuevaNota   = $n->nota_final;                 // puede ser null
                    $fechaFuente = $n->fecha_nota ?? null;         // puede ser null
                    $nuevaFecha  = $fechaFuente;                   // preferimos fecha de la fuente

                    // Estado actual del destino
                    $dest = $actualProy ?? $actualPrac;
                    $notaActual  = $dest->nota_final;
                    $fechaActual = $dest->fecha_nota;

                    // Regla: si aparece nota y la fuente NO trae fecha, y antes era NULL -> poner HOY
                    if ($nuevaNota !== null && $fechaFuente === null && $notaActual === null) {
                        $nuevaFecha = now()->toDateString();
                    }
                    // Si la nota vuelve a NULL -> fecha NULL
                    if ($nuevaNota === null) {
                        $nuevaFecha = null;
                    }

                    $update = [
                        'nota_final' => $nuevaNota,
                        'fecha_nota' => $nuevaFecha,
                        'updated_at' => now(),
                    ];

                    if ($actualProy) {
                        Proyecto::query()->where('id_habilitacion', $idHab)->update($update);
                    } else {
                        PracticaTutelada::query()->where('id_habilitacion', $idHab)->update($update);
                    }

                    $total++;
                }

                Log::warning('[SYNC][NOTA] id_habilitacion no encontrado en proyectos/practica', [
                    'id_hab' => $idHab,
                    'rut'    => $n->rut_alumno,
                    'sem'    => $n->semestre_inscrito,
                ]);

            });

            Log::info("[SYNC] Sincronización de notas completada. Total válidas: {$total}");
        } catch (\Throwable $e) {
            Log::error('[SYNC] Error en sincronización de notas', ['error' => $e->getMessage()]);
        }

        return $total;
    }

    public function syncHabilitacionesCampos(): array
    {
        Log::info('[SYNC] Actualizando habilitaciones existentes por id_habilitacion (semestre/nota)...');

        $updProy = 0;
        $updPrac = 0;

        // --- PROYECTOS ---
        Proyecto::query()->orderBy('alumno_rut')->chunk(300, function ($chunk) use (&$updProy) {
            foreach ($chunk as $p) {
                $changed = false;

                // 1) Asegurar semestre_inicio: si viene vacío, lo inferimos desde id_habilitacion
                $sem = $p->semestre_inicio;
                if (empty($sem)) {
                    $tail = substr((string)$p->id_habilitacion, -5); // AAAAY
                    if (preg_match('/^\d{5}$/', $tail)) {
                        $aaaa = substr($tail, 0, 4);
                        $y    = substr($tail, 4, 1);
                        $sem  = "{$aaaa}-{$y}";
                        $p->semestre_inicio = $sem;
                        $changed = true; // marcamos cambio por completar el semestre
                        Log::info('[SYNC][PROY] semestre inferido desde id_habilitacion', [
                            'id_hab' => $p->id_habilitacion,
                            'sem'    => $sem
                        ]);
                    } else {
                        Log::warning('[SYNC][PROY] no se pudo inferir semestre desde id_habilitacion', [
                            'id_hab' => $p->id_habilitacion
                        ]);
                        continue; // sin semestre no podemos cruzar con sim_notas
                    }
                }

                // 2) Buscar nota en sim_notas del MISMO semestre
                $simNota = SimNota::where('rut_alumno', $p->alumno_rut)
                            ->where('semestre_inscrito', $sem)
                            ->orderByDesc('fecha_nota')
                            ->first();

                $nota = $simNota->nota_final ?? null;
                $fNota = $simNota->fecha_nota ?? null;

                // 3) Validación (tolerar fecha_inicio nula para el payload)
                $payload = [
                    'Semestre_Inicio' => (string)$sem,
                    'Nota_Final'      => $nota,
                    'Fecha_Inicio'    => $p->fecha_inicio ? \Carbon\Carbon::parse($p->fecha_inicio)->format('d/m/Y') : now()->format('d/m/Y'),
                    'Fecha_Nota'      => $fNota ? \Carbon\Carbon::parse($fNota)->format('d/m/Y') : null,
                ];
                $val = \App\Support\HabilProfValidator::validarHabilitacion($payload);
                if (!$val['ok']) {
                    Log::warning('[SYNC][PROY] inválido', ['alumno' => $p->alumno_rut, 'errors' => $val['errors']]);
                    // igual guardamos el semestre inferido si lo acabamos de completar
                    if ($changed) { $p->updated_at = now(); $p->save(); }
                    continue;
                }

                // 4) Reglas de transición para nota/fecha
                $notaActual  = $p->nota_final;
                $fechaActual = $p->fecha_nota;
                $notaNueva   = $nota;
                $fechaFuente = $fNota;
                $fechaNueva  = $fechaFuente;

                if ($notaActual === null && $notaNueva !== null && $fechaFuente === null) {
                    $fechaNueva = now()->toDateString();
                }
                if ($notaNueva === null) {
                    $fechaNueva = null;
                }

                if ($notaActual !== $notaNueva) { $p->nota_final = $notaNueva; $changed = true; }
                if ($p->fecha_nota != $fechaNueva) { $p->fecha_nota = $fechaNueva; $changed = true; }

                if ($changed) { $p->updated_at = now(); $p->save(); $updProy++; }
            }

            Log::info("[SYNC] Proyecto: lote actualizado, acumulados={$updProy}");
        });

        // --- PRÁCTICAS TUTELADAS ---
        PracticaTutelada::query()->orderBy('alumno_rut')->chunk(300, function ($chunk) use (&$updPrac) {
            foreach ($chunk as $pt) {
                $changed = false;

                // 1) Asegurar semestre_inicio desde id_habilitacion si viene vacío
                $sem = $pt->semestre_inicio;
                if (empty($sem)) {
                    $tail = substr((string)$pt->id_habilitacion, -5); // AAAAY
                    if (preg_match('/^\d{5}$/', $tail)) {
                        $aaaa = substr($tail, 0, 4);
                        $y    = substr($tail, 4, 1);
                        $sem  = "{$aaaa}-{$y}";
                        $pt->semestre_inicio = $sem;
                        $changed = true;
                        Log::info('[SYNC][PRAC] semestre inferido desde id_habilitacion', [
                            'id_hab' => $pt->id_habilitacion,
                            'sem'    => $sem
                        ]);
                    } else {
                        Log::warning('[SYNC][PRAC] no se pudo inferir semestre desde id_habilitacion', [
                            'id_hab' => $pt->id_habilitacion
                        ]);
                        continue;
                    }
                }

                // 2) Buscar nota en sim_notas del MISMO semestre
                $simNota = SimNota::where('rut_alumno', $pt->alumno_rut)
                            ->where('semestre_inscrito', $sem)
                            ->orderByDesc('fecha_nota')
                            ->first();

                $nota = $simNota->nota_final ?? null;
                $fNota = $simNota->fecha_nota ?? null;

                // 3) Validación (tolerando fecha_inicio nula)
                $payload = [
                    'Semestre_Inicio' => (string)$sem,
                    'Nota_Final'      => $nota,
                    'Fecha_Inicio'    => $pt->fecha_inicio ? \Carbon\Carbon::parse($pt->fecha_inicio)->format('d/m/Y') : now()->format('d/m/Y'),
                    'Fecha_Nota'      => $fNota ? \Carbon\Carbon::parse($fNota)->format('d/m/Y') : null,
                ];
                $val = \App\Support\HabilProfValidator::validarHabilitacion($payload);
                if (!$val['ok']) {
                    Log::warning('[SYNC][PRAC] inválido', ['alumno' => $pt->alumno_rut, 'errors' => $val['errors']]);
                    if ($changed) { $pt->updated_at = now(); $pt->save(); }
                    continue;
                }

                // 4) Reglas de transición para nota/fecha
                $notaActual  = $pt->nota_final;
                $fechaActual = $pt->fecha_nota;
                $notaNueva   = $nota;
                $fechaFuente = $fNota;
                $fechaNueva  = $fechaFuente;

                if ($notaActual === null && $notaNueva !== null && $fechaFuente === null) {
                    $fechaNueva = now()->toDateString();
                }
                if ($notaNueva === null) {
                    $fechaNueva = null;
                }

                if ($notaActual !== $notaNueva) { $pt->nota_final = $notaNueva; $changed = true; }
                if ($pt->fecha_nota != $fechaNueva) { $pt->fecha_nota = $fechaNueva; $changed = true; }

                if ($changed) { $pt->updated_at = now(); $pt->save(); $updPrac++; }
            }

            Log::info("[SYNC] Práctica: lote actualizado, acumulados={$updPrac}");
        });

        Log::info('[SYNC] Habilitaciones actualizadas', ['proyectos' => $updProy, 'practicas' => $updPrac]);
        return ['proyectos' => $updProy, 'practicas' => $updPrac];
    }

}
