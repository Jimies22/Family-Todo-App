<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ReactionController;
use App\Http\Controllers\Post;



Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', [TaskController::class, 'index'])
//     ->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [TaskController::class, 'index'])->name('dashboard');
    Route::get('/tasks/create', [TaskController::class, 'create'])->name('tasks.create');

    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
    //Route::post('/tasks/{task}/done', [TaskController::class, 'markDone'])->name('tasks.done');
    Route::patch('/tasks/{task}/done', [TaskController::class, 'markDone'])->name('tasks.markDone');

    Route::get('/tasks/{task}/edit', [TaskController::class, 'edit'])->name('tasks.edit');
    Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');


    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');

    Route::get('/feed', [PostController::class, 'index'])->name('feed')->middleware(['auth', 'verified']);
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
    //Route::post('/react', [ReactionController::class, 'react'])->name('reactions.react');
    Route::post('/react', [ReactionController::class, 'react'])->name('reactions.react')->middleware(['auth']);
    Route::post('/tasks/clear-archived', [TaskController::class, 'clearArchived'])->name('tasks.clearArchived');
    Route::get('/tasks/archived', [TaskController::class, 'archived'])->name('tasks.archived');

    

    //Route::patch('/posts/{post}/task/{index}', [PostController::class, 'markTaskDone'])->name('posts.markTaskDone'); // update task status in post
    // Route::delete('/posts/{post}/task/{index}', [PostController::class, 'removeTask'])->name('posts.removeTask'); // remove task from post
    // Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit'); // edit post
    // Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update'); // update post
    // Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy'); // delete post




});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    Route::get('/tasks', [AdminController::class, 'tasks'])->name('admin.tasks');
    Route::get('/posts', [AdminController::class, 'posts'])->name('admin.posts');
});


require __DIR__.'/auth.php';
