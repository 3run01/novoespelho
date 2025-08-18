<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
    <style>
        [x-cloak] {
            display: none !important
        }
    </style>
</head>

<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Sidebar Component -->
        @livewire('sidebar')

        <!-- Main Content -->
        <main class="pt-20 pl-0 lg:pl-64 transition-all duration-300">
            <div class="content-optimized px-4 sm:px-6 py-6">
                {{ $slot }}
            </div>
        </main>
    </div>

    @livewireScripts
</body>

</html>
