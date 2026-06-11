@extends('layouts.admin')

@section('title', $booking->reference_code)

@section('content')
    <div class="mb-8 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div>
            <p class="text-sm font-bold uppercase tracking-[0.25em] text-[#8a6f32]">Booking</p>
            <h1 class="mt-3 font-serif text-4xl font-bold text-[#c9a84c]">{{ $booking->reference_code }}</h1>
        </div>
        <a href="{{ route('admin.bookings.index') }}" class="inline-flex h-12 items-center justify-center border border-[#2a2a2a] px-5 text-sm font-bold uppercase tracking-wide text-[#f0ece4] transition duration-200 hover:border-[#c9a84c] hover:text-[#c9a84c]">Back</a>
    </div>

    <div class="grid gap-6 lg:grid-cols-[1.2fr_0.8fr]">
        <section class="border border-[#2a2a2a] bg-[#161616] p-6">
            <h2 class="font-serif text-2xl font-bold text-[#f0ece4]">Booking Details</h2>
            <dl class="mt-6 grid gap-5 sm:grid-cols-2">
                <div>
                    <dt class="text-sm text-[#6b6b6b]">Customer</dt>
                    <dd class="mt-1 font-semibold text-[#f0ece4]">{{ $booking->customer_name }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-[#6b6b6b]">Email</dt>
                    <dd class="mt-1 font-semibold text-[#f0ece4]">{{ $booking->customer_email }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-[#6b6b6b]">Phone</dt>
                    <dd class="mt-1 font-semibold text-[#f0ece4]">{{ $booking->customer_phone }}</dd>
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

            <div class="mt-6">
                <p class="text-sm text-[#6b6b6b]">Notes</p>
                <p class="mt-2 min-h-24 border border-[#2a2a2a] bg-[#1f1f1f] p-4 leading-7 text-[#f0ece4]/75">{{ $booking->notes ?: 'No notes provided.' }}</p>
            </div>
        </section>

        <form method="POST" action="{{ route('admin.bookings.update', $booking) }}" class="border border-[#2a2a2a] bg-[#161616] p-6">
            @csrf
            @method('PATCH')
            <h2 class="font-serif text-2xl font-bold text-[#f0ece4]">Update Status</h2>

            <label class="mt-6 block">
                <span class="text-sm text-[#f0ece4]">Status</span>
                <select name="status" class="mt-2 h-12 w-full border border-[#2a2a2a] bg-[#1f1f1f] px-3 text-[#f0ece4] outline-none focus:border-[#c9a84c]">
                    @foreach ($statuses as $status)
                        <option value="{{ $status }}" @selected(old('status', $booking->status) === $status)>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
                @error('status') <span class="mt-1 block text-sm text-[#b94a4a]">{{ $message }}</span> @enderror
            </label>

            <label class="mt-4 block">
                <span class="text-sm text-[#f0ece4]">Notes</span>
                <textarea name="notes" rows="6" class="mt-2 w-full border border-[#2a2a2a] bg-[#1f1f1f] px-3 py-3 text-[#f0ece4] outline-none focus:border-[#c9a84c]">{{ old('notes', $booking->notes) }}</textarea>
                @error('notes') <span class="mt-1 block text-sm text-[#b94a4a]">{{ $message }}</span> @enderror
            </label>

            <button type="submit" class="mt-6 h-12 w-full bg-[#c9a84c] px-5 font-bold uppercase tracking-wide text-[#0e0e0e] transition duration-200 hover:bg-[#8a6f32]">Update</button>
        </form>
    </div>
@endsection
