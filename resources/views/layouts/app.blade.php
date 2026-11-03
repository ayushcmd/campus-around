<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full antialiased scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'CampusAround — Discover your campus and what\'s around' }}</title>
    <meta name="description" content="Discover campus facilities, food spots, cafes, shops, and services around IIT Patna. Built by students, for students.">

    <!-- Fonts: Inter & Manrope -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Leaflet CSS Fallback & Integrity -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="min-h-full flex flex-col bg-[#F7F7F2] text-[#082D00] selection:bg-[#9BE96B] selection:text-[#082D00] font-sans antialiased overflow-x-hidden">
    <!-- Accessible skip link -->
    <a href="#main-content" class="sr-only focus:not-sr-only focus:fixed focus:top-4 focus:left-4 z-50 px-4 py-2 bg-[#082D00] text-white rounded-lg font-semibold shadow-lg">
        Skip to main content
    </a>

    {{-- Navbar component --}}
    <x-navbar :theme="$navTheme ?? 'light'" />

    <!-- Main Page Content -->
    <main id="main-content" class="flex-grow">
        {{ $slot }}
    </main>

    {{-- Footer component --}}
    <x-footer />

    @stack('scripts')
</body>
</html>
