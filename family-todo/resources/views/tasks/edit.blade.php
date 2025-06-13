@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto mt-8">
    <h1 class="text-2xl font-bold mb-4">Edit Task</h1>

    <form action="{{ route('tasks.update', $task) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block font-medium">Title</label>
            <input type="text" name="title" value="{{ old('title', $task->title) }}" class="w-full border rounded p-2">
            @error('title') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block font-medium">Due Date</label>
            <input type="date" name="due_date" value="{{ old('due_date', $task->due_date->format('Y-m-d')) }}" class="w-full border rounded p-2">
            @error('due_date') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
        </div>

        <div class="text-right">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Update Task</button>
        </div>
    </form>
</div>
@endsection
