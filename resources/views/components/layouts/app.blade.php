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
        @if(Route::currentRouteName() !== 'login')
            <livewire:sidebar />
        @endif

        <main class="pt-10">

            <div class="px-4 sm:px-6 lg:px-8 py-8 max-w-none ml-20">
                {{ $slot }}
            </div>
        </main>
    </div>

    @livewireScripts

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const originalFetch = window.fetch;
            window.fetch = function(...args) {
            return originalFetch.apply(this, args).then(response => {
                if (response.status === 500) {
                return response.clone().text().then(text => {
                    if (text.includes('toJSON') && text.includes('not found')) {
                    console.warn('Livewire: Método toJSON não encontrado (ignorado)');
                    return new Response(JSON.stringify({ success: true }), {
                        status: 200,
                        statusText: 'OK',
                        headers: response.headers
                    });
                    }
                    return response;
                });
                }
                return response;
            });
            };
        });
    </script>


</body>

</html>
