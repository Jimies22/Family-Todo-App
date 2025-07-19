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
        $posts = Post::with('user')
            ->whereNull('archived_at')
            ->latest()
            ->get();
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

    


}
