<x-app-layout>
    <!-- Success/Error Messages -->
    @if (session('success'))
        <div class="fixed top-20 left-1/2 transform -translate-x-1/2 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="fixed top-20 left-1/2 transform -translate-x-1/2 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Welcome Section -->
    <div class="card p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">
                    Welcome back, {{ Auth::user()->name }}! 👋
                </h1>
                <p class="text-gray-600 mt-1">Here's your task summary for today</p>
            </div>
            <a href="{{ route('tasks.create') }}" class="btn-primary flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>New Task</span>
            </a>
        </div>
    </div>

    <!-- Task Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="card p-8 border-t-4 border-blue-500 card-hover hover:shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium uppercase tracking-wide">Today's Tasks</p>
                    <p class="text-4xl font-bold text-gray-900 mt-3">{{ $todayCount }}</p>
                </div>
                <div class="w-16 h-16 bg-blue-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-calendar-day text-2xl text-blue-500"></i>
                </div>
            </div>
        </div>

        <div class="card p-8 border-t-4 border-purple-500 card-hover hover:shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium uppercase tracking-wide">This Week</p>
                    <p class="text-4xl font-bold text-gray-900 mt-3">{{ $weekCount }}</p>
                </div>
                <div class="w-16 h-16 bg-purple-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-calendar-week text-2xl text-purple-500"></i>
                </div>
            </div>
        </div>

        <div class="card p-8 border-t-4 border-green-500 card-hover hover:shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium uppercase tracking-wide">Completed</p>
                    <p class="text-4xl font-bold text-gray-900 mt-3">{{ $doneCount }}</p>
                </div>
                <div class="w-16 h-16 bg-green-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-check-circle text-2xl text-green-500"></i>
                </div>
            </div>
        </div>

        <div class="card p-8 border-t-4 border-red-500 card-hover hover:shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium uppercase tracking-wide">Pending</p>
                    <p class="text-4xl font-bold text-gray-900 mt-3">{{ $pendingCount }}</p>
                </div>
                <div class="w-16 h-16 bg-red-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-exclamation-circle text-2xl text-red-500"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Completion Celebration -->
    @if ($pendingCount == 0 && $doneCount > 0)
        <div class="card p-8 bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-green-900">🎉 Amazing Work!</h2>
                    <p class="text-green-700 mt-1">You've completed all your tasks. Time to celebrate!</p>
                </div>
                <div class="flex space-x-3">
                    <form method="POST" action="{{ route('tasks.clearArchived') }}" class="inline">
                        @csrf
                        <button type="submit" class="btn-secondary">Clear Completed</button>
                    </form>
                </div>
            </div>
        </div>
    @else
        <!-- Active Tasks Section -->
        <div class="card overflow-hidden mb-6">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-900">📋 Your Tasks</h2>
            </div>
            
            @if ($tasks->whereNull('archived_at')->where('is_done', false)->count() === 0)
                <div class="p-12 text-center">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <p class="text-gray-600 text-lg font-medium">No pending tasks</p>
                    <p class="text-gray-500 text-sm mt-1">Create a new task to get started</p>
                </div>
            @else
                <div class="divide-y">
                    @foreach ($tasks->whereNull('archived_at')->where('is_done', false) as $task)
                        <div class="p-4 hover:bg-gray-50 transition flex items-center justify-between">
                            <div class="flex items-center space-x-4 flex-1">
                                <div>
                                    <h3 class="font-semibold text-gray-900">{{ $task->title }}</h3>
                                    <p class="text-sm text-gray-600 mt-1">Due: {{ $task->due_date->format('M d, Y') }}</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <form action="{{ route('tasks.markDone', $task) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button class="icon-button text-green-600 hover:bg-green-100" title="Mark as done">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </button>
                                </form>
                                <a href="{{ route('tasks.edit', $task) }}" class="icon-button text-blue-600 hover:bg-blue-100" title="Edit task">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                                <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="inline" onsubmit="return confirm('Delete this task?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="icon-button text-red-600 hover:bg-red-100" title="Delete task">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    @endif

    <!-- Post to Feed Button -->
    @if ($pendingCount > 0 || $doneCount > 0)
        <div class="card p-6 bg-gradient-to-r from-red-50 to-orange-50 border-l-4 border-red-500">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-gray-900">Share your progress</h3>
                    <p class="text-gray-600 text-sm mt-1">Post your task summary to the family feed</p>
                </div>
                <form action="{{ route('posts.store') }}" method="POST" class="inline">
                    @csrf
                    <input type="hidden" name="type" value="task_summary">
                    <input type="hidden" name="title" value="{{ Auth::user()->name }}'s Task Update">
                    <input type="hidden" name="content" value="Check out my task progress!">
                    <button type="submit" class="btn-primary flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3v-6"></path>
                        </svg>
                        <span>Post to Feed</span>
                    </button>
                </form>
            </div>
        </div>
    @endif

    <!-- Archived Tasks Link -->
    <div class="mt-6 text-center">
        <a href="{{ route('tasks.archived') }}" class="text-red-500 font-medium hover:text-red-700 transition">
            📁 View Archived Tasks
        </a>
    </div>
</x-app-layout>
