@extends('layouts.app')

@section('title', 'Booking Confirmed')

@section('content')
    <section class="bg-[#0e0e0e] py-16 sm:py-24">
        <div class="mx-auto max-w-3xl px-5 sm:px-8">
            <div class="border border-[#c9a84c] bg-[#161616] p-6 sm:p-8">
                <p class="text-sm font-bold uppercase tracking-[0.25em] text-[#8a6f32]">Booking confirmed</p>
                <h1 class="mt-3 font-serif text-5xl font-bold text-[#c9a84c]">{{ $booking->reference_code }}</h1>

                <dl class="mt-8 grid gap-5 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm text-[#6b6b6b]">Customer</dt>
                        <dd class="mt-1 font-semibold text-[#f0ece4]">{{ $booking->customer_name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-[#6b6b6b]">Service</dt>
                        <dd class="mt-1 font-semibold text-[#f0ece4]">{{ $booking->service->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-[#6b6b6b]">Date & Time</dt>
                        <dd class="mt-1 font-semibold text-[#f0ece4]">{{ $booking->booking_date->format('F j, Y') }} at {{ $booking->timeSlot->label }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-[#6b6b6b]">Barber</dt>
                        <dd class="mt-1 font-semibold text-[#f0ece4]">{{ $booking->barber?->name ?? 'Any available barber' }}</dd>
                    </div>
                </dl>

                <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                    <a href="{{ $googleCalendarUrl }}" target="_blank" rel="noopener" class="inline-flex h-12 items-center justify-center border border-[#c9a84c] px-5 font-bold uppercase tracking-wide text-[#c9a84c] transition duration-200 hover:bg-[#c9a84c] hover:text-[#0e0e0e]">Add to Calendar</a>
                    <a href="{{ route('booking') }}" class="inline-flex h-12 items-center justify-center bg-[#c9a84c] px-5 font-bold uppercase tracking-wide text-[#0e0e0e] transition duration-200 hover:bg-[#8a6f32]">Book Another</a>
                </div>
            </div>
        </div>
    </section>
@endsection
