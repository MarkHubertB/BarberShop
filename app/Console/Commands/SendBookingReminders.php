<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Notifications\BookingReminder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class SendBookingReminders extends Command
{
    protected $signature = 'bookings:send-reminders';

    protected $description = 'Send reminder emails for tomorrow bookings.';

    public function handle(): int
    {
        $sent = 0;

        Booking::with(['service', 'barber', 'timeSlot'])
            ->whereDate('booking_date', now()->addDay()->toDateString())
            ->where('reminder_sent', false)
            ->where('status', '!=', 'cancelled')
            ->chunkById(100, function ($bookings) use (&$sent) {
                foreach ($bookings as $booking) {
                    Notification::route('mail', $booking->customer_email)
                        ->notify(new BookingReminder($booking));

                    $booking->forceFill(['reminder_sent' => true])->save();
                    $sent++;
                }
            });

        $this->info("Sent {$sent} booking reminder(s).");

        return self::SUCCESS;
    }
}
