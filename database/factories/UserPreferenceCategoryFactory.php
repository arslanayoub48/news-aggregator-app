<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserPreferenceCategory>
 */
class UserPreferenceCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'uuid' => Str::uuid(),
            'user_id' => \App\Models\User::factory(),
            'category' => $this->faker->randomElement(['Technology', 'Health', 'Sports', 'Finance']),
        ];
    }
}