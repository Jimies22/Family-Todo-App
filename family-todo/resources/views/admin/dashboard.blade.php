<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-4 max-w-4xl mx-auto">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white p-4 rounded-xl shadow">
                <h3>Total Tasks</h3>
                <p>{{ $tasksCount }}</p>
            </div>
            <div class="bg-white p-4 rounded-xl shadow">
                <h3>Total Users</h3>
                <p>{{ $usersCount }}</p>
            </div>
            <div class="bg-white p-4 rounded-xl shadow">
                <h3>Total Posts</h3>
                <p>{{ $postsCount }}</p>
            </div>
        </div>
    </div>
</x-app-layout>
