<?php

namespace App\Http\Controllers;
use App\Models\Post; // Ensure the presence of Post model
use App\Models\User; // Ensure the presence of User model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with('user')->latest()->get();
        return view('feeds.index', compact('posts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|json',
        ]);

        Post::create([
            'user_id' => Auth::id(),
            'content' => $request->content,
        ]);

        return redirect()->route('feed')->with('success', 'Posted to feed!');
    }

    public function markTaskDone(Post $post, $index)
    {
        $this->authorize('update', $post); // Optional, for security

        $tasks = json_decode($post->content);
        if (isset($tasks[$index])) {
            $tasks[$index]->is_done = true;
            $post->content = json_encode($tasks);
            $post->save();
        }

        return back();
    }


}
