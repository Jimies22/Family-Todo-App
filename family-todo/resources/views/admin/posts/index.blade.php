<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Content Management') }}
            </h2>
            <a href="{{ route('admin.posts.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Create New Post
            </a>
        </div>
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

                    <!-- Search and Filter Form -->
                    <div class="mb-6 bg-gray-50 p-3 rounded-lg">
                        <form method="GET" action="{{ route('admin.posts') }}" class="space-y-3">
                            <div class="flex flex-wrap items-end gap-3">
                                <!-- Search -->
                                <div class="flex-1 min-w-[200px]">
                                    <label for="search" class="block text-xs font-medium text-gray-700 mb-1">Search</label>
                                    <div class="relative">
                                        <input type="text" 
                                               name="search" 
                                               id="search" 
                                               value="{{ request('search') }}"
                                               placeholder="Search by title or content..."
                                               class="w-full pl-8 pr-3 py-1.5 text-sm border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                        <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                <!-- Type Filter -->
                                <div class="min-w-[140px]">
                                    <label for="type" class="block text-xs font-medium text-gray-700 mb-1">Type</label>
                                    <select name="type" 
                                            id="type"
                                            class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="">All Types</option>
                                        <option value="announcement" {{ request('type') === 'announcement' ? 'selected' : '' }}>Announcement</option>
                                        <option value="task_summary" {{ request('type') === 'task_summary' ? 'selected' : '' }}>Task Summary</option>
                                        <option value="important" {{ request('type') === 'important' ? 'selected' : '' }}>Important</option>
                                        <option value="general" {{ request('type') === 'general' ? 'selected' : '' }}>General</option>
                                    </select>
                                </div>

                                <!-- Status Filter -->
                                <div class="min-w-[120px]">
                                    <label for="status" class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                                    <select name="status" 
                                            id="status"
                                            class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="">All Status</option>
                                        <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                                        <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
                                        <option value="archived" {{ request('status') === 'archived' ? 'selected' : '' }}>Archived</option>
                                    </select>
                                </div>

                                <!-- Featured Filter -->
                                <div class="min-w-[120px]">
                                    <label for="featured" class="block text-xs font-medium text-gray-700 mb-1">Featured</label>
                                    <select name="featured" 
                                            id="featured"
                                            class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="">All Posts</option>
                                        <option value="pinned" {{ request('featured') === 'pinned' ? 'selected' : '' }}>Pinned</option>
                                        <option value="featured" {{ request('featured') === 'featured' ? 'selected' : '' }}>Featured</option>
                                    </select>
                                </div>

                                <!-- Sort By -->
                                <div class="min-w-[120px]">
                                    <label for="sort_by" class="block text-xs font-medium text-gray-700 mb-1">Sort By</label>
                                    <select name="sort_by" 
                                            id="sort_by"
                                            class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="created_at" {{ request('sort_by', 'created_at') === 'created_at' ? 'selected' : '' }}>Created</option>
                                        <option value="title" {{ request('sort_by') === 'title' ? 'selected' : '' }}>Title</option>
                                        <option value="type" {{ request('sort_by') === 'type' ? 'selected' : '' }}>Type</option>
                                        <option value="status" {{ request('sort_by') === 'status' ? 'selected' : '' }}>Status</option>
                                        <option value="published_at" {{ request('sort_by') === 'published_at' ? 'selected' : '' }}>Published</option>
                                    </select>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex gap-2">
                                    <button type="submit" 
                                            class="bg-indigo-500 hover:bg-indigo-700 text-white font-medium py-1.5 px-3 rounded text-sm">
                                        Search
                                    </button>
                                    <a href="{{ route('admin.posts') }}" 
                                       class="bg-gray-500 hover:bg-gray-700 text-white font-medium py-1.5 px-3 rounded text-sm">
                                        Clear
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Quick Filters -->
                    @if(request('search') || request('type') || request('status') || request('featured') || request('sort_by') !== 'created_at')
                        <div class="mb-4">
                            <div class="flex flex-wrap gap-2">
                                @if(request('search'))
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                        Search: "{{ request('search') }}"
                                        <a href="{{ route('admin.posts', request()->except('search')) }}" class="ml-2 text-blue-600 hover:text-blue-800">×</a>
                                    </span>
                                @endif
                                @if(request('type'))
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        Type: {{ ucfirst(request('type')) }}
                                        <a href="{{ route('admin.posts', request()->except('type')) }}" class="ml-2 text-green-600 hover:text-green-800">×</a>
                                    </span>
                                @endif
                                @if(request('status'))
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                                        Status: {{ ucfirst(request('status')) }}
                                        <a href="{{ route('admin.posts', request()->except('status')) }}" class="ml-2 text-purple-600 hover:text-purple-800">×</a>
                                    </span>
                                @endif
                                @if(request('featured'))
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                        Featured: {{ ucfirst(request('featured')) }}
                                        <a href="{{ route('admin.posts', request()->except('featured')) }}" class="ml-2 text-yellow-600 hover:text-yellow-800">×</a>
                                    </span>
                                @endif
                                @if(request('sort_by') !== 'created_at')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-orange-100 text-orange-800">
                                        Sort: {{ ucfirst(str_replace('_', ' ', request('sort_by'))) }}
                                        <a href="{{ route('admin.posts', request()->except('sort_by')) }}" class="ml-2 text-orange-600 hover:text-orange-800">×</a>
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Results Summary -->
                    <div class="mb-4 text-sm text-gray-600">
                        Showing {{ $posts->firstItem() ?? 0 }} to {{ $posts->lastItem() ?? 0 }} of {{ $posts->total() }} posts
                        @if(request('search') || request('type') || request('status') || request('featured'))
                            <span class="text-indigo-600">(filtered)</span>
                        @endif
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Title
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Type
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Author
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Created
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($posts as $post)
                                    <tr class="hover:bg-gray-50 {{ $post->is_pinned ? 'bg-yellow-50' : '' }}">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                @if($post->is_pinned)
                                                    <svg class="h-4 w-4 text-yellow-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z"></path>
                                                    </svg>
                                                @endif
                                                @if($post->is_featured)
                                                    <svg class="h-4 w-4 text-purple-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                    </svg>
                                                @endif
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $post->title ?: 'Untitled' }}
                                                    </div>
                                                    <div class="text-sm text-gray-500 truncate max-w-xs">
                                                        {{ Str::limit($post->content, 100) }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $post->getTypeColor() }}">
                                                {{ $post->getTypeLabel() }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $post->getStatusColor() }}">
                                                {{ $post->getStatusLabel() }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $post->user->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $post->created_at->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('admin.posts.edit', $post) }}" 
                                                   class="text-indigo-600 hover:text-indigo-900 bg-indigo-100 hover:bg-indigo-200 px-3 py-1 rounded-md text-sm">
                                                    Edit
                                                </a>
                                                
                                                <form action="{{ route('admin.posts.toggle-pin', $post) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" 
                                                            class="text-yellow-600 hover:text-yellow-900 bg-yellow-100 hover:bg-yellow-200 px-3 py-1 rounded-md text-sm">
                                                        {{ $post->is_pinned ? 'Unpin' : 'Pin' }}
                                                    </button>
                                                </form>
                                                
                                                <form action="{{ route('admin.posts.toggle-featured', $post) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" 
                                                            class="text-purple-600 hover:text-purple-900 bg-purple-100 hover:bg-purple-200 px-3 py-1 rounded-md text-sm">
                                                        {{ $post->is_featured ? 'Unfeature' : 'Feature' }}
                                                    </button>
                                                </form>
                                                
                                                <form action="{{ route('admin.posts.delete', $post) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="text-red-600 hover:text-red-900 bg-red-100 hover:bg-red-200 px-3 py-1 rounded-md text-sm"
                                                            onclick="return confirm('Are you sure you want to delete this post?')">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                            @if(request('search') || request('type') || request('status') || request('featured'))
                                                No posts found matching your criteria.
                                            @else
                                                No posts found.
                                            @endif
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $posts->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 