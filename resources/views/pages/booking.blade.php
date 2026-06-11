@extends('layouts.app')

@section('title', 'Book a Cut')

@section('content')
    <section class="border-b border-[#2a2a2a] bg-[#0e0e0e] py-16">
        <div class="mx-auto max-w-7xl px-5 sm:px-8">
            <p class="text-sm font-bold uppercase tracking-[0.25em] text-[#8a6f32]">Reserve a chair</p>
            <h1 class="mt-3 font-serif text-5xl font-bold text-[#f0ece4]">Book Your Cut</h1>
            <p class="mt-4 max-w-2xl leading-8 text-[#f0ece4]/70">Choose a date, pick a live time slot, and confirm your details. No account needed.</p>
        </div>
    </section>

    <section class="bg-[#0e0e0e] py-12 sm:py-16">
        <div class="mx-auto max-w-7xl px-5 sm:px-8">
            <livewire:booking-calendar />
        </div>
    </section>
@endsection
