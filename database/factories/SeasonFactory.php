<?php

namespace Database\Factories;

use App\Models\Season;
use App\Models\Series;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Season>
 */
class SeasonFactory extends Factory
{
    protected $model = Season::class;

    public function definition(): array
    {
        return [
            'series_id' => Series::factory(),
            'number' => 1,
        ];
    }
}
