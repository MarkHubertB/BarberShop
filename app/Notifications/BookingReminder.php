<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingReminder extends Notification
{
    use Queueable;

    public function __construct(public Booking $booking)
    {
        $this->booking->loadMissing(['service', 'barber', 'timeSlot']);
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('See you tomorrow - ' . $this->booking->reference_code)
            ->greeting('Your appointment is tomorrow.')
            ->line('Reference: ' . $this->booking->reference_code)
            ->line('Service: ' . $this->booking->service->name)
            ->line('Date and time: ' . $this->booking->booking_date->format('F j, Y') . ' at ' . $this->booking->timeSlot->label)
            ->line('Barber: ' . ($this->booking->barber?->name ?? 'Any available barber'))
            ->line('Shop address: The Blade Room, 18 Gold Street');
    }
}
