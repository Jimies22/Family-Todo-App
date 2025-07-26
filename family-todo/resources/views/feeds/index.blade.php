<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Family Feed') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    @forelse ($posts as $post)
                        @if($post->type === 'task_summary' && $post->getTaskList())
                            {{-- 📋 Task Summary Post --}}
                            <div class="bg-gradient-to-r from-green-50 to-blue-50 p-4 rounded-xl shadow mb-4 border-l-4 border-green-400">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="font-bold text-lg text-green-700">
                                        {{ $post->title ?: $post->user->name . "'s Task Summary" }}
                                    </div>
                                    @if($post->is_pinned)
                                        <span class="text-yellow-600 text-sm">📌 Pinned</span>
                                    @endif
                                </div>

                                <div class="text-gray-700 mb-3">
                                    {!! nl2br(e($post->content)) !!}
                                </div>

                                @if($post->getTaskList())
                                    <div class="bg-white p-3 rounded-lg border">
                                        <h4 class="font-semibold text-gray-800 mb-2">Tasks Completed:</h4>
                                        <ul class="space-y-1">
                                            @foreach($post->getTaskList() as $task)
                                                <li class="flex items-center text-sm">
                                                    @if($task['completed'])
                                                        <span class="text-green-600 mr-2">✅</span>
                                                    @else
                                                        <span class="text-gray-400 mr-2">⭕</span>
                                                    @endif
                                                    <span class="{{ $task['completed'] ? 'line-through text-gray-500' : 'text-gray-700' }}">
                                                        {{ $task['title'] }}
                                                    </span>
                                                </li>
                                            @endforeach
                                        </ul>
                                        
                                        @if($post->getTotalTaskCount() > 0)
                                            <div class="mt-3 pt-2 border-t">
                                                <div class="flex justify-between text-sm text-gray-600">
                                                    <span>Progress: {{ $post->getCompletedTaskCount() }}/{{ $post->getTotalTaskCount() }} tasks</span>
                                                    <span>{{ $post->getTaskProgressPercentage() }}% complete</span>
                                                </div>
                                                <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                                                    <div class="bg-green-500 h-2 rounded-full" style="width: {{ $post->getTaskProgressPercentage() }}%"></div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endif

                                <div class="mt-3 text-sm text-gray-500">
                                    Posted by {{ $post->user->name }} • {{ $post->created_at->diffForHumans() }}
                                </div>
                            </div>
                        @else
                            {{-- 📢 Regular Post (Announcement, Important, General) --}}
                            <div class="bg-white p-4 rounded-xl shadow mb-4 border-l-4 
                                @if($post->type === 'announcement') border-blue-400 bg-blue-50
                                @elseif($post->type === 'important') border-red-400 bg-red-50
                                @else border-gray-400
                                @endif">
                                
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center space-x-2">
                                        <span class="text-sm font-medium px-2 py-1 rounded-full
                                            @if($post->type === 'announcement') bg-blue-100 text-blue-800
                                            @elseif($post->type === 'important') bg-red-100 text-red-800
                                            @elseif($post->type === 'task_summary') bg-green-100 text-green-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ $post->getTypeLabel() }}
                                        </span>
                                        @if($post->is_pinned)
                                            <span class="text-yellow-600 text-sm">📌 Pinned</span>
                                        @endif
                                        @if($post->is_featured)
                                            <span class="text-purple-600 text-sm">⭐ Featured</span>
                                        @endif
                                    </div>
                                </div>

                                <h3 class="font-bold text-lg text-gray-900 mb-2">
                                    {{ $post->title ?: 'Untitled Post' }}
                                </h3>

                                <div class="text-gray-700 mb-3">
                                    {!! nl2br(e($post->content)) !!}
                                </div>

                                @if($post->expires_at)
                                    <div class="text-sm text-gray-500 mb-2">
                                        ⏰ Expires: {{ $post->expires_at->format('M d, Y') }}
                                        @if($post->expires_at->isPast())
                                            <span class="text-red-600 font-medium">(Expired)</span>
                                        @endif
                                    </div>
                                @endif

                                <div class="mt-3 text-sm text-gray-500">
                                    Posted by {{ $post->user->name }} • {{ $post->created_at->diffForHumans() }}
                                </div>
                            </div>
                        @endif

                        {{-- 💬 Comments Section --}}
                        <div x-data="{ showComments: false }" class="ml-4 mb-4">
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
                        <form action="{{ route('comments.store') }}" method="POST" class="ml-4 mb-4">
                            @csrf
                            <input type="hidden" name="post_id" value="{{ $post->id }}">
                            <input type="text" name="content" placeholder="Write a comment..." class="w-full border rounded px-2 py-1">
                            <button class="mt-1 text-sm text-blue-600 hover:underline">Comment</button>
                        </form>

                        {{-- ❤️ Reactions --}}
                        <div class="ml-4 mb-6">
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
                    @empty
                        <div class="text-gray-600 text-center py-10">
                            <p class="text-lg mb-2">No posts yet!</p>
                            <p class="text-sm">Be the first to share an announcement, important notice, or task summary.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
