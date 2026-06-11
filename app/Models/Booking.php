<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Booking extends Model
{
    protected $fillable = [
        'reference_code',
        'customer_name',
        'customer_email',
        'customer_phone',
        'service_id',
        'barber_id',
        'booking_date',
        'time_slot_id',
        'notes',
        'status',
        'reminder_sent',
    ];

    protected function casts(): array
    {
        return [
            'booking_date' => 'date',
            'reminder_sent' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Booking $booking) {
            if (! $booking->reference_code) {
                do {
                    $reference = 'BLD-' . strtoupper(Str::random(4));
                } while (static::where('reference_code', $reference)->exists());

                $booking->reference_code = $reference;
            }
        });
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function barber(): BelongsTo
    {
        return $this->belongsTo(Barber::class);
    }

    public function timeSlot(): BelongsTo
    {
        return $this->belongsTo(TimeSlot::class);
    }
}
