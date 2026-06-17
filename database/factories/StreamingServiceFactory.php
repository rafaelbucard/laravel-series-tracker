<?php

namespace Database\Factories;

use App\Models\StreamingService;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StreamingService>
 */
class StreamingServiceFactory extends Factory
{
    protected $model = StreamingService::class;

    public function definition(): array
    {
        $name = fake()->unique()->company();

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'color' => fake()->hexColor(),
            'logo_path' => null,
        ];
    }
}
