<?php

namespace App\Console;

use App\Models\Rental;
use App\RentalStatus;
use Carbon\Carbon;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->call(function () {
            Log::info('Tarefa executada: '.Carbon::now());
            DB::table('rentals')
            ->whereDate('expected_return', '<',   Carbon::now())
                ->where('status', '=', 'IN_PROGRESS')
                ->whereNull('returned_at')
                ->update([ 'status' => 'LATE' ]);
       })->hourly()->runInBackground();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
