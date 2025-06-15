<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Welcome Message -->
            <div>
                <h1 class="text-2xl font-semibold text-gray-800">
                    Hello, {{ Auth::user()->name }} 👋
                </h1>
                <p class="text-gray-600">Here’s what’s on your plate today.</p>
            </div>

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

            

            <!-- Task Table -->
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
                        @forelse ($tasks as $task)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-3">{{ $task->title }}</td>
                                <td class="px-4 py-3">{{ $task->due_date->format('M d, Y') }}</td>
                                <td class="px-4 py-3">
                                    @if($task->is_done)
                                        <span class="text-green-600 font-medium">Done</span>
                                    @else
                                        <span class="text-red-600 font-medium">Pending</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right space-x-2">
                                    @if(!$task->is_done)
                                        <form action="{{ route('tasks.markDone', $task) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button class="text-green-600 hover:underline">✔️ Done</button>
                                        </form>
                                    @endif
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
                                <td colspan="4" class="px-4 py-4 text-center text-gray-500">No tasks yet. Add one!</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

                            <!-- Add Task Button -->
                            <div>
                                <a href="{{ route('tasks.create') }}"
                                class="bg-blue-600 text-white px-4 py-2 rounded-xl hover:bg-blue-700">
                                    + Add New Task
                                </a>
                            </div>
                            <div>
                                @if (count($tasks) > 0)
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

    </div>
</x-app-layout>
