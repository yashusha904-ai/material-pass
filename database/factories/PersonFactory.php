<?php

namespace Database\Factories;

use App\Models\Person;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Person>
 */
class PersonFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => null,
            'last_name' => fake()->lastName(),
            'first_name' => fake()->firstName(),
            'middle_name' => fake()->optional()->firstName(),
            'email' => fake()->boolean(70) ? fake()->unique()->safeEmail() : null,
            'phone' => fake()->boolean(50) ? fake()->e164PhoneNumber() : null,
            'is_active' => true,
        ];
    }

    public function forUser(?User $user = null): static
    {
        return $this->state(fn (): array => [
            'user_id' => $user?->id ?? User::factory(),
            'email' => $user?->email ?? fake()->unique()->safeEmail(),
        ]);
    }
}
