<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\Task;
use App\Console\Commands\GenerateWeeklyTaskPosts;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
         $schedule->command('tasks:generate-weekly-posts')->weeklyOn(1, '08:00'); // Every Monday 8 AM

         $schedule->call(function () {
            Task::where('is_done', true)
            ->whereNull('archived_at')
            ->where('updated_at', '<=', now()->subDays(1))
            ->update(['archived_at' => now()]);
        })->daily();

        $schedule->command('tasks:reset')->daily(); // or ->weekly()
    }

    protected $commands = [
    \App\Console\Commands\GenerateWeeklyTaskPosts::class,
    \App\Console\Commands\ResetTasks::class,

    ];

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }



    
}
