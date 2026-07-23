<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SahaDenetim Dashboard') }}</title>

        <!-- Tailwind CSS CDN -->
        <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>

        <!-- Tailwind Config -->
        <script src="{{ asset('js/tailwind-config.js') }}"></script>
        
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com" rel="preconnect">
        <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('styles')
    </head>
    <body class="bg-background text-text min-h-screen w-full font-sans overflow-x-hidden">
        <!-- Navbar Container -->
        <x-navbar />

        <!-- İçerik Alanı -->
        <main class="w-full max-w-7xl mx-auto px-6 pt-32 pb-12">
            {{ $slot }}
        </main>

        @stack('scripts')
    </body>
</html>
