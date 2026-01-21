<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Family Todo') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Font Awesome Icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-DTOQO9VMCCB+xD5M+a/r6ap4OAwbiLvVWDBEYd8+0s+4gFp6N957nSip+EwSz2PrtLSf5p7rIIUFB+KidJ5v2w==" crossorigin="anonymous" referrerpolicy="no-referrer" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://unpkg.com/alpinejs" defer></script>

        <style>
            :root {
                --red-primary: #E4405F;
                --red-dark: #C32AA3;
                --red-light: #F77737;
            }
            
            body {
                background: linear-gradient(135deg, #f5f5f5 0%, #fafafa 100%);
            }
            
            .top-bar {
                background: linear-gradient(90deg, #E4405F 0%, #F77737 100%);
                box-shadow: 0 2px 8px rgba(228, 64, 95, 0.15);
            }
            
            .logo-text {
                font-weight: 700;
                font-size: 24px;
                background: linear-gradient(90deg, #fff 0%, #fff 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }
            
            .nav-link {
                transition: all 0.2s ease;
                color: rgba(255, 255, 255, 0.8);
                font-weight: 500;
            }
            
            .nav-link:hover {
                color: white;
            }
            
            .nav-link.active {
                color: white;
                border-bottom: 3px solid white;
            }
            
            .main-container {
                display: grid;
                grid-template-columns: 280px 1fr 320px;
                gap: 16px;
                margin: 16px auto;
                max-width: 1200px;
                padding: 0 16px;
            }
            
            @media (max-width: 1024px) {
                .main-container {
                    grid-template-columns: 1fr;
                    max-width: 600px;
                }
                .sidebar-left, .sidebar-right {
                    display: none;
                }
            }
            
            .sidebar {
                position: sticky;
                top: 64px;
                height: fit-content;
            }
            
            .card {
                background: white;
                border-radius: 12px;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
                overflow: hidden;
            }
            
            .card-hover {
                transition: box-shadow 0.2s ease;
            }
            
            .card-hover:hover {
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
            }
            
            .btn-primary {
                background: linear-gradient(90deg, #E4405F 0%, #F77737 100%);
                color: white;
                font-weight: 600;
                padding: 10px 24px;
                border-radius: 8px;
                border: none;
                cursor: pointer;
                transition: all 0.3s ease;
            }
            
            .btn-primary:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 16px rgba(228, 64, 95, 0.3);
            }
            
            .btn-primary:active {
                transform: translateY(0);
            }
            
            .btn-secondary {
                background: #f0f2f5;
                color: #65676b;
                font-weight: 600;
                padding: 10px 24px;
                border-radius: 8px;
                border: none;
                cursor: pointer;
                transition: all 0.2s ease;
            }
            
            .btn-secondary:hover {
                background: #e4e6eb;
            }
            
            .badge-red {
                display: inline-block;
                background: linear-gradient(90deg, #E4405F 0%, #F77737 100%);
                color: white;
                padding: 4px 12px;
                border-radius: 20px;
                font-size: 12px;
                font-weight: 600;
            }
            
            .icon-button {
                width: 40px;
                height: 40px;
                border-radius: 50%;
                background: #f0f2f5;
                border: none;
                cursor: pointer;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: all 0.2s ease;
                color: #65676b;
            }
            
            .icon-button:hover {
                background: #e4e6eb;
            }
            
            .user-avatar {
                width: 40px;
                height: 40px;
                border-radius: 50%;
                object-fit: cover;
                border: 2px solid #E4405F;
            }
            
            .user-card {
                background: white;
                border-radius: 12px;
                padding: 12px;
                margin-bottom: 8px;
                transition: all 0.2s ease;
            }
            
            .user-card:hover {
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
            }
        </style>
    </head>
    <body class="font-sans antialiased bg-gray-50">
        <!-- Top Navigation Bar -->
        <nav class="top-bar sticky top-0 z-50">
            <div class="max-w-[1400px] mx-auto px-4 h-16 flex items-center justify-between">
                <!-- Logo -->
                <a href="{{ auth()->user()->is_admin ? route('admin.dashboard') : route('dashboard') }}" class="flex items-center space-x-2">
                    <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center">
                        <i class="fas fa-home text-red-500 text-xl"></i>
                    </div>
                    <span class="logo-text hidden sm:inline">Family Todo</span>
                </a>

                <!-- Center Navigation -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="fas fa-home"></i>
                    </a>
                    <a href="{{ route('feed') }}" class="nav-link {{ request()->routeIs('feed') ? 'active' : '' }}">
                        <i class="fas fa-stream"></i>
                    </a>
                    <a href="{{ route('tasks.index') }}" class="nav-link {{ request()->routeIs('tasks.index') ? 'active' : '' }}">
                        <i class="fas fa-tasks"></i>
                    </a>
                </div>

                <!-- Right Navigation -->
                <div class="flex items-center space-x-3">
                    <button class="icon-button hover:bg-red-100">
                        <i class="fas fa-search"></i>
                    </button>
                    
                    <div class="h-6 w-px bg-white/20"></div>

                    <!-- User Dropdown -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="flex items-center space-x-2 hover:opacity-80 transition">
                            <img class="user-avatar" src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}&background=E4405F&color=fff" alt="{{ auth()->user()->name }}">
                            <span class="text-white font-medium hidden sm:inline">{{ auth()->user()->name }}</span>
                        </button>

                        <div x-show="open" @click.outside="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg z-50">
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 hover:bg-red-50 text-gray-800 border-b">Profile</a>
                            <form method="POST" action="{{ route('logout') }}" class="block">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 hover:bg-red-50 text-gray-800">Logout</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        @if(request()->routeIs('admin.*'))
            <!-- Admin Layout -->
            <main>
                {{ $slot }}
            </main>
        @else
            <!-- User Layout with Sidebars -->
            <div class="main-container">
                <!-- Left Sidebar -->
                <aside class="sidebar sidebar-left hidden lg:block">
                    <div class="card">
                        <div class="p-4 border-b">
                            <img class="user-avatar mx-auto mb-3" src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}&background=E4405F&color=fff" alt="{{ auth()->user()->name }}">
                            <h3 class="font-bold text-center text-gray-900">{{ auth()->user()->name }}</h3>
                            <p class="text-center text-sm text-gray-600 mt-1">Member since {{ auth()->user()->created_at->format('M Y') }}</p>
                        </div>
                        <nav class="p-2">
                            <a href="{{ route('dashboard') }}" class="user-card flex items-center space-x-3 mb-2 {{ request()->routeIs('dashboard') ? 'bg-red-50 border-l-4 border-red-500' : '' }}">
                                <i class="fas fa-home text-red-500"></i>
                                <span class="text-sm font-medium text-gray-900">Home</span>
                            </a>
                            <a href="{{ route('feed') }}" class="user-card flex items-center space-x-3 {{ request()->routeIs('feed') ? 'bg-red-50 border-l-4 border-red-500' : '' }}">
                                <i class="fas fa-stream text-red-500"></i>
                                <span class="text-sm font-medium text-gray-900">Feed</span>
                            </a>
                            <a href="{{ route('tasks.index') }}" class="user-card flex items-center space-x-3 {{ request()->routeIs('tasks.index') ? 'bg-red-50 border-l-4 border-red-500' : '' }}">
                                <i class="fas fa-tasks text-red-500"></i>
                                <span class="text-sm font-medium text-gray-900">Tasks</span>
                            </a>
                        </nav>
                    </div>
                </aside>

                <!-- Main Feed -->
                <main class="space-y-6">
                    <!-- Page Heading -->
                    @isset($header)
                        <div class="card p-4 hidden">
                            {{ $header }}
                        </div>
                    @endisset

                    <!-- Main Content -->
                    {{ $slot }}
                </main>

                <!-- Right Sidebar -->
                <aside class="sidebar sidebar-right hidden lg:block">
                    <div class="card p-4">
                        <h3 class="font-bold text-gray-900 mb-3">Suggested Friends</h3>
                        <div class="space-y-3">
                            @forelse($users ?? [] as $user)
                                <div class="user-card p-3 border border-gray-200 rounded-lg hover:shadow-md transition">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-2 flex-1">
                                            <img class="w-8 h-8 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random" alt="{{ $user->name }}">
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900 truncate">{{ $user->name }}</p>
                                                <p class="text-xs text-gray-500 truncate">{{ $user->email }}</p>
                                            </div>
                                        </div>
                                        <button onclick="sendFriendRequest({{ $user->id }})" class="ml-2 px-2 py-1 bg-blue-500 hover:bg-blue-600 text-white text-xs rounded transition">
                                            Add
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-gray-600 text-center py-4">No suggestions at the moment</p>
                            @endforelse
                        </div>
                    </div>
                </aside>
            </div>
        @endif

        <script>
            $(document).ready(function () {
                $('.btn-react').on('click', function () {
                    const postId = $(this).data('post-id');
                    const type = $(this).data('type');

                    $.ajax({
                        url: '{{ route("reactions.react") }}',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            post_id: postId,
                            type: type
                        },
                        success: function () {
                            $('#reaction-count-' + type + '-' + postId).load(location.href + ' #reaction-count-' + type + '-' + postId);
                        },
                        error: function (xhr) {
                            alert("Error reacting: " + xhr.responseText);
                        }
                    });
                });
            });

            // Friend request function
            function sendFriendRequest(friendId) {
                $.ajax({
                    url: '{{ url('/friends/request') }}/' + friendId,
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        alert(response.message);
                        location.reload();
                    },
                    error: function (xhr) {
                        const errorMessage = xhr.responseJSON?.message || 'Error sending friend request';
                        alert(errorMessage);
                    }
                });
            }
        </script>
    </body>
</html>
