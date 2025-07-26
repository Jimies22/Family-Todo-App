<?php

namespace App\Http\Controllers;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with(['user', 'comments.user', 'reactions'])
            ->whereNull('archived_at')
            ->where('status', 'published')
            ->where(function($query) {
                $query->whereNull('expires_at')
                      ->orWhere('expires_at', '>', now());
            })
            ->orderBy('is_pinned', 'desc')
            ->orderBy('is_featured', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('feeds.index', compact('posts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
            'title' => 'nullable|string|max:255',
            'type' => 'nullable|in:announcement,important,task_summary,general',
        ]);

        Post::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'content' => $request->content,
            'type' => $request->type ?? 'general',
            'status' => 'published',
            'published_at' => now(),
        ]);

        return redirect()->route('feed')->with('success', 'Posted to feed!');
    }
}
