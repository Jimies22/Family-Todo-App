<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Create New Post') }}
            </h2>
            <a href="{{ route('admin.posts') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Back to Posts
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('admin.posts.store') }}" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Title -->
                            <div class="md:col-span-2">
                                <x-input-label for="title" :value="__('Title')" />
                                <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title')" required autofocus />
                                <x-input-error :messages="$errors->get('title')" class="mt-2" />
                            </div>

                            <!-- Type -->
                            <div>
                                <x-input-label for="type" :value="__('Post Type')" />
                                <select id="type" name="type" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="general" {{ old('type') === 'general' ? 'selected' : '' }}>General Post</option>
                                    <option value="announcement" {{ old('type') === 'announcement' ? 'selected' : '' }}>Announcement</option>
                                    <option value="important" {{ old('type') === 'important' ? 'selected' : '' }}>Important Notice</option>
                                    <option value="task_summary" {{ old('type') === 'task_summary' ? 'selected' : '' }}>Task Summary</option>
                                </select>
                                <x-input-error :messages="$errors->get('type')" class="mt-2" />
                            </div>

                            <!-- Status -->
                            <div>
                                <x-input-label for="status" :value="__('Status')" />
                                <select id="status" name="status" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Published</option>
                                </select>
                                <x-input-error :messages="$errors->get('status')" class="mt-2" />
                            </div>

                            <!-- Expires At -->
                            <div>
                                <x-input-label for="expires_at" :value="__('Expires At (Optional)')" />
                                <x-text-input id="expires_at" class="block mt-1 w-full" type="datetime-local" name="expires_at" :value="old('expires_at')" />
                                <p class="mt-1 text-sm text-gray-500">Leave empty for no expiration</p>
                                <x-input-error :messages="$errors->get('expires_at')" class="mt-2" />
                            </div>

                            <!-- Options -->
                            <div class="space-y-3">
                                <div class="flex items-center">
                                    <input id="is_pinned" type="checkbox" name="is_pinned" value="1" {{ old('is_pinned') ? 'checked' : '' }} 
                                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                    <label for="is_pinned" class="ml-2 block text-sm text-gray-900">
                                        {{ __('Pin to Top') }}
                                    </label>
                                </div>

                                <div class="flex items-center">
                                    <input id="is_featured" type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} 
                                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                    <label for="is_featured" class="ml-2 block text-sm text-gray-900">
                                        {{ __('Feature Post') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Content -->
                        <div>
                            <x-input-label for="content" :value="__('Content')" />
                            <textarea id="content" name="content" rows="10" 
                                      class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                      placeholder="Write your post content here...">{{ old('content') }}</textarea>
                            <x-input-error :messages="$errors->get('content')" class="mt-2" />
                        </div>

                        <!-- Post Type Help -->
                        <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                            <h4 class="text-sm font-medium text-blue-800 mb-2">Post Type Guide:</h4>
                            <ul class="text-sm text-blue-700 space-y-1">
                                <li><strong>General Post:</strong> Regular updates, news, or general information</li>
                                <li><strong>Announcement:</strong> Important announcements for all users</li>
                                <li><strong>Important Notice:</strong> Critical information that requires immediate attention</li>
                                <li><strong>Task Summary:</strong> Summary of completed tasks or progress reports</li>
                            </ul>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <x-primary-button class="ml-4">
                                {{ __('Create Post') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 