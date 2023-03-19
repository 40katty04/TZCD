<?php

namespace Database\Factories;

use App\Models\Link;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Link>
 */
class LinkFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'url' => fake()->url(),
            'token' => Link::generateToken(),
            'expires_at' => now()->addMinutes(3),
            'max_clicks' => $max = rand(5,10),
            'clicks' => 0,
        ];
    }

    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'expires_at' => Carbon::now()->addMinutes(-1)->toDateTime(),
        ]);
    }

    public function hasMaxClicks(): static
    {
        return $this->state(fn (array $attributes) => [
            'clicks' => $attributes['max_clicks'],
        ]);
    }

}
