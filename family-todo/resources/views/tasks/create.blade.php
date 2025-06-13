<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add New Task') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-md mx-auto bg-white p-6 rounded shadow">
            <form method="POST" action="{{ route('tasks.store') }}">
                @csrf

                <div class="mb-4">
                    <label for="title" class="block text-sm font-medium text-gray-700">Task Title</label>
                    <input type="text" name="title" id="title" class="w-full border border-gray-300 p-2 rounded" required>
                </div>

                <div class="mb-4">
                    <label for="due_date" class="block text-sm font-medium text-gray-700">Due Date</label>
                    <input type="date" name="due_date" id="due_date" class="w-full border border-gray-300 p-2 rounded" required>
                </div>

                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Add Task</button>
            </form>
        </div>
    </div>
</x-app-layout>
