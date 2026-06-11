<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') - The Blade Room</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Playfair+Display:wght@600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        serif: ['Playfair Display', 'serif'],
                        sans: ['Inter', 'sans-serif'],
                    },
                },
            },
        };
    </script>
</head>
<body class="min-h-screen bg-[#0e0e0e] font-sans text-[#f0ece4] antialiased">
    <div class="min-h-screen lg:flex">
        <aside class="border-b border-[#2a2a2a] bg-[#161616] lg:fixed lg:inset-y-0 lg:left-0 lg:flex lg:w-72 lg:flex-col lg:border-b-0 lg:border-r">
            <div class="flex h-20 items-center justify-between px-6">
                <a href="{{ route('admin.dashboard') }}" class="font-serif text-2xl font-bold text-[#c9a84c]">The Blade Room</a>
            </div>

            <nav class="flex gap-2 overflow-x-auto px-4 pb-4 lg:flex-1 lg:flex-col lg:overflow-visible">
                @php
                    $links = [
                        ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'pattern' => 'admin.dashboard'],
                        ['label' => 'Bookings', 'route' => 'admin.bookings.index', 'pattern' => 'admin.bookings.*'],
                        ['label' => 'Services', 'route' => 'admin.services.index', 'pattern' => 'admin.services.*'],
                        ['label' => 'Barbers', 'route' => 'admin.barbers.index', 'pattern' => 'admin.barbers.*'],
                    ];
                @endphp

                @foreach ($links as $link)
                    <a href="{{ route($link['route']) }}"
                        @class([
                            'whitespace-nowrap border px-4 py-3 text-sm font-semibold uppercase tracking-wide transition duration-200',
                            'border-[#c9a84c] bg-[#c9a84c] text-[#0e0e0e]' => request()->routeIs($link['pattern']),
                            'border-[#2a2a2a] text-[#f0ece4] hover:border-[#c9a84c] hover:text-[#c9a84c]' => ! request()->routeIs($link['pattern']),
                        ])>
                        {{ $link['label'] }}
                    </a>
                @endforeach
            </nav>

            <form method="POST" action="{{ route('admin.logout') }}" class="hidden border-t border-[#2a2a2a] p-4 lg:block">
                @csrf
                <button type="submit" class="w-full border border-[#2a2a2a] px-4 py-3 text-sm font-semibold uppercase tracking-wide text-[#f0ece4] transition duration-200 hover:border-[#c9a84c] hover:text-[#c9a84c]">
                    Logout
                </button>
            </form>
        </aside>

        <div class="min-w-0 flex-1 lg:pl-72">
            <main class="mx-auto max-w-7xl px-5 py-8 sm:px-8">
                @if (session('status'))
                    <div class="mb-6 border border-[#c9a84c] bg-[#161616] px-4 py-3 text-sm text-[#c9a84c]">
                        {{ session('status') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
