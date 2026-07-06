<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Client>
 */
class ClientFactory extends Factory
{
    public function definition(): array
    {
        return [
            'coach_id' => User::factory()->state(['role' => 'coach']),
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'status' => fake()->randomElement(['active', 'cancelled', 'past_due']),
            'joined_at' => fake()->dateTimeBetween('-2 years', 'now'),
            'last_activity_at' => fake()->optional(0.8)->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
