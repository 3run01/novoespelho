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
    <div class="min-h-screen" x-data="{ sidebarCollapsed: false }"
        @sidebar-collapsed.window="sidebarCollapsed = $event.detail.collapsed">
        @livewire('sidebar')

        <main class="pt-10 transition-all duration-300" :class="sidebarCollapsed ? 'pl-[10px]' : 'pl-20'">
            <div class="px-4 sm:px-6 lg:px-8 py-8 max-w-none">
                {{ $slot }}
            </div>
        </main>
    </div>

    @livewireScripts
</body>

</html>
