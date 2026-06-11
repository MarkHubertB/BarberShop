<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Barber extends Model
{
    protected $fillable = [
        'name',
        'bio',
        'photo_url',
        'specialty',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function isAvailableOn(string $date, int $timeSlotId): bool
    {
        return ! $this->bookings()
            ->whereDate('booking_date', $date)
            ->where('time_slot_id', $timeSlotId)
            ->whereIn('status', ['pending', 'confirmed'])
            ->exists();
    }
}
