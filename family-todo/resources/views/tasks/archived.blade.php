<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Archived Tasks') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="flex justify-between items-center mb-4">
                <h3 class="text-2xl font-semibold text-gray-700">🗃️ Archived Tasks</h3>
                <a href="{{ route('dashboard') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    ← Back to Dashboard
                </a>
            </div>
            

            @if($archivedTasks->isEmpty())
                <div class="bg-yellow-100 text-yellow-800 p-4 rounded-lg">
                    You have no archived tasks yet.
                </div>
            @else
                <div class="bg-white shadow-md rounded-xl overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200 text-sm text-left text-gray-700">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-6 py-3">Title</th>
                                <th class="px-6 py-3">Due Date</th>
                                <th class="px-6 py-3">Completed At</th>
                                <th class="px-6 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($archivedTasks as $task)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-6 py-4">{{ $task->title }}</td>
                                    <td class="px-6 py-4">{{ $task->due_date->format('M d, Y') }}</td>
                                    <td class="px-6 py-4">{{ $task->updated_at->format('M d, Y H:i') }}</td>
                                    <td class="px-6 py-4 text-right space-x-2">
                                        <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="inline" onsubmit="return confirm('Delete this archived task?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="text-red-600 hover:underline">🗑️ Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
            <!-- Clear Archived Tasks Button -->
            <form method="POST" action="{{ route('tasks.clearArchived') }}">
                @csrf
                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                    Clear Archived Tasks
                </button>
            </form>

        </div>
    </div>
</x-app-layout>
