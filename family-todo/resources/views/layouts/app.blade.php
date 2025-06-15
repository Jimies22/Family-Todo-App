<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
                // Refresh just the reaction count
                $('#reaction-count-' + type + '-' + postId).load(location.href + ' #reaction-count-' + type + '-' + postId);
            },
            error: function (xhr) {
                alert("Error reacting: " + xhr.responseText);
            }
        });
    });
});
</script>

    </body>
</html>
