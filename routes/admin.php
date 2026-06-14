<?php

use App\Http\Controllers\Admin\BarberController;
use App\Http\Controllers\Admin\BookingAdminController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ServiceController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')
    ->middleware('admin.auth')
    ->name('admin.')
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('bookings', BookingAdminController::class)->only(['index', 'show', 'update']);
        Route::patch('bookings/{booking}/status', [BookingAdminController::class, 'updateStatus'])->name('bookings.status');
        Route::resource('services', ServiceController::class);
        Route::resource('barbers', BarberController::class);
    });
