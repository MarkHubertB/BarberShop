@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <div class="mb-8 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div>
            <p class="text-sm font-bold uppercase tracking-[0.25em] text-[#8a6f32]">Admin</p>
            <h1 class="mt-3 font-serif text-4xl font-bold text-[#f0ece4]">Dashboard</h1>
        </div>
        <a href="{{ route('admin.bookings.index') }}" class="inline-flex h-12 items-center justify-center border border-[#c9a84c] px-5 text-sm font-bold uppercase tracking-wide text-[#c9a84c] transition duration-200 hover:bg-[#c9a84c] hover:text-[#0e0e0e]">Manage Bookings</a>
    </div>

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @foreach ([
            ['label' => 'Total Bookings Today', 'value' => $todayBookings],
            ['label' => 'Pending', 'value' => $pendingBookings],
            ['label' => 'Confirmed', 'value' => $confirmedBookings],
            ['label' => 'Revenue This Month', 'value' => number_format($monthlyRevenue, 0)],
        ] as $stat)
            <article class="border border-[#2a2a2a] bg-[#161616] p-5">
                <p class="text-sm uppercase tracking-wide text-[#6b6b6b]">{{ $stat['label'] }}</p>
                <p class="mt-3 font-serif text-4xl font-bold text-[#c9a84c]">{{ $stat['value'] }}</p>
            </article>
        @endforeach
    </div>

    <section class="mt-8 border border-[#2a2a2a] bg-[#161616]">
        <div class="border-b border-[#2a2a2a] p-5">
            <h2 class="font-serif text-2xl font-bold text-[#f0ece4]">Recent Bookings</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full min-w-[900px] text-left text-sm">
                <thead class="border-b border-[#2a2a2a] text-xs uppercase tracking-wide text-[#8a6f32]">
                    <tr>
                        <th class="px-5 py-4">Reference</th>
                        <th class="px-5 py-4">Customer</th>
                        <th class="px-5 py-4">Service</th>
                        <th class="px-5 py-4">Date & Time</th>
                        <th class="px-5 py-4">Barber</th>
                        <th class="px-5 py-4">Status</th>
                        <th class="px-5 py-4">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#2a2a2a]">
                    @forelse ($recentBookings as $booking)
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
                            <td class="px-5 py-4 text-[#f0ece4]">{{ $booking->customer_name }}</td>
                            <td class="px-5 py-4 text-[#f0ece4]/75">{{ $booking->service->name }}</td>
                            <td class="px-5 py-4 text-[#f0ece4]/75">{{ $booking->booking_date->format('M j, Y') }} at {{ $booking->timeSlot->label }}</td>
                            <td class="px-5 py-4 text-[#f0ece4]/75">{{ $booking->barber?->name ?? 'Any' }}</td>
                            <td class="px-5 py-4"><span class="border px-2 py-1 text-xs font-bold uppercase {{ $badge }}">{{ $booking->status }}</span></td>
                            <td class="px-5 py-4"><a href="{{ route('admin.bookings.show', $booking) }}" class="text-[#c9a84c] hover:text-[#f0ece4]">View</a></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-8 text-center text-[#6b6b6b]">No bookings yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
