<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Family Todo App</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .gradient-primary {
            background: linear-gradient(135deg, #E4405F 0%, #C32AA3 100%);
        }
        .gradient-success {
            background: linear-gradient(135deg, #F77737 0%, #E4405F 100%);
        }
        .gradient-warning {
            background: linear-gradient(135deg, #E63946 0%, #A4161A 100%);
        }
        .card-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .card-hover:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }
        .sidebar {
            transition: all 0.3s ease;
        }
        .nav-item {
            transition: all 0.2s ease;
        }
        .nav-item:hover {
            background-color: rgba(255, 255, 255, 0.1);
            padding-left: 1.5rem;
        }
        .nav-item.active {
            background-color: rgba(255, 255, 255, 0.2);
            border-left: 4px solid #fff;
        }
        .stat-card {
            position: relative;
            overflow: hidden;
        }
        .stat-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="flex h-screen bg-gray-100">
        <!-- Sidebar -->
        <div class="sidebar w-64 bg-gradient-to-b from-red-600 to-red-800 text-white p-0 hidden lg:block fixed h-full left-0 top-0 z-40">
            <!-- Logo -->
            <div class="p-6 border-b border-red-500">
                <h1 class="text-2xl font-bold flex items-center">
                    <svg class="w-8 h-8 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                    </svg>
                    Admin
                </h1>
                <p class="text-red-300 text-xs mt-1">Family Todo Dashboard</p>
            </div>

            <!-- Navigation -->
            <nav class="mt-6 flex-1">
                <a href="{{ route('admin.dashboard') }}" class="nav-item active block px-6 py-3 text-white border-l-4 border-white">
                    <svg class="w-5 h-5 inline-block mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-3m0 0l7-4 7 4M5 9v10a1 1 0 001 1h12a1 1 0 001-1V9m-9 16l-7-4m0 0V5m7 4l7-4"></path>
                    </svg>
                    Dashboard
                </a>
                <a href="{{ route('admin.users') }}" class="nav-item block px-6 py-3 text-red-200 hover:text-white">
                    <svg class="w-5 h-5 inline-block mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 6a3 3 0 11-6 0 3 3 0 016 0zm6 0a2 2 0 11-4 0 2 2 0 014 0zM5 20a3 3 0 015.856-1.487M15 20h5v-2a3 3 0 00-9-3"></path>
                    </svg>
                    Manage Users
                </a>
                <a href="{{ route('admin.tasks') }}" class="nav-item block px-6 py-3 text-red-200 hover:text-white">
                    <svg class="w-5 h-5 inline-block mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                    View Tasks
                </a>
                <a href="{{ route('admin.posts') }}" class="nav-item block px-6 py-3 text-red-200 hover:text-white">
                    <svg class="w-5 h-5 inline-block mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                    </svg>
                    View Posts
                </a>
            </nav>

            <!-- Footer -->
            <div class="p-6 border-t border-red-500 text-red-300 text-xs">
                <p>Family Todo App v1.0</p>
                <p class="mt-2">© 2025 All rights reserved</p>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 lg:ml-64 flex flex-col overflow-hidden">
            <!-- Top Navigation Bar -->
            <div class="bg-white border-b border-gray-200 p-4 lg:p-6 flex items-center justify-between">
                <div class="flex items-center">
                    <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <button class="p-2 hover:bg-gray-100 rounded-lg transition">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                    </button>
                    <div class="flex items-center space-x-2 pl-4 border-l border-gray-200">
                        <img class="w-10 h-10 rounded-full" src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}&color=E4405F&background=ffe0e6" alt="{{ auth()->user()->name }}">
                        <span class="text-sm font-medium text-gray-700">{{ auth()->user()->name }}</span>
                    </div>
                </div>
            </div>

            <!-- Scrollable Content -->
            <div class="flex-1 overflow-auto">
                <div class="p-4 lg:p-8">
                    <!-- Header Section -->
                    <div class="mb-8">
                        <h2 class="text-3xl font-bold text-gray-900 mb-2">Welcome back, {{ auth()->user()->name }}! 👋</h2>
                        <p class="text-gray-600">Here's what's happening in your Family Todo App today</p>
                    </div>

                    <!-- Stats Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                        <!-- Tasks Card -->
                        <div class="card-hover stat-card bg-white rounded-xl p-6 shadow-sm">
                            <div class="flex items-start justify-between">
                                <div>
                                    <p class="text-gray-600 text-sm font-medium">Total Tasks</p>
                                    <p class="text-4xl font-bold text-gray-900 mt-2">{{ $tasksCount }}</p>
                                    <p class="text-green-600 text-sm font-medium mt-2">📈 +12% this month</p>
                                </div>
                                <div class="gradient-primary w-12 h-12 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Users Card -->
                        <div class="card-hover stat-card bg-white rounded-xl p-6 shadow-sm">
                            <div class="flex items-start justify-between">
                                <div>
                                    <p class="text-gray-600 text-sm font-medium">Total Users</p>
                                    <p class="text-4xl font-bold text-gray-900 mt-2">{{ $usersCount }}</p>
                                    <p class="text-green-600 text-sm font-medium mt-2">📈 +5% new users</p>
                                </div>
                                <div class="gradient-success w-12 h-12 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 6a3 3 0 11-6 0 3 3 0 016 0zm6 0a2 2 0 11-4 0 2 2 0 014 0zM5 20a3 3 0 015.856-1.487M15 20h5v-2a3 3 0 00-9-3"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Posts Card -->
                        <div class="card-hover stat-card bg-white rounded-xl p-6 shadow-sm">
                            <div class="flex items-start justify-between">
                                <div>
                                    <p class="text-gray-600 text-sm font-medium">Total Posts</p>
                                    <p class="text-4xl font-bold text-gray-900 mt-2">{{ $postsCount }}</p>
                                    <p class="text-green-600 text-sm font-medium mt-2">📈 +8% engagement</p>
                                </div>
                                <div class="gradient-warning w-12 h-12 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions Section -->
                    <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
                        <h3 class="text-xl font-bold text-gray-900 mb-6">Quick Actions</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <a href="{{ route('admin.users') }}" class="group relative overflow-hidden bg-gradient-to-r from-red-500 to-red-600 text-white font-semibold py-4 px-6 rounded-lg transition duration-300 hover:shadow-lg hover:-translate-y-1">
                                <span class="relative z-10 flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                    </svg>
                                    Manage Users
                                </span>
                                <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-20 transition duration-300"></div>
                            </a>
                            <a href="{{ route('admin.tasks') }}" class="group relative overflow-hidden bg-gradient-to-r from-orange-500 to-orange-600 text-white font-semibold py-4 px-6 rounded-lg transition duration-300 hover:shadow-lg hover:-translate-y-1">
                                <span class="relative z-10 flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                    View Tasks
                                </span>
                                <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-20 transition duration-300"></div>
                            </a>
                            <a href="{{ route('admin.posts') }}" class="group relative overflow-hidden bg-gradient-to-r from-rose-500 to-rose-600 text-white font-semibold py-4 px-6 rounded-lg transition duration-300 hover:shadow-lg hover:-translate-y-1">
                                <span class="relative z-10 flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                                    </svg>
                                    View Posts
                                </span>
                                <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-20 transition duration-300"></div>
                            </a>
                        </div>
                    </div>

                    <!-- Footer Stats -->
                    <div class="bg-gradient-to-r from-red-600 to-red-800 rounded-xl text-white p-8">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                            <div>
                                <p class="text-red-200 text-sm font-medium">Active Sessions</p>
                                <p class="text-3xl font-bold mt-2">{{ $usersCount }}</p>
                            </div>
                            <div>
                                <p class="text-red-200 text-sm font-medium">System Status</p>
                                <p class="text-3xl font-bold mt-2">✓ Operational</p>
                            </div>
                            <div>
                                <p class="text-red-200 text-sm font-medium">Last Update</p>
                                <p class="text-3xl font-bold mt-2">{{ now()->format('H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
