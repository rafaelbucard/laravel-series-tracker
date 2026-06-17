<?php

namespace Database\Factories;

use App\Models\Topic;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Topic>
 */
class TopicFactory extends Factory
{
    protected $model = Topic::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'series_id' => null,
            'title' => fake()->sentence(6),
            'body' => fake()->paragraphs(3, true),
        ];
    }
}
