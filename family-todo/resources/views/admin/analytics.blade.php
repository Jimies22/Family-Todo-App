<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Analytics & Reports') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Users Stats -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Total Users</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ $totalUsers }}</dd>
                                </dl>
                            </div>
                        </div>
                        <div class="mt-4 grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-gray-500">Admins:</span>
                                <span class="font-medium text-gray-900">{{ $adminUsers }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500">Regular:</span>
                                <span class="font-medium text-gray-900">{{ $regularUsers }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tasks Stats -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Total Tasks</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ $totalTasks }}</dd>
                                </dl>
                            </div>
                        </div>
                        <div class="mt-4 grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-gray-500">Completed:</span>
                                <span class="font-medium text-green-600">{{ $completedTasks }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500">Pending:</span>
                                <span class="font-medium text-yellow-600">{{ $pendingTasks }}</span>
                            </div>
                        </div>
                        <div class="mt-2">
                            <div class="flex items-center">
                                <div class="flex-1 bg-gray-200 rounded-full h-2">
                                    <div class="bg-green-500 h-2 rounded-full" style="width: {{ $taskCompletionRate }}%"></div>
                                </div>
                                <span class="ml-2 text-sm text-gray-600">{{ $taskCompletionRate }}%</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Posts Stats -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Total Posts</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ $totalPosts }}</dd>
                                </dl>
                            </div>
                        </div>
                        <div class="mt-4 grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-gray-500">Announcements:</span>
                                <span class="font-medium text-blue-600">{{ $announcements }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500">Important:</span>
                                <span class="font-medium text-red-600">{{ $importantPosts }}</span>
                            </div>
                        </div>
                        <div class="mt-2 text-sm">
                            <span class="text-gray-500">Pinned:</span>
                            <span class="font-medium text-yellow-600">{{ $pinnedPosts }}</span>
                        </div>
                    </div>
                </div>

                <!-- System Health -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">System Health</dt>
                                    <dd class="text-lg font-medium text-green-600">Good</dd>
                                </dl>
                            </div>
                        </div>
                        <div class="mt-4 text-sm text-gray-600">
                            All systems operational
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Activity</h3>
                    <div class="space-y-4">
                        @forelse($recentActivity as $activity)
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0">
                                    @if($activity['type'] === 'user')
                                        <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                                            <svg class="h-4 w-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                        </div>
                                    @elseif($activity['type'] === 'task')
                                        <div class="h-8 w-8 rounded-full bg-green-100 flex items-center justify-center">
                                            <svg class="h-4 w-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                            </svg>
                                        </div>
                                    @elseif($activity['type'] === 'post')
                                        <div class="h-8 w-8 rounded-full bg-purple-100 flex items-center justify-center">
                                            <svg class="h-4 w-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900">
                                        @if($activity['type'] === 'user')
                                            {{ $activity['item']->name }} {{ $activity['action'] }}
                                        @elseif($activity['type'] === 'task')
                                            Task "{{ $activity['item']->title }}" was {{ $activity['action'] }}
                                        @elseif($activity['type'] === 'post')
                                            Post "{{ $activity['item']->title ?: 'Untitled' }}" was {{ $activity['action'] }}
                                        @endif
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        {{ $activity['date']->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4 text-gray-500">
                                No recent activity
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 