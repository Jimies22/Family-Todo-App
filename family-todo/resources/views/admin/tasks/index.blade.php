<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Task Management') }}
            </h2>
            <a href="{{ route('tasks.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Add New Task
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
                        <form method="GET" action="{{ route('admin.tasks') }}" class="space-y-3">
                            <div class="flex flex-wrap items-end gap-3">
                                <!-- Search -->
                                <div class="flex-1 min-w-[200px]">
                                    <label for="search" class="block text-xs font-medium text-gray-700 mb-1">Search</label>
                                    <div class="relative">
                                        <input type="text" 
                                               name="search" 
                                               id="search" 
                                               value="{{ request('search') }}"
                                               placeholder="Search by task title..."
                                               class="w-full pl-8 pr-3 py-1.5 text-sm border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                        <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                <!-- Status Filter -->
                                <div class="min-w-[120px]">
                                    <label for="status" class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                                    <select name="status" 
                                            id="status"
                                            class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="">All Status</option>
                                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                                    </select>
                                </div>

                                <!-- Archive Status Filter -->
                                <div class="min-w-[140px]">
                                    <label for="archive_status" class="block text-xs font-medium text-gray-700 mb-1">Archive</label>
                                    <select name="archive_status" 
                                            id="archive_status"
                                            class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="active" {{ request('archive_status', 'active') === 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="archived" {{ request('archive_status') === 'archived' ? 'selected' : '' }}>Archived</option>
                                        <option value="" {{ request('archive_status') === '' ? 'selected' : '' }}>All</option>
                                    </select>
                                </div>

                                <!-- User Filter -->
                                <div class="min-w-[140px]">
                                    <label for="user_id" class="block text-xs font-medium text-gray-700 mb-1">Assigned To</label>
                                    <select name="user_id" 
                                            id="user_id"
                                            class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="">All Users</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }}
                                            </option>
                                        @endforeach
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
                                        <option value="is_done" {{ request('sort_by') === 'is_done' ? 'selected' : '' }}>Status</option>
                                        <option value="due_date" {{ request('sort_by') === 'due_date' ? 'selected' : '' }}>Due Date</option>
                                    </select>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex gap-2">
                                    <button type="submit" 
                                            class="bg-indigo-500 hover:bg-indigo-700 text-white font-medium py-1.5 px-3 rounded text-sm">
                                        Search
                                    </button>
                                    <a href="{{ route('admin.tasks') }}" 
                                       class="bg-gray-500 hover:bg-gray-700 text-white font-medium py-1.5 px-3 rounded text-sm">
                                        Clear
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Quick Filters -->
                    @if(request('search') || request('status') || request('archive_status') || request('user_id') || request('sort_by') !== 'created_at')
                        <div class="mb-4">
                            <div class="flex flex-wrap gap-2">
                                @if(request('search'))
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                        Search: "{{ request('search') }}"
                                        <a href="{{ route('admin.tasks', request()->except('search')) }}" class="ml-2 text-blue-600 hover:text-blue-800">×</a>
                                    </span>
                                @endif
                                @if(request('status'))
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        Status: {{ ucfirst(request('status')) }}
                                        <a href="{{ route('admin.tasks', request()->except('status')) }}" class="ml-2 text-green-600 hover:text-green-800">×</a>
                                    </span>
                                @endif
                                @if(request('archive_status'))
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-orange-100 text-orange-800">
                                        Archive: {{ ucfirst(request('archive_status')) }}
                                        <a href="{{ route('admin.tasks', request()->except('archive_status')) }}" class="ml-2 text-orange-600 hover:text-orange-800">×</a>
                                    </span>
                                @endif
                                @if(request('user_id'))
                                    @php $selectedUser = $users->find(request('user_id')); @endphp
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                                        User: {{ $selectedUser ? $selectedUser->name : 'Unknown' }}
                                        <a href="{{ route('admin.tasks', request()->except('user_id')) }}" class="ml-2 text-purple-600 hover:text-purple-800">×</a>
                                    </span>
                                @endif
                                @if(request('sort_by') !== 'created_at')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                        Sort: {{ ucfirst(str_replace('_', ' ', request('sort_by'))) }}
                                        <a href="{{ route('admin.tasks', request()->except('sort_by')) }}" class="ml-2 text-yellow-600 hover:text-yellow-800">×</a>
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Results Summary -->
                    <div class="mb-4 text-sm text-gray-600">
                        Showing {{ $tasks->firstItem() ?? 0 }} to {{ $tasks->lastItem() ?? 0 }} of {{ $tasks->total() }} tasks
                        @if(request('search') || request('status') || request('archive_status') || request('user_id'))
                            <span class="text-indigo-600">(filtered)</span>
                        @endif
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Task
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Assigned To
                                    </th>
                                                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Archive
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Due Date
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
                                @forelse ($tasks as $task)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $task->title }}</div>
                                            @if($task->description)
                                                <div class="text-sm text-gray-500 truncate max-w-xs">{{ $task->description }}</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-8 w-8">
                                                    <div class="h-8 w-8 rounded-full bg-gray-300 flex items-center justify-center">
                                                        <span class="text-sm font-medium text-gray-700">
                                                            {{ strtoupper(substr($task->user->name, 0, 1)) }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="ml-3">
                                                    <div class="text-sm font-medium text-gray-900">{{ $task->user->name }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($task->isCompleted())
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    Completed
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    Pending
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($task->isArchived())
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    Archived
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    Active
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $task->due_date ? $task->due_date->format('M d, Y') : '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $task->created_at->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('tasks.edit', $task) }}" 
                                                   class="text-indigo-600 hover:text-indigo-900 bg-indigo-100 hover:bg-indigo-200 px-3 py-1 rounded-md text-sm">
                                                    Edit
                                                </a>
                                                <form action="{{ route('admin.tasks.toggle-archive', $task) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" 
                                                            class="{{ $task->isArchived() ? 'text-green-600 hover:text-green-900 bg-green-100 hover:bg-green-200' : 'text-orange-600 hover:text-orange-900 bg-orange-100 hover:bg-orange-200' }} px-3 py-1 rounded-md text-sm"
                                                            onclick="return confirm('Are you sure you want to {{ $task->isArchived() ? 'unarchive' : 'archive' }} this task?')">
                                                        {{ $task->isArchived() ? 'Unarchive' : 'Archive' }}
                                                    </button>
                                                </form>
                                                <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="text-red-600 hover:text-red-900 bg-red-100 hover:bg-red-200 px-3 py-1 rounded-md text-sm"
                                                            onclick="return confirm('Are you sure you want to delete this task?')">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                                                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                        @if(request('search') || request('status') || request('archive_status') || request('user_id'))
                                            No tasks found matching your criteria.
                                        @else
                                            No tasks found.
                                        @endif
                                    </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $tasks->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 