<?php

namespace Database\Seeders;

use App\Models\TimeSlot;
use Carbon\CarbonImmutable;
use Illuminate\Database\Seeder;

class TimeSlotSeeder extends Seeder
{
    public function run(): void
    {
        $start = CarbonImmutable::createFromTime(9);

        for ($hour = 0; $hour < 12; $hour++) {
            $slotStart = $start->addHours($hour);
            $slotEnd = $slotStart->addHour();

            TimeSlot::updateOrCreate(
                ['start_time' => $slotStart->format('H:i:s')],
                [
                    'label' => $slotStart->format('g:00 A'),
                    'end_time' => $slotEnd->format('H:i:s'),
                    'is_active' => true,
                ],
            );
        }
    }
}
