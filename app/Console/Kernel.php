<?php

namespace App\Console;

use App\Jobs\SendReportLowStockJob;
use App\Jobs\SendReportStockJob;
use App\Jobs\Stock;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        // $schedule->job(new Stock())->dailyAt('11:00')->timezone('Asia/Jakarta');
        $schedule->job(new SendReportStockJob())->dailyAt('11:00'); // Kirim laporan stok jam 9 pagi
        $schedule->job(new SendReportLowStockJob)->dailyAt('11:05'); // Kirim peringatan stok rendah jam 9:05
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
