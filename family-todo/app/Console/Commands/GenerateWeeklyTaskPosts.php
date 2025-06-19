<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Task;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Log;

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
    // public function handle()
    // {
    //     $users = User::all();
    //     foreach ($users as $user) {
    //         $weekTasks = $user->tasks()
    //             ->whereBetween('due_date', [now()->startOfWeek(), now()->endOfWeek()])
    //             ->get(['title', 'due_date', 'is_done']);

    //         Post::create([
    //             'user_id' => $user->id,
    //             'content' => json_encode($weekTasks),
    //         ]);
    //     }
    // }
//     public function handle()
// {
//     $users = User::all();

//     foreach ($users as $user) {
//         // Archive all done tasks not yet archived
//         $archivedTasks = Task::where('user_id', $user->id)
//             ->where('is_done', true)
//             ->whereNull('archived_at')
//             ->get();

//         // Archive them now
//         foreach ($archivedTasks as $task) {
//             $task->archived_at = now();
//             $task->save();
//         }

//         // Prepare task summary
//         $archivedTaskTitles = $archivedTasks->pluck('title')->toArray();

//         // Create feed post
//         Post::create([
//             'user_id' => $user->id,
//             'content' => json_encode([
//                 'message' => '🗓️ Weekly Summary',
//                 'summary' => count($archivedTaskTitles) . ' tasks completed and archived.',
//                 'tasks' => $archivedTaskTitles,
//             ]),
//         ]);
//     }

//     // Weekly Leaderboard
// $topUser = User::withCount(['tasks as done_tasks_count' => function ($query) {
//     $query->where('is_done', true)
//           ->whereBetween('updated_at', [now()->subWeek(), now()]);
// }])
// ->orderByDesc('done_tasks_count')
// ->first();

// if ($topUser && $topUser->done_tasks_count > 0) {
//     Post::create([
//         'user_id' => $topUser->id,
//         'content' => json_encode([
//             'message' => '🏆 Weekly Champion!',
//             'summary' => $topUser->name . ' completed ' . $topUser->done_tasks_count . ' tasks this week!',
//             'tasks' => [], // optional
//         ]),
//     ]);
// }

// }



public function handle()
{
    $users = User::all();

    foreach ($users as $user) {
        // Archive any remaining done tasks that aren't yet archived
        $archivedCount = Task::where('user_id', $user->id)
            ->where('is_done', true)
            ->whereNull('archived_at')
            ->update(['archived_at' => now()]);

        // Create a post in the feed prompting to start a new week
        Post::create([
            'user_id' => $user->id,
            'content' => json_encode([
                'message' => '🗓️ The week has ended!',
                'summary' => "$archivedCount tasks archived. Time to start fresh with a new task list for the week! 💪",
            ]),
        ]);
    }

    Log::info('Weekly posts and archival completed successfully.');
}


}
