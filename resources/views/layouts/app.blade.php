<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'The Blade Room')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Playfair+Display:wght@600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        blade: {
                            deep: '#0e0e0e',
                            card: '#161616',
                            surface: '#1f1f1f',
                            gold: '#c9a84c',
                            mutedGold: '#8a6f32',
                            text: '#f0ece4',
                            muted: '#6b6b6b',
                            border: '#2a2a2a',
                            danger: '#b94a4a',
                        },
                    },
                    fontFamily: {
                        serif: ['Playfair Display', 'serif'],
                        sans: ['Inter', 'sans-serif'],
                    },
                },
            },
        };
    </script>
    @livewireStyles
    @stack('head')
</head>
<body class="min-h-screen bg-[#0e0e0e] font-sans text-[#f0ece4] antialiased">
    <header class="fixed inset-x-0 top-0 z-40 border-b border-[#2a2a2a]/80 bg-[#0e0e0e]/90 backdrop-blur">
        <nav class="mx-auto flex h-20 max-w-7xl items-center justify-between px-5 sm:px-8">
            <a href="{{ route('home') }}" class="font-serif text-2xl font-bold tracking-wide text-[#f0ece4]">
                The Blade Room
            </a>
            <div class="flex items-center gap-5 text-sm font-semibold uppercase tracking-wide">
                <a href="{{ route('home') }}" class="text-[#6b6b6b] transition duration-200 hover:text-[#c9a84c]">Home</a>
                <a href="{{ route('booking') }}" class="border border-[#c9a84c] px-4 py-3 text-[#c9a84c] transition duration-200 hover:bg-[#c9a84c] hover:text-[#0e0e0e]">Book</a>
            </div>
        </nav>
    </header>

    <main class="pt-20">
        @yield('content')
    </main>

    @livewireScripts
    @stack('scripts')
</body>
</html>
