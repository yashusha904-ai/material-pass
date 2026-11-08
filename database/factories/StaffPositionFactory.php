<?php

namespace Database\Factories;

use App\Models\StaffPosition;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<StaffPosition>
 */
class StaffPositionFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => strtoupper(fake()->unique()->bothify('SP-####')),
            'title' => fake()->randomElement([
                'Начальник лаборатории',
                'Инженер',
                'Мастер участка',
                'Водитель',
                'Охранник',
            ]),
            'is_active' => true,
        ];
    }
}
