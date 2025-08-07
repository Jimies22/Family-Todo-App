<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Success/Error Messages -->
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Welcome Message -->
            <div>
                <h1 class="text-2xl font-semibold text-gray-800">
                    Hello, {{ Auth::user()->name }} 👋
                </h1>
                <p class="text-gray-600">Here's what's on your plate today.</p>
            </div>

            <!-- Clear Archived Tasks Button -->
            <form method="POST" action="{{ route('tasks.clearArchived') }}">
                @csrf
                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                    Clear Archived Tasks
                </button>
            </form>
            <a href="{{ route('tasks.archived') }}" class="inline-block bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 mt-2">
        📁 View Archived Tasks
    </a>

            <!-- Task Overview Cards -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="bg-white shadow rounded-xl p-4 text-center">
                    <h2 class="text-lg font-bold text-gray-700">Today</h2>
                    <p class="text-blue-600 text-xl font-semibold">{{ $todayCount }}</p>
                </div>
                <div class="bg-white shadow rounded-xl p-4 text-center">
                    <h2 class="text-lg font-bold text-gray-700">This Week</h2>
                    <p class="text-purple-600 text-xl font-semibold">{{ $weekCount }}</p>
                </div>  
                <div class="bg-white shadow rounded-xl p-4 text-center">
                    <h2 class="text-lg font-bold text-gray-700">Done</h2>
                    <p class="text-green-600 text-xl font-semibold">{{ $doneCount }}</p>
                </div>
                <div class="bg-white shadow rounded-xl p-4 text-center">
                    <h2 class="text-lg font-bold text-gray-700">Pending</h2>
                    <p class="text-red-600 text-xl font-semibold">{{ $pendingCount }}</p>
                </div>
            </div>

             @if ($pendingCount == 0 && $doneCount > 0)

                <div class="text-center p-6 bg-green-100 rounded-lg">
                    🎉 All tasks completed! Start fresh!
                    <div class="mt-4 flex justify-center items-center space-x-2">
                        <a href="{{ route('tasks.create') }}"
                           class="bg-blue-600 text-white px-4 py-2 rounded-xl hover:bg-blue-700">
                            ➕ Start New Task
                        </a>
                        <form method="POST" action="{{ route('tasks.clearArchived') }}">
                            @csrf
                            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                                🗑️ Clear Completed Tasks
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <!-- Active Tasks Table -->
                <div class="bg-white shadow rounded-xl overflow-hidden">
                    <table class="min-w-full text-sm text-left text-gray-700">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-3">Title</th>
                                <th class="px-4 py-3">Due Date</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($tasks->whereNull('archived_at')->where('is_done', false) as $task)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-4 py-3">{{ $task->title }}</td>
                                    <td class="px-4 py-3">{{ $task->due_date->format('M d, Y') }}</td>
                                    <td class="px-4 py-3">
                                        <span class="text-red-600 font-medium">Pending</span>
                                    </td>
                                    <td class="px-4 py-3 text-right space-x-2">
                                        <form action="{{ route('tasks.markDone', $task) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button class="text-green-600 hover:underline">✔️ Done</button>
                                        </form>
                                        <a href="{{ route('tasks.edit', $task) }}" class="text-blue-600 hover:underline">✏️ Edit</a>
                                        <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this task?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="text-red-600 hover:underline">🗑️ Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-4 text-center text-gray-500">No pending tasks. Add one!</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @endif

            <!-- Add Task Button and Post to Feed - Only show when there are pending tasks -->
            @if ($pendingCount > 0)
                <!-- Add Task Button -->
                <div>
                    <a href="{{ route('tasks.create') }}"
                       class="bg-blue-600 text-white px-4 py-2 rounded-xl hover:bg-blue-700">
                        + Add New Task
                    </a>
                </div>

                <!-- Post to Feed -->
                <div>
                    <form action="{{ route('posts.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="content" value="{{ $tasks->toJson() }}">
                        <x-primary-button class="bg-purple-600 hover:bg-purple-700 mt-2">
                            📤 Post to Feed
                        </x-primary-button>
                    </form>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
