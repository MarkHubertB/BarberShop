@extends('layouts.admin')

@section('title', 'Bookings')

@section('content')
    <div class="mb-8">
        <p class="text-sm font-bold uppercase tracking-[0.25em] text-[#8a6f32]">Admin</p>
        <h1 class="mt-3 font-serif text-4xl font-bold text-[#f0ece4]">Bookings</h1>
    </div>

    <form method="GET" action="{{ route('admin.bookings.index') }}" class="mb-6 grid gap-3 border border-[#2a2a2a] bg-[#161616] p-4 lg:grid-cols-[1fr_1fr_2fr_auto]">
        <input type="date" name="date" value="{{ request('date') }}" class="h-12 border border-[#2a2a2a] bg-[#1f1f1f] px-3 text-[#f0ece4] outline-none focus:border-[#c9a84c]">
        <select name="status" class="h-12 border border-[#2a2a2a] bg-[#1f1f1f] px-3 text-[#f0ece4] outline-none focus:border-[#c9a84c]">
            <option value="">All statuses</option>
            @foreach ($statuses as $status)
                <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
            @endforeach
        </select>
        <input type="search" name="search" value="{{ request('search') }}" placeholder="Search name, reference, email, phone" class="h-12 border border-[#2a2a2a] bg-[#1f1f1f] px-3 text-[#f0ece4] outline-none focus:border-[#c9a84c]">
        <button type="submit" class="h-12 bg-[#c9a84c] px-5 font-bold uppercase tracking-wide text-[#0e0e0e] transition duration-200 hover:bg-[#8a6f32]">Filter</button>
    </form>

    <section class="border border-[#2a2a2a] bg-[#161616]">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[1100px] text-left text-sm">
                <thead class="border-b border-[#2a2a2a] text-xs uppercase tracking-wide text-[#8a6f32]">
                    <tr>
                        <th class="px-5 py-4">Reference</th>
                        <th class="px-5 py-4">Customer</th>
                        <th class="px-5 py-4">Phone</th>
                        <th class="px-5 py-4">Service</th>
                        <th class="px-5 py-4">Date</th>
                        <th class="px-5 py-4">Time</th>
                        <th class="px-5 py-4">Barber</th>
                        <th class="px-5 py-4">Status</th>
                        <th class="px-5 py-4">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#2a2a2a]">
                    @forelse ($bookings as $booking)
                        @php
                            $badge = match ($booking->status) {
                                'pending' => 'border-amber-500/50 text-amber-300',
                                'confirmed' => 'border-[#c9a84c] text-[#c9a84c]',
                                'completed' => 'border-emerald-500/50 text-emerald-300',
                                'cancelled' => 'border-[#b94a4a] text-[#b94a4a]',
                            };
                        @endphp
                        <tr>
                            <td class="px-5 py-4 font-bold text-[#c9a84c]">{{ $booking->reference_code }}</td>
                            <td class="px-5 py-4">
                                <div class="font-semibold text-[#f0ece4]">{{ $booking->customer_name }}</div>
                                <div class="text-xs text-[#6b6b6b]">{{ $booking->customer_email }}</div>
                            </td>
                            <td class="px-5 py-4 text-[#f0ece4]/75">{{ $booking->customer_phone }}</td>
                            <td class="px-5 py-4 text-[#f0ece4]/75">{{ $booking->service->name }}</td>
                            <td class="px-5 py-4 text-[#f0ece4]/75">{{ $booking->booking_date->format('M j, Y') }}</td>
                            <td class="px-5 py-4 text-[#f0ece4]/75">{{ $booking->timeSlot->label }}</td>
                            <td class="px-5 py-4 text-[#f0ece4]/75">{{ $booking->barber?->name ?? 'Any' }}</td>
                            <td class="px-5 py-4"><span class="border px-2 py-1 text-xs font-bold uppercase {{ $badge }}">{{ $booking->status }}</span></td>
                            <td class="px-5 py-4">
                                <div class="flex flex-wrap items-center gap-2">
                                    <a href="{{ route('admin.bookings.show', $booking) }}" class="border border-[#2a2a2a] px-3 py-2 text-[#f0ece4] transition duration-200 hover:border-[#c9a84c] hover:text-[#c9a84c]">View</a>
                                    @if ($booking->status !== 'confirmed')
                                        <form method="POST" action="{{ route('admin.bookings.status', $booking) }}">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="confirmed">
                                            <button type="submit" class="border border-[#c9a84c] px-3 py-2 text-[#c9a84c] transition duration-200 hover:bg-[#c9a84c] hover:text-[#0e0e0e]">Confirm</button>
                                        </form>
                                    @endif
                                    @if ($booking->status !== 'cancelled')
                                        <form method="POST" action="{{ route('admin.bookings.status', $booking) }}">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="cancelled">
                                            <button type="submit" class="border border-[#b94a4a] px-3 py-2 text-[#b94a4a] transition duration-200 hover:bg-[#b94a4a] hover:text-[#0e0e0e]">Cancel</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-5 py-8 text-center text-[#6b6b6b]">No bookings match these filters.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <div class="mt-6">
        {{ $bookings->links() }}
    </div>
@endsection
