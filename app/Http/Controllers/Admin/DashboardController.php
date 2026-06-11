<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $monthStart = now()->startOfMonth()->toDateString();
        $monthEnd = now()->endOfMonth()->toDateString();

        return view('admin.dashboard', [
            'todayBookings' => Booking::whereDate('booking_date', today())->count(),
            'pendingBookings' => Booking::where('status', 'pending')->count(),
            'confirmedBookings' => Booking::where('status', 'confirmed')->count(),
            'monthlyRevenue' => Booking::query()
                ->join('services', 'bookings.service_id', '=', 'services.id')
                ->whereBetween('booking_date', [$monthStart, $monthEnd])
                ->whereIn('status', ['confirmed', 'completed'])
                ->sum('services.price'),
            'recentBookings' => Booking::with(['service', 'barber', 'timeSlot'])
                ->latest()
                ->limit(10)
                ->get(),
        ]);
    }
}
