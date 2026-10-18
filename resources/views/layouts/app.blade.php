<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="{{ auth()->check() && auth()->user()->theme === 'dark' ? 'dark' : '' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'TaskFlow Pro'))</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 transition-colors duration-300">
    <div class="min-h-screen">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white dark:bg-gray-800 shadow transition-colors duration-300">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main>
            @yield('content')
        </main>
    </div>

    <!-- Theme Toggle Script -->
    <script>
        if (localStorage.getItem('theme') === 'dark' || ({{ auth()->check() && auth()->user()->theme === 'dark' ? 'true' : 'false' }})) {
            document.documentElement.classList.add('dark');
            document.body.style.backgroundColor = '#111827';
            document.body.style.color = '#f3f4f6';
        } else {
            document.documentElement.classList.remove('dark');
            document.body.style.backgroundColor = '#f3f4f6';
            document.body.style.color = '#111827';
        }

        function toggleTheme() {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('theme', 'light');
                document.body.style.backgroundColor = '#f3f4f6';
                document.body.style.color = '#111827';
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
                document.body.style.backgroundColor = '#111827';
                document.body.style.color = '#f3f4f6';
            }
            const form = document.getElementById('themeForm');
            if(form) form.submit();
        }
    </script>
</body>
</html>