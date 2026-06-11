<section class="mx-auto w-full max-w-5xl text-[#f0ece4]" x-data="{ step: @entangle('step') }">
    <div class="border border-[#2a2a2a] bg-[#161616] p-4 sm:p-6">
        <div class="mb-5 flex items-center justify-between gap-3">
            <button
                type="button"
                wire:click="prevMonth"
                @disabled($isPastMonth)
                aria-label="Previous month"
                class="flex h-11 w-11 items-center justify-center border border-[#2a2a2a] text-[#f0ece4] transition duration-200 hover:border-[#c9a84c] hover:text-[#c9a84c] disabled:cursor-not-allowed disabled:opacity-30"
            >
                <span aria-hidden="true">&larr;</span>
            </button>

            <h2 class="font-serif text-2xl text-[#f0ece4] sm:text-3xl">{{ $monthTitle }}</h2>

            <button
                type="button"
                wire:click="nextMonth"
                aria-label="Next month"
                class="flex h-11 w-11 items-center justify-center border border-[#2a2a2a] text-[#f0ece4] transition duration-200 hover:border-[#c9a84c] hover:text-[#c9a84c]"
            >
                <span aria-hidden="true">&rarr;</span>
            </button>
        </div>

        <div class="grid grid-cols-7 gap-2 text-center text-xs uppercase tracking-wide text-[#8a6f32] sm:text-sm">
            @foreach (['Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa', 'Su'] as $weekday)
                <div class="py-2">{{ $weekday }}</div>
            @endforeach
        </div>

        <div class="grid grid-cols-7 gap-2">
            @foreach ($calendarDays as $day)
                @php
                    $isSelected = $selectedDate === $day['date'];
                    $isFullyBooked = in_array($day['date'], $fullyBookedDates, true);
                    $isDisabled = ! $day['inMonth'] || $day['isPast'] || $isFullyBooked;
                @endphp

                <button
                    type="button"
                    wire:click="selectDate('{{ $day['date'] }}')"
                    @disabled($isDisabled)
                    @class([
                        'min-h-12 border p-2 text-sm transition duration-200 sm:min-h-14 sm:text-base',
                        'border-[#2a2a2a] text-[#6b6b6b] opacity-30 cursor-not-allowed' => ! $day['inMonth'] || $day['isPast'],
                        'border-[#b94a4a] bg-[#1a0a0a] text-[#6b6b6b] line-through cursor-not-allowed' => $day['inMonth'] && $isFullyBooked,
                        'border-[#2a2a2a] text-[#f0ece4] hover:border-[#c9a84c]' => $day['inMonth'] && ! $day['isPast'] && ! $isFullyBooked && ! $isSelected,
                        'border-[#c9a84c] bg-[#c9a84c] font-bold text-[#0e0e0e]' => $isSelected,
                        'ring-1 ring-[#c9a84c]' => $day['isToday'] && ! $isSelected,
                    ])
                >
                    {{ $day['day'] }}
                </button>
            @endforeach
        </div>
    </div>

    <div x-show="step === 'slots' || step === 'form'" x-transition.opacity.duration.200ms class="mt-6 border border-[#2a2a2a] bg-[#161616] p-4 sm:p-6">
        <h3 class="font-serif text-xl text-[#f0ece4]">
            Available times
            @if ($selectedDate)
                <span class="text-[#c9a84c]">for {{ \Carbon\Carbon::parse($selectedDate)->format('F j') }}</span>
            @endif
        </h3>

        <div class="mt-5 grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4">
            @foreach ($slots as $slot)
                @php
                    $isBooked = in_array($slot->id, $bookedSlots, true);
                    $isSelected = $selectedSlot === $slot->id;
                @endphp

                <button
                    type="button"
                    wire:click="selectSlot({{ $slot->id }})"
                    @disabled($isBooked)
                    @class([
                        'min-h-12 border px-3 py-2 text-sm transition duration-200',
                        'border-[#c9a84c] text-[#f0ece4] hover:bg-[#c9a84c] hover:text-[#0e0e0e]' => ! $isBooked && ! $isSelected,
                        'border-[#b94a4a] bg-[#1a0a0a] text-[#6b6b6b] line-through cursor-not-allowed' => $isBooked,
                        'border-[#c9a84c] bg-[#c9a84c] font-bold text-[#0e0e0e]' => $isSelected,
                    ])
                >
                    {{ $slot->label }} @if ($isBooked) &times; @endif
                </button>
            @endforeach
        </div>

        @error('selectedSlot')
            <p class="mt-3 text-sm text-[#b94a4a]">{{ $message }}</p>
        @enderror
    </div>

    <form wire:submit="submitBooking" x-show="step === 'form'" x-transition.opacity.duration.200ms class="mt-6 border border-[#2a2a2a] bg-[#161616] p-4 sm:p-6">
        <h3 class="font-serif text-xl text-[#f0ece4]">Your details</h3>

        <div class="mt-5 grid gap-4 sm:grid-cols-2">
            <label class="block">
                <span class="text-sm text-[#f0ece4]">Full Name</span>
                <input type="text" wire:model.blur="customerName" autocomplete="name" class="mt-2 h-12 w-full border border-[#2a2a2a] bg-[#1f1f1f] px-3 text-[#f0ece4] outline-none transition duration-200 focus:border-[#c9a84c]">
                @error('customerName') <span class="mt-1 block text-sm text-[#b94a4a]">{{ $message }}</span> @enderror
            </label>

            <label class="block">
                <span class="text-sm text-[#f0ece4]">Email</span>
                <input type="email" wire:model.blur="customerEmail" autocomplete="email" class="mt-2 h-12 w-full border border-[#2a2a2a] bg-[#1f1f1f] px-3 text-[#f0ece4] outline-none transition duration-200 focus:border-[#c9a84c]">
                @error('customerEmail') <span class="mt-1 block text-sm text-[#b94a4a]">{{ $message }}</span> @enderror
            </label>

            <label class="block">
                <span class="text-sm text-[#f0ece4]">Phone</span>
                <input type="tel" wire:model.blur="customerPhone" autocomplete="tel" class="mt-2 h-12 w-full border border-[#2a2a2a] bg-[#1f1f1f] px-3 text-[#f0ece4] outline-none transition duration-200 focus:border-[#c9a84c]">
                @error('customerPhone') <span class="mt-1 block text-sm text-[#b94a4a]">{{ $message }}</span> @enderror
            </label>

            <label class="block">
                <span class="text-sm text-[#f0ece4]">Select Service</span>
                <select wire:model="selectedService" class="mt-2 h-12 w-full border border-[#2a2a2a] bg-[#1f1f1f] px-3 text-[#f0ece4] outline-none transition duration-200 focus:border-[#c9a84c]">
                    <option value="">Choose a cut</option>
                    @foreach ($services as $service)
                        <option value="{{ $service->id }}">{{ $service->name }} - {{ number_format($service->price) }}</option>
                    @endforeach
                </select>
                @error('selectedService') <span class="mt-1 block text-sm text-[#b94a4a]">{{ $message }}</span> @enderror
            </label>

            <label class="block sm:col-span-2">
                <span class="text-sm text-[#f0ece4]">Preferred Barber</span>
                <select wire:model="selectedBarber" class="mt-2 h-12 w-full border border-[#2a2a2a] bg-[#1f1f1f] px-3 text-[#f0ece4] outline-none transition duration-200 focus:border-[#c9a84c]">
                    <option value="">Any available barber</option>
                    @foreach ($barbers as $barber)
                        <option value="{{ $barber->id }}">{{ $barber->name }} - {{ $barber->specialty }}</option>
                    @endforeach
                </select>
                @error('selectedBarber') <span class="mt-1 block text-sm text-[#b94a4a]">{{ $message }}</span> @enderror
            </label>

            <label class="block sm:col-span-2">
                <span class="text-sm text-[#f0ece4]">Notes</span>
                <textarea wire:model.blur="notes" rows="4" class="mt-2 w-full border border-[#2a2a2a] bg-[#1f1f1f] px-3 py-3 text-[#f0ece4] outline-none transition duration-200 focus:border-[#c9a84c]"></textarea>
                @error('notes') <span class="mt-1 block text-sm text-[#b94a4a]">{{ $message }}</span> @enderror
            </label>
        </div>

        <button type="submit" class="mt-6 h-12 w-full bg-[#c9a84c] px-5 font-bold uppercase text-[#0e0e0e] transition duration-200 hover:bg-[#8a6f32] disabled:cursor-not-allowed disabled:opacity-50" wire:loading.attr="disabled">
            <span wire:loading.remove>Confirm Booking</span>
            <span wire:loading>Confirming...</span>
        </button>
    </form>

    <div x-show="step === 'done'" x-transition.opacity.duration.200ms class="mt-6 border border-[#c9a84c] bg-[#161616] p-4 sm:p-6">
        @if ($confirmedBooking)
            <p class="text-sm uppercase text-[#8a6f32]">Booking confirmed</p>
            <div class="mt-2 font-serif text-4xl text-[#c9a84c]">{{ $confirmedBooking->reference_code }}</div>

            <dl class="mt-5 grid gap-3 text-sm sm:grid-cols-2">
                <div>
                    <dt class="text-[#6b6b6b]">Service</dt>
                    <dd class="text-[#f0ece4]">{{ $confirmedBooking->service->name }}</dd>
                </div>
                <div>
                    <dt class="text-[#6b6b6b]">Date & Time</dt>
                    <dd class="text-[#f0ece4]">{{ $confirmedBooking->booking_date->format('F j, Y') }} at {{ $confirmedBooking->timeSlot->label }}</dd>
                </div>
                <div>
                    <dt class="text-[#6b6b6b]">Barber</dt>
                    <dd class="text-[#f0ece4]">{{ $confirmedBooking->barber?->name ?? 'Any available barber' }}</dd>
                </div>
            </dl>

            <div class="mt-6 flex flex-col gap-3 sm:flex-row">
                <a href="{{ $googleCalendarUrl }}" target="_blank" rel="noopener" class="inline-flex h-12 items-center justify-center border border-[#c9a84c] px-5 text-[#c9a84c] transition duration-200 hover:bg-[#c9a84c] hover:text-[#0e0e0e]">
                    Add to Calendar
                </a>
                <button type="button" wire:click="resetBooking" class="h-12 bg-[#c9a84c] px-5 font-bold uppercase text-[#0e0e0e] transition duration-200 hover:bg-[#8a6f32]">
                    Book Another
                </button>
            </div>
        @endif
    </div>
</section>
