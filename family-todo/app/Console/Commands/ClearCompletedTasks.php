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
        // Fix old data: archive all done tasks that are not yet archived
        \App\Models\Task::where('is_done', true)
            ->whereNull('archived_at')
            ->update(['archived_at' => now()]);

        // Existing clear logic
        \App\Models\Task::where('is_done', true)
            ->whereNotNull('archived_at')
            ->delete();
    }
}
