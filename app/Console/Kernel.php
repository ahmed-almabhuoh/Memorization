<?php

namespace App\Console;

use App\Jobs\StoreDailyKeeperAbsenceJob;
use App\Jobs\StoreDailyStudentAbsencesJob;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();


        $schedule->job(new StoreDailyKeeperAbsenceJob())->dailyAt('12:00');
        $schedule->job(new StoreDailyStudentAbsencesJob())->dailyAt('12:00');
//        $schedule->job(new StoreDailyStudentAbsencesJob())->everyMinute();
//        $schedule->job(new StoreDailyKeeperAbsenceJob())->everyMinute();


        $schedule->command('queue:work --stop-when-empty')
            ->everyMinute()
            ->withoutOverlapping();
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
