<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Task;
use App\Models\Post;

class AdminController extends Controller
{
    public function dashboard()
    {
        $tasksCount = Task::count();
        $usersCount = User::count();
        $postsCount = Post::count();
        return view('admin.dashboard', compact('tasksCount', 'usersCount', 'postsCount'));
    }

    public function users()
    {
        $users = User::all();
        return view('admin.users', compact('users'));
    }

    public function tasks()
    {
        $tasks = Task::all();
        return view('admin.tasks', compact('tasks'));
    }

    public function posts()
    {
        $posts = Post::all();
        return view('admin.posts', compact('posts'));
    }
}
