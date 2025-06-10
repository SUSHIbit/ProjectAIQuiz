<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-slate-50">
        <div class="min-h-screen flex flex-col items-center justify-center px-4 py-8">
            <!-- Logo/Brand Section -->
            <div class="mb-8">
                <a href="{{ route('welcome') }}" class="flex items-center space-x-3 group">
                    <div class="w-12 h-12 bg-gradient-to-br from-slate-600 to-slate-800 rounded-xl flex items-center justify-center shadow-lg group-hover:shadow-xl transition-all duration-200">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-slate-900 group-hover:text-slate-700 transition-colors">Quiz Generator</h1>
                        <p class="text-sm text-slate-500">AI-Powered Learning</p>
                    </div>
                </a>
            </div>

            <!-- Main Content Card -->
            <div class="w-full max-w-md">
                <div class="bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden">
                    <div class="px-8 py-8">
                        {{ $slot }}
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="mt-8 text-center">
                <p class="text-sm text-slate-500">
                    &copy; {{ date('Y') }} Quiz Generator. All rights reserved.
                </p>
            </div>
        </div>
    </body>
</html>