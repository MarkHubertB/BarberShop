<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

class BookingAdminController extends Controller
{
    private const STATUSES = ['pending', 'confirmed', 'completed', 'cancelled'];

    public function index(Request $request): View
    {
        $bookings = Booking::with(['service', 'barber', 'timeSlot'])
            ->when($request->filled('date'), fn ($query) => $query->whereDate('booking_date', $request->date))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = '%' . $request->search . '%';

                $query->where(function ($query) use ($search) {
                    $query->where('reference_code', 'like', $search)
                        ->orWhere('customer_name', 'like', $search)
                        ->orWhere('customer_email', 'like', $search)
                        ->orWhere('customer_phone', 'like', $search);
                });
            })
            ->orderByDesc('booking_date')
            ->orderBy('time_slot_id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.bookings.index', [
            'bookings' => $bookings,
            'statuses' => self::STATUSES,
        ]);
    }

    public function show(Booking $booking): View
    {
        return view('admin.bookings.show', [
            'booking' => $booking->load(['service', 'barber', 'timeSlot']),
            'statuses' => self::STATUSES,
        ]);
    }

    public function update(Request $request, Booking $booking): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(self::STATUSES)],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $booking->update($data);

        return redirect()->route('admin.bookings.show', $booking)->with('status', 'Booking updated.');
    }

    public function updateStatus(Request $request, Booking $booking): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(self::STATUSES)],
        ]);

        $booking->update($data);

        return back()->with('status', 'Booking status updated.');
    }
}
