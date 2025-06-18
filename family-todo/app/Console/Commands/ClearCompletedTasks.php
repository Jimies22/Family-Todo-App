<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ClearCompletedTasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clear-completed-tasks';

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
        //
        Task::where('is_done', true)
        ->whereNotNull('archived_at')
        ->delete();
    }
}
