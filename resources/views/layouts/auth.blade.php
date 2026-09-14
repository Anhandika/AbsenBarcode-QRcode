<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Masuk' }} · SMK BINA UTAMA KENDAL</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#eef2f7] relative overflow-hidden">
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(ellipse_at_top,_rgba(44,104,245,.12),transparent_50%),radial-gradient(ellipse_at_bottom_right,_rgba(255,213,0,.14),transparent_50%)]"></div>
    <a href="#main-content" class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-50 focus:rounded-school-control focus:bg-school-navy focus:px-4 focus:py-3 focus:text-sm focus:font-semibold focus:text-white">
        Lewati ke formulir masuk
    </a>
    <main id="main-content" class="relative grid min-h-screen place-items-center px-4 py-8">
        @yield('content')
    </main>
</body>
</html>
