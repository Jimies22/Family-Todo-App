<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Task;
use App\Models\Post;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        $tasksCount = Task::count();
        $usersCount = User::count();
        $postsCount = Post::count();
        
        // Get recent activity
        $recentPosts = Post::with('user')->latest()->take(5)->get();
        $recentTasks = Task::with('user')->latest()->take(5)->get();
        $recentUsers = User::latest()->take(5)->get();
        
        // Get statistics
        $announcementsCount = Post::announcements()->count();
        $importantPostsCount = Post::important()->count();
        $pinnedPostsCount = Post::pinned()->count();
        
        return view('admin.dashboard', compact(
            'tasksCount', 
            'usersCount', 
            'postsCount',
            'recentPosts',
            'recentTasks',
            'recentUsers',
            'announcementsCount',
            'importantPostsCount',
            'pinnedPostsCount'
        ));
    }

    public function users(Request $request)
    {
        $query = User::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->filled('role')) {
            if ($request->role === 'admin') {
                $query->where('is_admin', true);
            } elseif ($request->role === 'user') {
                $query->where('is_admin', false);
            }
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        // Validate sort columns
        $allowedSortColumns = ['name', 'email', 'is_admin', 'created_at'];
        if (!in_array($sortBy, $allowedSortColumns)) {
            $sortBy = 'created_at';
        }
        
        if (!in_array($sortOrder, ['asc', 'desc'])) {
            $sortOrder = 'desc';
        }

        $query->orderBy($sortBy, $sortOrder);

        $users = $query->paginate(10)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function createUser()
    {
        return view('admin.users.create');
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'is_admin' => 'boolean',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_admin' => $request->has('is_admin'),
        ]);

        return redirect()->route('admin.users')->with('success', 'User created successfully.');
    }

    public function editUser(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'is_admin' => 'boolean',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'is_admin' => $request->has('is_admin'),
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('admin.users')->with('success', 'User updated successfully.');
    }

    public function deleteUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users')->with('error', 'You cannot delete your own account.');
        }

        $user->delete();
        return redirect()->route('admin.users')->with('success', 'User deleted successfully.');
    }

    // Enhanced Posts Management
    public function posts(Request $request)
    {
        $query = Post::with('user');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by pinned/featured
        if ($request->filled('featured')) {
            if ($request->featured === 'pinned') {
                $query->pinned();
            } elseif ($request->featured === 'featured') {
                $query->featured();
            }
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        $allowedSortColumns = ['title', 'type', 'status', 'created_at', 'published_at'];
        if (!in_array($sortBy, $allowedSortColumns)) {
            $sortBy = 'created_at';
        }
        
        if (!in_array($sortOrder, ['asc', 'desc'])) {
            $sortOrder = 'desc';
        }

        $query->orderBy($sortBy, $sortOrder);

        $posts = $query->paginate(10)->withQueryString();

        return view('admin.posts.index', compact('posts'));
    }

    public function createPost()
    {
        return view('admin.posts.create');
    }

    public function storePost(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:announcement,task_summary,general,important',
            'status' => 'required|in:draft,published',
            'is_pinned' => 'boolean',
            'is_featured' => 'boolean',
            'expires_at' => 'nullable|date|after:now',
        ]);

        $post = Post::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'content' => $request->content,
            'type' => $request->type,
            'status' => $request->status,
            'is_pinned' => $request->has('is_pinned'),
            'is_featured' => $request->has('is_featured'),
            'published_at' => $request->status === 'published' ? now() : null,
            'expires_at' => $request->expires_at,
        ]);

        return redirect()->route('admin.posts')->with('success', 'Post created successfully.');
    }

    public function editPost(Post $post)
    {
        return view('admin.posts.edit', compact('post'));
    }

    public function updatePost(Request $request, Post $post)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:announcement,task_summary,general,important',
            'status' => 'required|in:draft,published,archived',
            'is_pinned' => 'boolean',
            'is_featured' => 'boolean',
            'expires_at' => 'nullable|date',
        ]);

        $post->update([
            'title' => $request->title,
            'content' => $request->content,
            'type' => $request->type,
            'status' => $request->status,
            'is_pinned' => $request->has('is_pinned'),
            'is_featured' => $request->has('is_featured'),
            'published_at' => $request->status === 'published' && !$post->published_at ? now() : $post->published_at,
            'expires_at' => $request->expires_at,
        ]);

        return redirect()->route('admin.posts')->with('success', 'Post updated successfully.');
    }

    public function deletePost(Post $post)
    {
        $post->delete();
        return redirect()->route('admin.posts')->with('success', 'Post deleted successfully.');
    }

    public function togglePin(Post $post)
    {
        $post->update(['is_pinned' => !$post->is_pinned]);
        $action = $post->is_pinned ? 'pinned' : 'unpinned';
        return redirect()->route('admin.posts')->with('success', "Post {$action} successfully.");
    }

    public function toggleFeatured(Post $post)
    {
        $post->update(['is_featured' => !$post->is_featured]);
        $action = $post->is_featured ? 'featured' : 'unfeatured';
        return redirect()->route('admin.posts')->with('success', "Post {$action} successfully.");
    }

    // Task Management
    public function tasks(Request $request)
    {
        $query = Task::with('user');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('title', 'like', "%{$search}%");
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'completed') {
                $query->where('is_done', true);
            } elseif ($request->status === 'pending') {
                $query->where('is_done', false);
            }
        }

        // Filter by archive status
        if ($request->filled('archive_status')) {
            if ($request->archive_status === 'archived') {
                $query->archived();
            } elseif ($request->archive_status === 'active') {
                $query->active();
            }
        } else {
            // Default to showing active tasks only
            $query->active();
        }

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        $allowedSortColumns = ['title', 'is_done', 'created_at', 'due_date', 'archived_at'];
        if (!in_array($sortBy, $allowedSortColumns)) {
            $sortBy = 'created_at';
        }
        
        if (!in_array($sortOrder, ['asc', 'desc'])) {
            $sortOrder = 'desc';
        }

        $query->orderBy($sortBy, $sortOrder);

        $tasks = $query->paginate(10)->withQueryString();
        $users = User::all(); // For user filter dropdown

        return view('admin.tasks.index', compact('tasks', 'users'));
    }

    // Analytics and Reports
    public function analytics()
    {
        // User statistics
        $totalUsers = User::count();
        $adminUsers = User::where('is_admin', true)->count();
        $regularUsers = User::where('is_admin', false)->count();
        
        // Task statistics
        $totalTasks = Task::count();
        $completedTasks = Task::where('is_done', true)->count();
        $pendingTasks = Task::where('is_done', false)->count();
        $taskCompletionRate = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100, 2) : 0;
        
        // Post statistics
        $totalPosts = Post::count();
        $announcements = Post::announcements()->count();
        $importantPosts = Post::important()->count();
        $pinnedPosts = Post::pinned()->count();
        
        // Recent activity
        $recentActivity = collect();
        
        // Add recent users
        $recentUsers = User::latest()->take(5)->get()->map(function($user) {
            return [
                'type' => 'user',
                'action' => 'registered',
                'item' => $user,
                'date' => $user->created_at
            ];
        });
        
        // Add recent tasks
        $recentTasks = Task::latest()->take(5)->get()->map(function($task) {
            return [
                'type' => 'task',
                'action' => $task->is_done ? 'completed' : 'created',
                'item' => $task,
                'date' => $task->updated_at
            ];
        });
        
        // Add recent posts
        $recentPosts = Post::latest()->take(5)->get()->map(function($post) {
            return [
                'type' => 'post',
                'action' => 'created',
                'item' => $post,
                'date' => $post->created_at
            ];
        });
        
        $recentActivity = $recentUsers->concat($recentTasks)->concat($recentPosts)
            ->sortByDesc('date')
            ->take(10);

        return view('admin.analytics', compact(
            'totalUsers',
            'adminUsers', 
            'regularUsers',
            'totalTasks',
            'completedTasks',
            'pendingTasks',
            'taskCompletionRate',
            'totalPosts',
            'announcements',
            'importantPosts',
            'pinnedPosts',
            'recentActivity'
        ));
    }

    public function toggleTaskArchive(Task $task)
    {
        $task->toggleArchive();
        
        return back()->with('success', $task->isArchived() ? 'Task archived successfully!' : 'Task unarchived successfully!');
    }
}
