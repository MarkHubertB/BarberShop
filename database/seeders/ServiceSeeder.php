<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            ['name' => 'Textured Crop', 'duration_minutes' => 30, 'price' => 250],
            ['name' => 'Textured Quiff', 'duration_minutes' => 35, 'price' => 280],
            ['name' => 'Modern Mullet', 'duration_minutes' => 40, 'price' => 300],
            ['name' => 'Blowout Low Taper Fade', 'duration_minutes' => 40, 'price' => 320],
            ['name' => 'Warrior Cut', 'duration_minutes' => 45, 'price' => 350],
            ['name' => 'Overgrown Buzz Cut', 'duration_minutes' => 25, 'price' => 200],
            ['name' => 'Messy Textured Crop', 'duration_minutes' => 35, 'price' => 270],
            ['name' => 'Slick Back Undercut', 'duration_minutes' => 40, 'price' => 300],
            ['name' => 'Curly Flow', 'duration_minutes' => 45, 'price' => 330],
            ['name' => 'Skin Fade', 'duration_minutes' => 30, 'price' => 280],
        ];

        foreach ($services as $service) {
            Service::updateOrCreate(
                ['name' => $service['name']],
                [
                    ...$service,
                    'description' => $this->descriptionFor($service['name']),
                    'image_url' => 'https://placehold.co/800x600/161616/c9a84c?text=' . urlencode($service['name']),
                    'is_active' => true,
                ],
            );
        }
    }

    private function descriptionFor(string $service): string
    {
        return match ($service) {
            'Textured Crop' => 'A clean, low-maintenance crop with controlled movement and a sharp finish.',
            'Textured Quiff' => 'A polished quiff shaped with volume, texture, and natural flow.',
            'Modern Mullet' => 'A balanced modern mullet with tailored sides and confident length through the back.',
            'Blowout Low Taper Fade' => 'A crisp low taper with airy volume and a smooth blended profile.',
            'Warrior Cut' => 'A bold, structured cut built for strong lines and standout shape.',
            'Overgrown Buzz Cut' => 'A softer buzz silhouette with enough length for texture and definition.',
            'Messy Textured Crop' => 'A relaxed crop with broken texture and effortless daily styling.',
            'Slick Back Undercut' => 'A refined undercut with clean separation and a sleek finish.',
            'Curly Flow' => 'A shape-preserving cut that keeps curls defined, light, and natural.',
            'Skin Fade' => 'A precise fade taken tight to the skin with a clean top shape.',
        };
    }
}
