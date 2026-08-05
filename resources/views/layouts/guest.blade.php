<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SIDBM Export') }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-white to-primary-50 relative overflow-hidden">
            <!-- Decorative Background Elements -->
            <div class="absolute inset-0 overflow-hidden pointer-events-none">
                <!-- Top Right Circle -->
                <div class="absolute -top-20 -right-20 w-80 h-80 rounded-full bg-primary-100/30"></div>
                <div class="absolute -top-10 -right-10 w-60 h-60 rounded-full bg-primary-200/20"></div>

                <!-- Bottom Left Circle -->
                <div class="absolute -bottom-24 -left-24 w-96 h-96 rounded-full bg-secondary-100/30"></div>
                <div class="absolute -bottom-12 -left-12 w-64 h-64 rounded-full bg-secondary-200/20"></div>

                <!-- Top Left Dots -->
                <svg class="absolute top-20 left-20 w-32 h-32 text-primary-200/40" viewBox="0 0 100 100">
                    <pattern id="dots" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse">
                        <circle cx="2" cy="2" r="1.5" fill="currentColor"/>
                    </pattern>
                    <rect x="0" y="0" width="100" height="100" fill="url(#dots)"/>
                </svg>

                <!-- Bottom Right Dots -->
                <svg class="absolute bottom-20 right-20 w-32 h-32 text-secondary-300/40" viewBox="0 0 100 100">
                    <pattern id="dots2" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse">
                        <circle cx="2" cy="2" r="1.5" fill="currentColor"/>
                    </pattern>
                    <rect x="0" y="0" width="100" height="100" fill="url(#dots2)"/>
                </svg>

                <!-- Curved Lines -->
                <svg class="absolute top-32 left-10 w-40 h-40 text-primary-100/50" viewBox="0 0 100 100">
                    <path d="M10 50 Q 50 10, 90 50 Q 50 90, 10 50" fill="none" stroke="currentColor" stroke-width="1"/>
                </svg>

                <svg class="absolute bottom-32 right-10 w-40 h-40 text-secondary-200/50" viewBox="0 0 100 100">
                    <path d="M10 50 Q 50 90, 90 50 Q 50 10, 10 50" fill="none" stroke="currentColor" stroke-width="1"/>
                </svg>

                <!-- Small decorative circles -->
                <div class="absolute top-1/4 left-1/4 w-3 h-3 rounded-full bg-primary-300/30"></div>
                <div class="absolute top-1/3 right-1/3 w-2 h-2 rounded-full bg-secondary-400/40"></div>
                <div class="absolute bottom-1/3 left-1/3 w-2 h-2 rounded-full bg-primary-200/50"></div>
                <div class="absolute bottom-1/4 right-1/4 w-4 h-4 rounded-full bg-secondary-300/20"></div>
            </div>

            <!-- Login Card -->
            <div class="w-full max-w-md mx-4 relative z-10">
                <div class="bg-card rounded-card shadow-card hover:shadow-card-hover transition-all duration-300 px-8 py-10">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
