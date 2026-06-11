<?php

namespace App\Http\Livewire;

use App\Models\Barber;
use App\Models\Booking;
use App\Models\Service;
use App\Models\TimeSlot;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\View\View;
use Livewire\Component;

class BookingCalendar extends Component
{
    public string $currentMonth;
    public ?string $selectedDate = null;
    public ?int $selectedSlot = null;
    public ?int $selectedBarber = null;
    public ?int $selectedService = null;
    public array $bookedSlots = [];
    public string $customerName = '';
    public string $customerEmail = '';
    public string $customerPhone = '';
    public string $notes = '';
    public string $step = 'calendar';
    public ?string $confirmedReference = null;

    public function mount(): void
    {
        $this->currentMonth = now()->format('Y-m');
    }

    public function prevMonth(): void
    {
        $candidate = Carbon::createFromFormat('Y-m', $this->currentMonth)->startOfMonth()->subMonth();

        if ($candidate->lt(now()->startOfMonth())) {
            return;
        }

        $this->currentMonth = $candidate->format('Y-m');
        $this->resetSelection();
    }

    public function nextMonth(): void
    {
        $this->currentMonth = Carbon::createFromFormat('Y-m', $this->currentMonth)
            ->startOfMonth()
            ->addMonth()
            ->format('Y-m');

        $this->resetSelection();
    }

    public function selectDate(string $date): void
    {
        $day = Carbon::parse($date)->startOfDay();

        if (($day->isPast() && ! $day->isToday()) || $this->isDateFullyBooked($day->toDateString())) {
            return;
        }

        $this->selectedDate = $day->toDateString();
        $this->selectedSlot = null;
        $this->selectedBarber = null;
        $this->bookedSlots = $this->bookedSlotIdsFor($this->selectedDate);
        $this->step = 'slots';
    }

    public function selectSlot(int $slotId): void
    {
        if (in_array($slotId, $this->bookedSlots, true)) {
            return;
        }

        if (! TimeSlot::active()->whereKey($slotId)->exists()) {
            return;
        }

        $this->selectedSlot = $slotId;
        $this->step = 'form';
    }

    public function submitBooking(): void
    {
        $this->validate([
            'customerName' => ['required', 'string', 'max:255'],
            'customerEmail' => ['required', 'email', 'max:255'],
            'customerPhone' => ['required', 'string', 'max:30'],
            'selectedService' => ['required', 'integer', 'exists:services,id'],
            'selectedDate' => ['required', 'date', 'after_or_equal:today'],
            'selectedSlot' => ['required', 'integer', 'exists:time_slots,id'],
            'selectedBarber' => ['nullable', 'integer', 'exists:barbers,id'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ], attributes: [
            'customerName' => 'full name',
            'customerEmail' => 'email',
            'customerPhone' => 'phone',
            'selectedService' => 'service',
            'selectedDate' => 'booking date',
            'selectedSlot' => 'time slot',
            'selectedBarber' => 'barber',
        ]);

        if (! Service::active()->whereKey($this->selectedService)->exists()) {
            $this->addError('selectedService', 'Please choose an active service.');

            return;
        }

        if (! TimeSlot::active()->whereKey($this->selectedSlot)->exists()) {
            $this->addError('selectedSlot', 'Please choose an active time slot.');

            return;
        }

        try {
            $booking = DB::transaction(function () {
                $barberId = $this->availableBarberId();

                if (! $barberId) {
                    return null;
                }

                return Booking::create([
                    'customer_name' => $this->customerName,
                    'customer_email' => $this->customerEmail,
                    'customer_phone' => $this->customerPhone,
                    'service_id' => $this->selectedService,
                    'barber_id' => $barberId,
                    'booking_date' => $this->selectedDate,
                    'time_slot_id' => $this->selectedSlot,
                    'notes' => $this->notes ?: null,
                    'status' => 'confirmed',
                ]);
            });
        } catch (QueryException) {
            $booking = null;
        }

        if (! $booking) {
            $this->bookedSlots = $this->bookedSlotIdsFor($this->selectedDate);
            $this->selectedSlot = null;
            $this->step = 'slots';
            $this->addError('selectedSlot', 'That time was just taken. Please choose another slot.');

            return;
        }

        $booking->load(['service', 'barber', 'timeSlot']);
        $this->sendConfirmationNotification($booking);
        $this->confirmedReference = $booking->reference_code;
        $this->selectedBarber = $booking->barber_id;
        $this->step = 'done';
    }

    public function resetBooking(): void
    {
        $this->reset([
            'selectedDate',
            'selectedSlot',
            'selectedBarber',
            'selectedService',
            'bookedSlots',
            'customerName',
            'customerEmail',
            'customerPhone',
            'notes',
            'confirmedReference',
        ]);

        $this->step = 'calendar';
    }

    public function render(): View
    {
        $confirmedBooking = $this->confirmedReference
            ? Booking::with(['service', 'barber', 'timeSlot'])->where('reference_code', $this->confirmedReference)->first()
            : null;

        return view('livewire.booking-calendar', [
            'barbers' => Barber::active()->orderBy('name')->get(),
            'calendarDays' => $this->calendarDays(),
            'fullyBookedDates' => $this->fullyBookedDatesForCurrentMonth(),
            'isPastMonth' => Carbon::createFromFormat('Y-m', $this->currentMonth)->startOfMonth()->lte(now()->startOfMonth()),
            'monthTitle' => Carbon::createFromFormat('Y-m', $this->currentMonth)->format('F Y'),
            'services' => Service::active()->orderBy('name')->get(),
            'slots' => TimeSlot::active()->orderBy('start_time')->get(),
            'confirmedBooking' => $confirmedBooking,
            'googleCalendarUrl' => $confirmedBooking ? $this->googleCalendarUrl($confirmedBooking) : null,
        ]);
    }

    private function resetSelection(): void
    {
        $this->selectedDate = null;
        $this->selectedSlot = null;
        $this->selectedBarber = null;
        $this->bookedSlots = [];
        $this->step = 'calendar';
    }

    private function calendarDays(): array
    {
        $month = Carbon::createFromFormat('Y-m', $this->currentMonth)->startOfMonth();
        $cursor = $month->copy()->startOfWeek(Carbon::MONDAY);
        $end = $month->copy()->endOfMonth()->endOfWeek(Carbon::SUNDAY);
        $days = [];

        while ($cursor->lte($end)) {
            $days[] = [
                'date' => $cursor->toDateString(),
                'day' => $cursor->day,
                'inMonth' => $cursor->month === $month->month,
                'isPast' => $cursor->isPast() && ! $cursor->isToday(),
                'isToday' => $cursor->isToday(),
            ];

            $cursor->addDay();
        }

        return $days;
    }

    private function bookedSlotIdsFor(string $date): array
    {
        $barberIds = Barber::active()->pluck('id');
        $activeBarberCount = $barberIds->count();

        if ($activeBarberCount === 0) {
            return TimeSlot::active()->pluck('id')->all();
        }

        return Booking::query()
            ->select('time_slot_id')
            ->whereDate('booking_date', $date)
            ->whereIn('status', ['pending', 'confirmed'])
            ->whereIn('barber_id', $barberIds)
            ->groupBy('time_slot_id')
            ->havingRaw('COUNT(DISTINCT barber_id) >= ?', [$activeBarberCount])
            ->pluck('time_slot_id')
            ->map(fn ($id) => (int) $id)
            ->all();
    }

    private function fullyBookedDatesForCurrentMonth(): array
    {
        $slotIds = TimeSlot::active()->pluck('id');
        $barberIds = Barber::active()->pluck('id');

        if ($slotIds->isEmpty() || $barberIds->isEmpty()) {
            return [];
        }

        $month = Carbon::createFromFormat('Y-m', $this->currentMonth)->startOfMonth();
        $bookings = Booking::query()
            ->select('booking_date', 'time_slot_id', 'barber_id')
            ->whereBetween('booking_date', [$month->toDateString(), $month->copy()->endOfMonth()->toDateString()])
            ->whereIn('status', ['pending', 'confirmed'])
            ->whereIn('time_slot_id', $slotIds)
            ->whereIn('barber_id', $barberIds)
            ->get()
            ->groupBy(fn (Booking $booking) => $booking->booking_date->toDateString());

        $fullyBooked = [];
        $cursor = $month->copy();
        $end = $month->copy()->endOfMonth();

        while ($cursor->lte($end)) {
            if (! $cursor->isPast() || $cursor->isToday()) {
                $dateBookings = $bookings->get($cursor->toDateString(), collect());

                if ($this->allSlotsUnavailable($dateBookings, $slotIds, $barberIds->count())) {
                    $fullyBooked[] = $cursor->toDateString();
                }
            }

            $cursor->addDay();
        }

        return $fullyBooked;
    }

    private function allSlotsUnavailable(Collection $dateBookings, Collection $slotIds, int $activeBarberCount): bool
    {
        foreach ($slotIds as $slotId) {
            $bookedBarbers = $dateBookings
                ->where('time_slot_id', $slotId)
                ->pluck('barber_id')
                ->unique()
                ->count();

            if ($bookedBarbers < $activeBarberCount) {
                return false;
            }
        }

        return true;
    }

    private function isDateFullyBooked(string $date): bool
    {
        return in_array($date, $this->fullyBookedDatesForCurrentMonth(), true);
    }

    private function availableBarberId(): ?int
    {
        if ($this->selectedBarber) {
            $barber = Barber::active()->whereKey($this->selectedBarber)->first();

            return $barber?->isAvailableOn($this->selectedDate, $this->selectedSlot) ? $barber->id : null;
        }

        return Barber::active()
            ->whereDoesntHave('bookings', function ($query) {
                $query->whereDate('booking_date', $this->selectedDate)
                    ->where('time_slot_id', $this->selectedSlot)
                    ->whereIn('status', ['pending', 'confirmed']);
            })
            ->orderBy('name')
            ->value('id');
    }

    private function sendConfirmationNotification(Booking $booking): void
    {
        if (! class_exists(\App\Notifications\BookingConfirmed::class)) {
            return;
        }

        Notification::route('mail', $booking->customer_email)
            ->notify(new \App\Notifications\BookingConfirmed($booking));
    }

    private function googleCalendarUrl(Booking $booking): string
    {
        $start = Carbon::parse($booking->booking_date->toDateString() . ' ' . $booking->timeSlot->start_time);
        $end = Carbon::parse($booking->booking_date->toDateString() . ' ' . $booking->timeSlot->end_time);
        $details = sprintf(
            'Your %s booking at The Blade Room with %s. Reference: %s',
            $booking->service->name,
            $booking->barber?->name ?? 'your barber',
            $booking->reference_code,
        );

        return 'https://calendar.google.com/calendar/render?' . http_build_query([
            'action' => 'TEMPLATE',
            'text' => 'The Blade Room Booking',
            'dates' => $start->utc()->format('Ymd\THis\Z') . '/' . $end->utc()->format('Ymd\THis\Z'),
            'details' => $details,
            'location' => 'The Blade Room',
        ]);
    }
}
