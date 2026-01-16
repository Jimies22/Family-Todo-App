<x-app-layout>
    <!-- Success Message -->
    @if(session('success'))
        <div class="fixed top-20 left-1/2 transform -translate-x-1/2 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
            {{ session('success') }}
        </div>
    @endif

    <!-- Feed Header -->
    <div class="card p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Family Feed 📢</h1>
                <p class="text-gray-600 mt-1">Stay connected with your family's updates and achievements</p>
            </div>
        </div>
    </div>

    <!-- Posts -->
    <div class="space-y-6">
        @forelse ($posts as $post)
            <!-- Post Card -->
            <div class="card overflow-hidden card-hover" x-data="{ showComments: false }">
                <!-- Post Header -->
                <div class="p-4 border-b border-gray-100 flex items-center justify-between">
                    <div class="flex items-center space-x-3 flex-1">
                        <img class="user-avatar" src="https://ui-avatars.com/api/?name={{ $post->user->name }}&background=E4405F&color=fff" alt="{{ $post->user->name }}">
                        <div class="flex-1">
                            <h3 class="font-bold text-gray-900">{{ $post->user->name }}</h3>
                            <p class="text-xs text-gray-600">{{ $post->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        @if($post->is_pinned)
                            <span class="badge-red">📌 Pinned</span>
                        @endif
                        @if($post->is_featured)
                            <span class="badge-red">⭐ Featured</span>
                        @endif
                        <span class="text-sm font-medium px-2 py-1 rounded-full
                            @if($post->type === 'announcement') bg-blue-100 text-blue-800
                            @elseif($post->type === 'important') bg-red-100 text-red-800
                            @elseif($post->type === 'task_summary') bg-green-100 text-green-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ $post->getTypeLabel() }}
                        </span>
                    </div>
                </div>

                <!-- Post Content -->
                <div class="p-6">
                    @if($post->type === 'task_summary' && $post->getTaskList())
                        <!-- Task Summary View -->
                        <h2 class="text-xl font-bold text-gray-900 mb-4">
                            {{ $post->title ?: $post->user->name . "'s Task Summary" }}
                        </h2>
                        
                        <p class="text-gray-700 mb-4">
                            {!! nl2br(e($post->content)) !!}
                        </p>

                        <div class="bg-gray-50 rounded-lg p-4 mb-4 border border-gray-200">
                            <h4 class="font-bold text-gray-900 mb-3">📋 Task Progress:</h4>
                            <div class="space-y-2 mb-3">
                                @foreach($post->getTaskList() as $task)
                                    <div class="flex items-center space-x-3">
                                        <span class="text-lg">
                                            @if($task['completed']) ✅ @else ⭕ @endif
                                        </span>
                                        <span class="{{ $task['completed'] ? 'line-through text-gray-500' : 'text-gray-700' }}">
                                            {{ $task['title'] }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                            
                            @if($post->getTotalTaskCount() > 0)
                                <div class="mt-3 pt-3 border-t border-gray-200">
                                    <div class="flex justify-between text-sm text-gray-600 mb-2">
                                        <span class="font-medium">{{ $post->getCompletedTaskCount() }}/{{ $post->getTotalTaskCount() }} completed</span>
                                        <span class="font-bold text-green-600">{{ $post->getTaskProgressPercentage() }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-300 rounded-full h-2">
                                        <div class="bg-gradient-to-r from-red-500 to-orange-500 h-2 rounded-full transition-all" style="width: {{ $post->getTaskProgressPercentage() }}%"></div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @else
                        <!-- Regular Post View -->
                        <h2 class="text-xl font-bold text-gray-900 mb-2">
                            {{ $post->title ?: 'Untitled Post' }}
                        </h2>
                        
                        <p class="text-gray-700 whitespace-pre-wrap mb-4">
                            {!! nl2br(e($post->content)) !!}
                        </p>

                        @if($post->expires_at)
                            <div class="text-sm font-medium mb-4 flex items-center space-x-2
                                @if($post->expires_at->isPast()) text-red-600 @else text-orange-600 @endif">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 102 0V6z" clip-rule="evenodd"></path>
                                </svg>
                                <span>
                                    ⏰ {{ $post->expires_at->format('M d, Y') }}
                                    @if($post->expires_at->isPast()) (Expired) @endif
                                </span>
                            </div>
                        @endif
                    @endif
                </div>

                <!-- Post Footer - Reactions & Comments -->
                <div class="px-6 py-3 border-t border-gray-100">
                    <div class="flex items-center justify-between text-sm text-gray-600 mb-4">
                        <span>{{ $post->reactions->count() }} reaction{{ $post->reactions->count() !== 1 ? 's' : '' }}</span>
                        <span>{{ $post->comments->count() }} comment{{ $post->comments->count() !== 1 ? 's' : '' }}</span>
                    </div>

                    <!-- Reactions Bar -->
                    <div class="flex items-center space-x-2 mb-4 p-2 bg-gray-50 rounded-lg">
                        @foreach (['like' => '👍', 'love' => '❤️', 'haha' => '😂'] as $type => $emoji)
                            <button type="button"
                                    class="btn-react flex-1 hover:bg-white hover:scale-105 transition px-3 py-2 rounded-lg"
                                    data-post-id="{{ $post->id }}"
                                    data-type="{{ $type }}">
                                <span class="text-lg">{{ $emoji }}</span>
                                <span class="text-xs text-gray-600 ml-1" id="reaction-count-{{ $type }}-{{ $post->id }}">
                                    {{ $post->reactions->where('type', $type)->count() }}
                                </span>
                            </button>
                        @endforeach
                    </div>

                    <!-- Comment Toggle & View -->
                    <button @click="showComments = !showComments" class="w-full text-center py-2 text-gray-700 font-medium hover:bg-gray-50 rounded-lg transition">
                        <span x-show="!showComments">💬 View Comments</span>
                        <span x-show="showComments">🙈 Hide Comments</span>
                    </button>
                </div>

                <!-- Comments Section -->
                <div x-show="showComments" x-transition class="border-t border-gray-100 bg-gray-50">
                    <div class="p-4 space-y-4">
                        <!-- Existing Comments -->
                        @if($post->comments->count() > 0)
                            <div class="space-y-3">
                                @foreach ($post->comments as $comment)
                                    <div class="flex space-x-3">
                                        <img class="w-8 h-8 rounded-full" src="https://ui-avatars.com/api/?name={{ $comment->user->name }}&background=E4405F&color=fff" alt="{{ $comment->user->name }}">
                                        <div class="flex-1 bg-white rounded-lg p-3">
                                            <div class="flex items-center space-x-2">
                                                <span class="font-semibold text-gray-900 text-sm">{{ $comment->user->name }}</span>
                                                <span class="text-xs text-gray-600">{{ $comment->created_at->diffForHumans() }}</span>
                                            </div>
                                            <p class="text-gray-700 text-sm mt-1">{{ $comment->content }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-center text-gray-500 py-4">No comments yet. Be the first to comment!</p>
                        @endif

                        <!-- Add Comment Form -->
                        <form action="{{ route('comments.store') }}" method="POST" class="flex space-x-3 pt-3 border-t border-gray-200">
                            @csrf
                            <input type="hidden" name="post_id" value="{{ $post->id }}">
                            <img class="w-8 h-8 rounded-full" src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}&background=E4405F&color=fff" alt="{{ auth()->user()->name }}">
                            <div class="flex-1 flex space-x-2">
                                <input type="text" name="content" placeholder="Write a comment..." class="flex-1 bg-white border border-gray-300 rounded-full px-4 py-2 text-sm focus:outline-none focus:border-red-500" required>
                                <button type="submit" class="icon-button text-red-500 hover:bg-red-100">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5.951-1.429 5.951 1.429a1 1 0 001.169-1.409l-7-14z"></path>
                                    </svg>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <!-- Empty State -->
            <div class="card p-12 text-center">
                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">No posts yet</h3>
                <p class="text-gray-600 mb-6">Be the first to share an announcement, important notice, or task summary with your family!</p>
                <a href="{{ route('dashboard') }}" class="btn-primary inline-block">
                    Create Your First Post
                </a>
            </div>
        @endforelse
    </div>
</x-app-layout>
