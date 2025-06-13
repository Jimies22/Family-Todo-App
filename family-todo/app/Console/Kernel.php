<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
         $schedule->command('tasks:generate-weekly-posts')->weeklyOn(1, '08:00'); // Every Monday 8 AM
    }

    protected $commands = [
    \App\Console\Commands\GenerateWeeklyTaskPosts::class,
    ];

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
