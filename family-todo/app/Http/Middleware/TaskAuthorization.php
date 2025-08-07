<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Task;
use Symfony\Component\HttpFoundation\Response;

class TaskAuthorization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get the task ID from the route parameters
        $taskId = $request->route('task');
        
        if ($taskId) {
            // If it's a model instance, check if it exists and belongs to user
            if ($taskId instanceof Task) {
                $task = $taskId;
            } else {
                // If it's an ID, find the task
                $task = Task::where('id', $taskId)
                           ->where('user_id', auth()->id())
                           ->first();
            }

            // If task doesn't exist or doesn't belong to user, redirect with error
            if (!$task || $task->user_id !== auth()->id()) {
                return redirect()->route('dashboard')
                    ->with('error', 'Task not found or you are not authorized to access it.');
            }
        }

        return $next($request);
    }
}
