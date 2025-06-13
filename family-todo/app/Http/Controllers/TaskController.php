<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests; // ??
use Illuminate\Foundation\Auth\Access\AuthorizesResources;// ??
use App\Models\Task; // Make sure you have this model

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
        $tasks = Task::where('user_id', auth()->id())->get();

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


    public function markDone(Task $task)
    {
        //$this->authorize('update', $task); // Optional: ensure user owns the task

        $task->is_done = true;
        $task->save();

        return redirect()->route('dashboard')->with('success', 'Task marked as done!');
    }

    public function destroy(Task $task)
    {
       // $this->authorize('delete', $task); // Optional

        $task->delete();

        return redirect()->route('dashboard')->with('success', 'Task deleted Successfully.');
    }
}
