<?php

namespace Database\Factories;

use App\Models\Series;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Series>
 */
class SeriesFactory extends Factory
{
    protected $model = Series::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'streaming_service_id' => null,
            'name' => fake()->sentence(3),
            'synopsis' => fake()->paragraph(),
            'year' => fake()->numberBetween(1990, 2025),
            'imdb_id' => null,
            'cover_path' => null,
            'cover_url' => null,
        ];
    }
}
