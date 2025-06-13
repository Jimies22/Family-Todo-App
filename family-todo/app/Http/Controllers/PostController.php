<?php

namespace App\Http\Controllers;
use App\Models\Post; // Ensure the presence of Post model
use App\Models\User; // Ensure the presence of User model
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with('user')->latest()->get();
        return view('feeds.index', compact('posts'));
    }

}
