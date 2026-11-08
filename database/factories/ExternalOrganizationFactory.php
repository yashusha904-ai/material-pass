<?php

namespace Database\Factories;

use App\Models\ExternalOrganization;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ExternalOrganization>
 */
class ExternalOrganizationFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->company();

        return [
            'name' => $name,
            'short_name' => fake()->optional()->lexify('???'),
            'inn' => fake()->numerify('############'),
            'kpp' => fake()->numerify('#########'),
            'address' => fake()->optional()->address(),
            'is_active' => true,
        ];
    }
}
