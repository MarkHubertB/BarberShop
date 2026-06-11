<?php

namespace Database\Seeders;

use App\Models\Barber;
use Illuminate\Database\Seeder;

class BarberSeeder extends Seeder
{
    public function run(): void
    {
        $barbers = [
            [
                'name' => 'Marco',
                'bio' => 'A precision cutter known for refined fades, clean silhouettes, and quiet attention to detail.',
                'photo_url' => 'https://placehold.co/400x500/161616/c9a84c?text=Marco',
                'specialty' => 'Skin fades and textured crops',
            ],
            [
                'name' => 'Diego',
                'bio' => 'A shape specialist with a strong eye for flow, proportion, and modern longer cuts.',
                'photo_url' => 'https://placehold.co/400x500/161616/c9a84c?text=Diego',
                'specialty' => 'Mullets, quiffs, and slick backs',
            ],
            [
                'name' => 'Rex',
                'bio' => 'A detail-driven barber focused on bold transformations and crisp finishing work.',
                'photo_url' => 'https://placehold.co/400x500/161616/c9a84c?text=Rex',
                'specialty' => 'Warrior cuts and taper fades',
            ],
        ];

        foreach ($barbers as $barber) {
            Barber::updateOrCreate(
                ['name' => $barber['name']],
                [...$barber, 'is_active' => true],
            );
        }
    }
}
