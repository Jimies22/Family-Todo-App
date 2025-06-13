<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateWeeklyTaskPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-weekly-task-posts';

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
        $users = User::all();
        foreach ($users as $user) {
            $weekTasks = $user->tasks()
                ->whereBetween('due_date', [now()->startOfWeek(), now()->endOfWeek()])
                ->get(['title', 'due_date', 'is_done']);

            Post::create([
                'user_id' => $user->id,
                'content' => json_encode($weekTasks),
            ]);
        }
    }

}
