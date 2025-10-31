<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\SyncSimulacion;

class Kernel extends ConsoleKernel
{
    /**
     * Si tu versión de Laravel NO autodetecta comandos, descomenta:
     */
    // protected $commands = [
    //     SyncSimulacion::class,
    // ];

    protected function schedule(Schedule $schedule): void
    {
        // Corre cada minuto
        $schedule->command('simulacion:sync')->everyMinute();

        // Si quieres incluir notas también cada 5 minutos:
        // $schedule->command('simulacion:sync --with-notas')->everyFiveMinutes();
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');
    }
}
