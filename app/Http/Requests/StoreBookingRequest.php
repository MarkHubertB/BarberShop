<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_email' => ['required', 'email', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:30'],
            'service_id' => [
                'required',
                'integer',
                Rule::exists('services', 'id')->where('is_active', true),
            ],
            'barber_id' => [
                'nullable',
                'integer',
                Rule::exists('barbers', 'id')->where('is_active', true),
            ],
            'booking_date' => ['required', 'date', 'after_or_equal:today'],
            'time_slot_id' => [
                'required',
                'integer',
                Rule::exists('time_slots', 'id')->where('is_active', true),
            ],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
