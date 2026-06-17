<?php

namespace Database\Seeders;

use App\Models\StreamingService;
use Illuminate\Database\Seeder;

class StreamingServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            ['name' => 'Netflix',       'slug' => 'netflix',       'color' => '#e50914'],
            ['name' => 'Prime Video',   'slug' => 'prime-video',   'color' => '#00a8e1'],
            ['name' => 'Disney+',       'slug' => 'disney-plus',   'color' => '#0063e5'],
            ['name' => 'Max',           'slug' => 'max',           'color' => '#0046ff'],
            ['name' => 'Apple TV+',     'slug' => 'apple-tv-plus', 'color' => '#000000'],
            ['name' => 'Globoplay',     'slug' => 'globoplay',     'color' => '#ff2e63'],
            ['name' => 'Paramount+',    'slug' => 'paramount-plus','color' => '#0064ff'],
            ['name' => 'Crunchyroll',   'slug' => 'crunchyroll',   'color' => '#f47521'],
        ];

        foreach ($services as $svc) {
            StreamingService::updateOrCreate(
                ['slug' => $svc['slug']],
                $svc,
            );
        }
    }
}
