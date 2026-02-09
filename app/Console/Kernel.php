<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\InformesController;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        $schedule->command('send:password-update-notifications')->daily()->runInBackground();
        $schedule->call(function () 
        	{
        		DB::insert ('INSERT INTO `investigaciones_observaciones_estados` ( `idInvestigacion`, `idUsuario`, `idEstado`, `observacion`, `created_at`, `updated_at`) SELECT id, 0, 20, \'Cierre Automatico por el sistema\',NOW(),NOW() FROM investigaciones WHERE estado =19 AND updated_at < CURRENT_DATE - INTERVAL 5 DAY;');
        		DB::update ('UPDATE investigaciones set estado =20, updated_at=NOW() , FechaCancelacion=NOW()  WHERE estado =19 AND updated_at < CURRENT_DATE - INTERVAL 5 DAY;');

        	})->daily();
        // $schedule->call(function () 
        // 	{
        // 		$myInformes = new InformesController();
        // 		$nombre_archivo = 'informeInvestigaciones_filtros_' . $fecha_actual . '.xlsx';
        // 		//$data = 
        		

        // 	})->dailyAt('05:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
