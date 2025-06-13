<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Feed') }}
        </h2>
    </x-slot>
@foreach ($posts as $post)
    <div class="bg-white p-4 rounded-xl shadow mb-4">
        <div class="font-bold">{{ $post->user->name }}'s Weekly Tasks</div>
        <ul class="mt-2 list-disc list-inside text-gray-700">
            @foreach (json_decode($post->content) as $task)
                <li>
                    {{ $task->title }} - {{ \Carbon\Carbon::parse($task->due_date)->format('D, M d') }}
                    @if ($task->is_done) ✅ @else ❌ @endif
                </li>
            @endforeach
        </ul>
        <small class="text-gray-500">Posted {{ $post->created_at->diffForHumans() }}</small>
    </div>
@endforeach

</x-app-layout>
