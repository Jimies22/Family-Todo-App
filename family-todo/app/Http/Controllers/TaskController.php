<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests; // ??
use Illuminate\Foundation\Auth\Access\AuthorizesResources;// ??
use App\Models\Task; // Make sure you have this model
use App\Models\Post;

class TaskController extends Controller
{
    // public function index()
    // {
    //     $user = Auth::user();

    //     // Get tasks for the authenticated user (adjust as needed for shared/family tasks)
    //     $tasks = Task::where('user_id', $user->id)
    //                  ->orderBy('due_date')
    //                  ->get();

    //     $todayCount = Task::where('user_id', $user->id)
    //                       ->whereDate('due_date', now())
    //                       ->count();

    //     $weekCount = Task::where('user_id', $user->id)
    //                      ->whereBetween('due_date', [now(), now()->addDays(7)])
    //                      ->count();

    //     $doneCount = Task::where('user_id', $user->id)
    //                      ->where('is_done', true)
    //                      ->count();

    //     $pendingCount = Task::where('user_id', $user->id)
    //                         ->where('is_done', false)
    //                         ->count();

    //     return view('dashboard', compact(
    //         'tasks',
    //         'todayCount',
    //         'weekCount',
    //         'doneCount',
    //         'pendingCount'
    //     ));
    // }

    public function index()
    {
        $tasks = Task::where('user_id', auth()->id())
        ->whereNull('archived_at') // only active tasks
        ->get();

        $todayCount = $tasks->where('due_date', today())->count();
        $weekCount = $tasks->whereBetween('due_date', [now()->startOfWeek(), now()->endOfWeek()])->count();
        $doneCount = $tasks->where('is_done', true)->count();
        $pendingCount = $tasks->where('is_done', false)->count();

        return view('dashboard', compact('tasks', 'todayCount', 'weekCount', 'doneCount', 'pendingCount'));
    }

    public function create()
    {
        return view('tasks.create'); // Ensure you have this view
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'due_date' => 'required|date',
        ]);

        Auth::user()->tasks()->create([
            'title' => $request->title,
            'due_date' => $request->due_date,
            'is_done'=> false,
            'description' => $request->description, // Optional
        ]);

        return redirect()->route('dashboard')->with('success', 'Task added!');
    }

    public function edit(Task $task)
    {
        //$this->authorize('update', $task); // Optional authorization
        return view('tasks.edit', compact('task'));
    }

    // Update the task
    public function update(Request $request, Task $task)
    {
        //$this->authorize('update', $task); // Optional

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'due_date' => 'required|date',
        ]);

        $task->update($validated);

        return redirect()->route('dashboard')->with('success', 'Task updated successfully.');
    }


    // public function markDone(Task $task)
    // {
    //     //$this->authorize('update', $task); // Optional: ensure user owns the task

    //     $task->is_done = true;
    //     $task->save();

    //     return redirect()->route('dashboard')->with('success', 'Task marked as done!');
    // }

    public function destroy(Task $task)
    {
       // $this->authorize('delete', $task); // Optional

        $task->delete();

        return redirect()->route('dashboard')->with('success', 'Task deleted Successfully.');
    }

//     public function markDone(Task $task)
// {
//     //$this->authorize('update', $task); // optional

//     $task->is_done = true;
//     $task->save();

//     $task->update([
//     'is_done' => true,
//     'archived_at' => now(),
//     ]);

//     // 🟣 Update latest feed post for this user
//     $latestPost = Post::where('user_id', auth()->id())->latest()->first();
//     if ($latestPost) {
//         $latestTasks = Task::where('user_id', auth()->id())->get();
//         $latestPost->content = $latestTasks->toJson();
//         $latestPost->save();
//     }

    

//     return redirect()->back()->with('success', 'Task marked as done and feed updated.');

//     $allTasksDone = Task::where('user_id', Auth::id())
//     ->whereNull('archived_at')
//     ->where('is_done', false)
//     ->count() === 0;

//     if ($allTasksDone) {
//     // Optional: Reset dashboard or prompt new list
//     // Could redirect to a fresh task list page
//     return redirect()->route('dashboard')->with('success', 'All tasks are done! You can start a new list.');
//     } else {
//         return redirect()->back()->with('success', 'Task marked as done and feed updated.');
//     }

// }

public function markDone(Task $task)
{
    // Step 1: Mark task as done and archive it
    $task->update([
        'is_done' => true,
        'archived_at' => now(),
    ]);

    // Step 2: Update the latest feed post with only active (non-archived) tasks
    $latestPost = Post::where('user_id', auth()->id())->latest()->first();
    if ($latestPost) {
        $activeTasks = Task::where('user_id', auth()->id())
                           ->whereNull('archived_at')
                           ->get();

        $latestPost->content = $activeTasks->toJson(); // only active tasks posted
        $latestPost->save();
    }

    // Step 3: Check if there are any active (non-archived) and not-done tasks left
    $allTasksDone = Task::where('user_id', auth()->id())
        ->whereNull('archived_at')
        ->where('is_done', false)
        ->count() === 0;

    if ($allTasksDone) {
        return redirect()->route('dashboard')->with('success', '🎉 All tasks are done! Start fresh!');
    }

    return redirect()->back()->with('success', '✔️ Task marked as done and archived.');
}


public function clearArchived()
{
    Task::where('user_id', auth()->id())
        ->whereNotNull('archived_at')
        ->delete();

    return redirect()->route('dashboard')->with('success', 'Archived tasks cleared.');
}
public function archived()
{
    $archivedTasks = Task::whereNotNull('archived_at')
                         ->where('user_id', auth()->id())
                         ->latest('archived_at')
                         ->get();

    return view('tasks.archived', compact('archivedTasks'));
}

}
