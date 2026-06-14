@extends('layouts.app')

@section('title', 'The Blade Room')

@section('content')
    <section class="relative flex min-h-[calc(100vh-5rem)] items-center overflow-hidden border-b border-[#2a2a2a]">
        <div class="absolute inset-0 bg-[url('https://placehold.co/1800x1200/0e0e0e/c9a84c?text=The+Blade+Room')] bg-cover bg-center opacity-20"></div>
        <div class="absolute inset-0 bg-[#0e0e0e]/75"></div>
        <div class="pointer-events-none absolute inset-0 opacity-20 [background-image:radial-gradient(#c9a84c_1px,transparent_1px)] [background-size:18px_18px]"></div>

        <div class="relative mx-auto w-full max-w-7xl px-5 py-24 sm:px-8">
            <p class="mb-5 text-sm font-bold uppercase tracking-[0.35em] text-[#c9a84c]">The Blade Room</p>
            <h1 class="max-w-4xl font-serif text-5xl font-bold leading-tight text-[#f0ece4] sm:text-7xl lg:text-8xl">
                Precision. Style. Confidence.
            </h1>
            <p class="mt-6 max-w-xl text-lg leading-8 text-[#f0ece4]/80">
                Premium cuts for the modern gentleman.
            </p>
            <a href="{{ route('booking') }}" class="mt-10 inline-flex min-h-12 items-center justify-center bg-[#c9a84c] px-7 font-bold uppercase tracking-wide text-[#0e0e0e] transition duration-200 hover:bg-[#8a6f32]">
                Book Your Cut &rarr;
            </a>
        </div>
    </section>

    <section class="border-b border-[#2a2a2a] bg-[#0e0e0e] py-20">
        <div class="mx-auto max-w-7xl px-5 sm:px-8">
            <div class="mb-10 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
                <div>
                    <p class="text-sm font-bold uppercase tracking-[0.25em] text-[#8a6f32]">Services</p>
                    <h2 class="mt-3 font-serif text-4xl font-bold text-[#f0ece4]">The Cuts</h2>
                </div>
                <a href="{{ route('booking') }}" class="text-sm font-bold uppercase tracking-wide text-[#c9a84c] transition duration-200 hover:text-[#f0ece4]">Reserve a chair</a>
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                @foreach ($services as $service)
                    <article class="border border-[#2a2a2a] bg-[#161616] transition duration-200 hover:border-l-[#c9a84c]">
    @if ($service->image_url)
        <img src="{{ $service->image_url }}" alt="{{ $service->name }}" class="aspect-video w-full object-cover">
    @endif
    <div class="p-6 md:p-7">
        <div class="flex items-start justify-between gap-4">
                            <h3 class="font-serif text-2xl font-bold text-[#f0ece4]">{{ $service->name }}</h3>
                            <div class="text-right">
                                <p class="font-bold text-[#c9a84c]">{{ number_format($service->price, 0) }}</p>
                                <p class="text-xs uppercase tracking-wide text-[#6b6b6b]">{{ $service->duration_minutes }} min</p>
                            </div>
                        </div>
                       <p class="mt-4 leading-7 text-[#f0ece4]/70">{{ $service->description }}</p>
        </div>
    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="border-b border-[#2a2a2a] bg-[#161616] py-20">
        <div class="mx-auto max-w-7xl px-5 sm:px-8">
            <h2 class="font-serif text-4xl font-bold text-[#f0ece4]">How It Works</h2>
            <div class="mt-10 grid gap-4 md:grid-cols-3">
                @foreach ([
                    ['title' => 'Choose Your Cut', 'body' => 'Pick a service that fits your style and schedule.', 'icon' => 'scissors'],
                    ['title' => 'Pick Date & Time', 'body' => 'Select a live slot with an available barber.', 'icon' => 'calendar'],
                    ['title' => 'Show Up Fresh', 'body' => 'Arrive on time and leave with a clean finish.', 'icon' => 'check'],
                ] as $item)
                    <article class="border border-[#2a2a2a] bg-[#0e0e0e] p-6">
                        <div class="mb-5 flex h-12 w-12 items-center justify-center border border-[#c9a84c] text-[#c9a84c]">
                            @if ($item['icon'] === 'scissors')
                                <svg viewBox="0 0 24 24" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><circle cx="6" cy="6" r="3"></circle><circle cx="6" cy="18" r="3"></circle><path d="M20 4 8.5 15.5"></path><path d="m8.5 8.5 3 3"></path><path d="M20 20 8.5 8.5"></path></svg>
                            @elseif ($item['icon'] === 'calendar')
                                <svg viewBox="0 0 24 24" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><rect x="3" y="4" width="18" height="17" rx="1"></rect><path d="M8 2v4M16 2v4M3 10h18"></path></svg>
                            @else
                                <svg viewBox="0 0 24 24" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="m4 12 5 5L20 6"></path></svg>
                            @endif
                        </div>
                        <h3 class="font-bold text-[#f0ece4]">{{ $item['title'] }}</h3>
                        <p class="mt-3 leading-7 text-[#f0ece4]/65">{{ $item['body'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="border-b border-[#2a2a2a] bg-[#0e0e0e] py-20">
        <div class="mx-auto max-w-7xl px-5 sm:px-8">
            <h2 class="font-serif text-4xl font-bold text-[#f0ece4]">Our Barbers</h2>
            <div class="mt-10 grid gap-5 md:grid-cols-3">
                @foreach ($barbers as $barber)
                    <article class="border border-[#2a2a2a] bg-[#161616]">
                        <img src="{{ $barber->photo_url }}" alt="{{ $barber->name }}" class="aspect-[4/5] w-full object-cover">
                        <div class="p-6">
                            <h3 class="font-serif text-2xl font-bold text-[#f0ece4]">{{ $barber->name }}</h3>
                            <p class="mt-3 inline-flex border border-[#c9a84c] px-3 py-1 text-xs font-bold uppercase tracking-wide text-[#c9a84c]">{{ $barber->specialty }}</p>
                            <p class="mt-4 leading-7 text-[#f0ece4]/65">{{ $barber->bio }}</p>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="border-b border-[#2a2a2a] bg-[#161616] py-20">
        <div class="mx-auto max-w-7xl px-5 sm:px-8">
            <h2 class="font-serif text-4xl font-bold text-[#f0ece4]">Client Notes</h2>
            <div class="mt-10 grid gap-4 md:grid-cols-3">
                @foreach ([
                    ['quote' => 'The cleanest fade I have had in years. Sharp, calm, exact.', 'name' => 'Adrian M.'],
                    ['quote' => 'Booking took a minute and the cut looked better than the reference.', 'name' => 'Leo R.'],
                    ['quote' => 'Premium without feeling stiff. The whole shop has serious taste.', 'name' => 'Marcus T.'],
                ] as $testimonial)
                    <figure class="border border-[#2a2a2a] bg-[#0e0e0e] p-6">
                        <div class="font-serif text-5xl leading-none text-[#c9a84c]">&ldquo;</div>
                        <blockquote class="mt-3 italic leading-8 text-[#f0ece4]/75">{{ $testimonial['quote'] }}</blockquote>
                        <figcaption class="mt-5 text-sm font-bold uppercase tracking-wide text-[#8a6f32]">{{ $testimonial['name'] }}</figcaption>
                    </figure>
                @endforeach
            </div>
        </div>
    </section>

    <footer class="bg-[#0e0e0e] py-12">
        <div class="mx-auto grid max-w-7xl gap-8 px-5 sm:px-8 lg:grid-cols-[1fr_1fr_1.2fr]">
            <div>
                <p class="font-serif text-3xl font-bold text-[#f0ece4]">The Blade Room</p>
                <p class="mt-4 text-[#f0ece4]/65">Premium cuts for the modern gentleman.</p>
            </div>
            <div class="space-y-2 text-sm text-[#f0ece4]/70">
                <p class="font-bold uppercase tracking-wide text-[#c9a84c]">Visit</p>
                <p>18 Gold Street</p>
                <p>Mon-Sat 9AM-8PM</p>
                <div class="flex gap-3 pt-2">
                    <a href="#" aria-label="Instagram" class="flex h-11 w-11 items-center justify-center border border-[#2a2a2a] text-[#c9a84c] transition duration-200 hover:border-[#c9a84c]">IG</a>
                    <a href="#" aria-label="Facebook" class="flex h-11 w-11 items-center justify-center border border-[#2a2a2a] text-[#c9a84c] transition duration-200 hover:border-[#c9a84c]">FB</a>
                </div>
            </div>
            <iframe title="Google Maps location" class="min-h-64 w-full border border-[#2a2a2a]" loading="lazy" referrerpolicy="no-referrer-when-downgrade" src="https://www.google.com/maps?q=barbershop&output=embed"></iframe>
        </div>
    </footer>
@endsection
