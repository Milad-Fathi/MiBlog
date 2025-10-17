<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>MIBlog</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <link rel="stylesheet" href="/style.css">
    <!-- <link rel="stylesheet" href="{{ asset('styles.css') }}"> -->

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @if (isset($header))
        <header class="bg-white dark:bg-gray-800 shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
        @endif

        <div class="search">
            <form action="{{ route('search') }}" method="GET">
                <input type="text" name="q" placeholder="Search by title">
                <button class="search_btn" type="submit">Search</button>
            </form>
        </div>

        <!-- Page Content -->
        <main>
            @if (isset($slot))
            {{ $slot }}
            @endif
        </main>

        <main>
            @yield('content')
        </main>
    </div>
</body>

</html>