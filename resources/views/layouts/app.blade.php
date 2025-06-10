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
    </head>
    <body class="font-sans antialiased bg-slate-50">
        <x-sidebar :user="auth()->user()">
            <!-- Page Header -->
            @if (isset($header))
                <div class="bg-white border-b border-slate-200">
                    <div class="px-6 py-4">
                        {{ $header }}
                    </div>
                </div>
            @endif

            <!-- Page Content -->
            <div class="p-6">
                {{ $slot }}
            </div>
        </x-sidebar>
    </body>
</html>