<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @livewireStyles
    <style>[x-cloak]{display:none !important}</style>
</head>
<body class="bg-gray-50">
    <div class="flex">
        <!-- Sidebar Component -->
        @livewire('sidebar')
        
        <!-- Main Content -->
        <main class="flex-1 ml-10 p-6 transition-all duration-300 flex justify-center mb-10">
            {{ $slot }}
        </main>
    </div>
    
    @livewireScripts
</body>
</html>