<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SimSyncService;
use Illuminate\Support\Facades\Log;

class SyncSimulacion extends Command
{
    protected $signature = 'simulacion:sync {--with-notas : Sincroniza también notas}';
    protected $description = 'Sincroniza datos desde la BD simulada (alumnos, profesores, (opcional) notas)';

    public function handle(SimSyncService $service)
    {
        $this->info('Iniciando sincronización desde BD simulada...');

        try {
            // --- 1. Sincroniza alumnos y profesores ---
            $al = $service->syncAlumnos();
            $pr = $service->syncProfesores();
            $this->info("Alumnos sincronizados: {$al}");
            $this->info("Profesores sincronizados: {$pr}");

            // --- 2. NUEVO: Actualiza habilitaciones existentes ---
            $hab = $service->syncHabilitacionesCampos();
            $this->info("Habilitaciones actualizadas → Proyectos: {$hab['proyectos']}, Prácticas: {$hab['practicas']}");

            // --- 3. Notas (si activas la opción) ---
            if ($this->option('with-notas')) {
                $no = $service->syncNotas();
                $this->info("Notas procesadas: {$no}");
            }

            // --- 4. Final ---
            $this->info('Sincronización OK');
            Log::info('[simulacion:sync] OK', [
                'alumnos' => $al,
                'profesores' => $pr,
                'habilitaciones' => $hab
            ]);

            return self::SUCCESS;
        } catch (\Throwable $e) {
            $this->error('Error en sincronización: '.$e->getMessage());
            Log::error('[simulacion:sync] ERROR', ['ex' => $e]);
            return self::FAILURE;
        }
    }
}
