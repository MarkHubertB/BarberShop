<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function index(): View
    {
        return view('pages.booking');
    }

    public function confirmation(string $reference): View
    {
        $booking = Booking::with(['service', 'barber', 'timeSlot'])
            ->where('reference_code', strtoupper($reference))
            ->firstOrFail();

        return view('pages.confirmation', [
            'booking' => $booking,
            'googleCalendarUrl' => $this->googleCalendarUrl($booking),
        ]);
    }

    private function googleCalendarUrl(Booking $booking): string
    {
        $start = Carbon::parse($booking->booking_date->toDateString() . ' ' . $booking->timeSlot->start_time);
        $end = Carbon::parse($booking->booking_date->toDateString() . ' ' . $booking->timeSlot->end_time);

        return 'https://calendar.google.com/calendar/render?' . http_build_query([
            'action' => 'TEMPLATE',
            'text' => 'The Blade Room Booking',
            'dates' => $start->utc()->format('Ymd\THis\Z') . '/' . $end->utc()->format('Ymd\THis\Z'),
            'details' => sprintf(
                'Your %s booking at The Blade Room with %s. Reference: %s',
                $booking->service->name,
                $booking->barber?->name ?? 'your barber',
                $booking->reference_code,
            ),
            'location' => 'The Blade Room',
        ]);
    }
}
