<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('User Management') }}
            </h2>
            <a href="{{ route('admin.users.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Add New User
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

                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Search and Filter Form -->
                    <div class="mb-6 bg-gray-50 p-3 rounded-lg">
                        <form method="GET" action="{{ route('admin.users') }}" class="space-y-3">
                            <div class="flex flex-wrap items-end gap-3">
                                <!-- Search -->
                                <div class="flex-1 min-w-[200px]">
                                    <label for="search" class="block text-xs font-medium text-gray-700 mb-1">Search</label>
                                    <div class="relative">
                                        <input type="text" 
                                               name="search" 
                                               id="search" 
                                               value="{{ request('search') }}"
                                               placeholder="Search by name or email..."
                                               class="w-full pl-8 pr-3 py-1.5 text-sm border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                        <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                <!-- Role Filter -->
                                <div class="min-w-[120px]">
                                    <label for="role" class="block text-xs font-medium text-gray-700 mb-1">Role</label>
                                    <select name="role" 
                                            id="role"
                                            class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="">All Roles</option>
                                        <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                                        <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>User</option>
                                    </select>
                                </div>

                                <!-- Sort By -->
                                <div class="min-w-[120px]">
                                    <label for="sort_by" class="block text-xs font-medium text-gray-700 mb-1">Sort By</label>
                                    <select name="sort_by" 
                                            id="sort_by"
                                            class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="created_at" {{ request('sort_by', 'created_at') === 'created_at' ? 'selected' : '' }}>Created</option>
                                        <option value="name" {{ request('sort_by') === 'name' ? 'selected' : '' }}>Name</option>
                                        <option value="email" {{ request('sort_by') === 'email' ? 'selected' : '' }}>Email</option>
                                        <option value="is_admin" {{ request('sort_by') === 'is_admin' ? 'selected' : '' }}>Role</option>
                                    </select>
                                </div>

                                <!-- Sort Order -->
                                <div class="min-w-[120px]">
                                    <label for="sort_order" class="block text-xs font-medium text-gray-700 mb-1">Order</label>
                                    <select name="sort_order" 
                                            id="sort_order"
                                            class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="desc" {{ request('sort_order', 'desc') === 'desc' ? 'selected' : '' }}>Desc</option>
                                        <option value="asc" {{ request('sort_order') === 'asc' ? 'selected' : '' }}>Asc</option>
                                    </select>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex gap-2">
                                    <button type="submit" 
                                            class="bg-indigo-500 hover:bg-indigo-700 text-white font-medium py-1.5 px-3 rounded text-sm">
                                        Search
                                    </button>
                                    <a href="{{ route('admin.users') }}" 
                                       class="bg-gray-500 hover:bg-gray-700 text-white font-medium py-1.5 px-3 rounded text-sm">
                                        Clear
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Quick Filters -->
                    @if(request('search') || request('role') || request('sort_by') !== 'created_at' || request('sort_order') !== 'desc')
                        <div class="mb-4">
                            <div class="flex flex-wrap gap-2">
                                @if(request('search'))
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                        Search: "{{ request('search') }}"
                                        <a href="{{ route('admin.users', request()->except('search')) }}" class="ml-2 text-blue-600 hover:text-blue-800">×</a>
                                    </span>
                                @endif
                                @if(request('role'))
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        Role: {{ ucfirst(request('role')) }}
                                        <a href="{{ route('admin.users', request()->except('role')) }}" class="ml-2 text-green-600 hover:text-green-800">×</a>
                                    </span>
                                @endif
                                @if(request('sort_by') !== 'created_at')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                                        Sort: {{ ucfirst(str_replace('_', ' ', request('sort_by'))) }}
                                        <a href="{{ route('admin.users', request()->except('sort_by')) }}" class="ml-2 text-purple-600 hover:text-purple-800">×</a>
                                    </span>
                                @endif
                                @if(request('sort_order') !== 'desc')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                        Order: {{ ucfirst(request('sort_order')) }}
                                        <a href="{{ route('admin.users', request()->except('sort_order')) }}" class="ml-2 text-yellow-600 hover:text-yellow-800">×</a>
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Results Summary -->
                    <div class="mb-4 text-sm text-gray-600">
                        Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} of {{ $users->total() }} users
                        @if(request('search') || request('role'))
                            <span class="text-indigo-600">(filtered)</span>
                        @endif
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Name
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Email
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Role
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
                                @forelse ($users as $user)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                                        <span class="text-sm font-medium text-gray-700">
                                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $user->name }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $user->email }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($user->is_admin)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    Admin
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    User
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $user->created_at->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('admin.users.edit', $user) }}" 
                                                   class="text-indigo-600 hover:text-indigo-900 bg-indigo-100 hover:bg-indigo-200 px-3 py-1 rounded-md text-sm">
                                                    Edit
                                                </a>
                                                @if($user->id !== auth()->id())
                                                    <form action="{{ route('admin.users.delete', $user) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="text-red-600 hover:text-red-900 bg-red-100 hover:bg-red-200 px-3 py-1 rounded-md text-sm"
                                                                onclick="return confirm('Are you sure you want to delete this user?')">
                                                            Delete
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="text-gray-400 text-sm">Current User</span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                            @if(request('search') || request('role'))
                                                No users found matching your criteria.
                                            @else
                                                No users found.
                                            @endif
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 