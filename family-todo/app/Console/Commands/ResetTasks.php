<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ResetTasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:reset-tasks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
{
    Task::whereDate('created_at', '<', now()->toDateString())
        ->where('is_done', false)
        ->whereNull('archived_at')
        ->update([
            'archived_at' => now(),
        ]);

    // Optionally: notify user or log event
}

}
