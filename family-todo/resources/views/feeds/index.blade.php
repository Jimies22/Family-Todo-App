<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Feed') }}
        </h2>
    </x-slot>

    <div class="py-4 max-w-4xl mx-auto">
        @forelse ($posts as $post)
    @php $content = json_decode($post->content, true); @endphp

    {{-- 🟡 Summary Weekly Post --}}
    @if (is_array($content) && isset($content['message']))
        <div class="bg-yellow-100 rounded p-4 mb-4">
            <p class="font-bold text-lg">{{ $content['message'] }}</p>
            <p>{{ $content['summary'] }}</p>

            @if (!empty($content['tasks']))
                <ul class="mt-2 list-disc pl-5 text-sm text-gray-700">
                    @foreach ($content['tasks'] as $task)
                        <li>{{ $task }}</li>
                    @endforeach
                </ul>
            @endif
            <small class="text-gray-500">Posted {{ $post->created_at->diffForHumans() }}</small>
        </div>
    @else
        {{-- 🟢 Normal Task Post --}}
        <div class="bg-white p-4 rounded-xl shadow mb-4">
            <div class="font-bold text-lg text-indigo-700">
                {{ $post->user->name }}'s Weekly Tasks
            </div>

            <ul class="mt-2 list-disc list-inside text-gray-700">
                @foreach (json_decode($post->content) as $task)
                    <li>
                        <span class="font-semibold">{{ $task->title }}</span>
                        - {{ \Carbon\Carbon::parse($task->due_date)->format('D, M d') }}
                        @if ($task->is_done) ✅ @else ❌ @endif
                    </li>
                @endforeach
            </ul>

            <small class="text-gray-500">Posted {{ $post->created_at->diffForHumans() }}</small>

            {{-- 💬 Comments Toggle --}}
            <div x-data="{ showComments: false }" class="ml-4 mt-2">
                <button @click="showComments = !showComments" class="text-sm text-blue-600 hover:underline">
                    <span x-show="!showComments">💬 View Comments ({{ $post->comments->count() }})</span>
                    <span x-show="showComments">🙈 Hide Comments</span>
                </button>

                <div x-show="showComments" x-transition class="mt-2 border-l pl-4">
                    <h4 class="text-sm font-semibold text-gray-600 mb-2">Comments:</h4>
                    @forelse ($post->comments as $comment)
                        <p class="text-sm text-gray-800 mb-1">
                            <strong>{{ $comment->user->name }}:</strong> {{ $comment->content }}
                        </p>
                    @empty
                        <p class="text-gray-500 text-sm">No comments yet.</p>
                    @endforelse
                </div>
            </div>

            {{-- 💬 Add Comment --}}
            <form action="{{ route('comments.store') }}" method="POST" class="mt-2 ml-4">
                @csrf
                <input type="hidden" name="post_id" value="{{ $post->id }}">
                <input type="text" name="content" placeholder="Write a comment..." class="w-full border rounded px-2 py-1">
                <button class="mt-1 text-sm text-blue-600 hover:underline">Comment</button>
            </form>

            {{-- ❤️ Reactions --}}
            <div class="mt-2">
                @foreach (['like' => '👍', 'love' => '❤️', 'haha' => '😂'] as $type => $emoji)
                    <button type="button"
                            class="btn-react mr-2 hover:scale-110"
                            data-post-id="{{ $post->id }}"
                            data-type="{{ $type }}">
                        {{ $emoji }}
                        <span class="text-xs text-gray-600" id="reaction-count-{{ $type }}-{{ $post->id }}">
                            {{ $post->reactions->where('type', $type)->count() }}
                        </span>
                    </button>
                @endforeach
            </div>
        </div>
    @endif
@empty
    <div class="text-gray-600 text-center py-10">
        No to-do posts yet. Be the first to post!
    </div>
@endforelse
    </div>
</x-app-layout>
