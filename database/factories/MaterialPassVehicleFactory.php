<?php

namespace Database\Factories;

use App\Models\MaterialPass;
use App\Models\MaterialPassVehicle;
use App\Models\Person;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MaterialPassVehicle>
 */
class MaterialPassVehicleFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'material_pass_id' => MaterialPass::factory(),
            'sort_order' => 0,
            'booking_request' => fake()->optional()->bothify('BR-####'),
            'vehicle_type' => fake()->randomElement(['Грузовой', 'Легковой', 'Спецтехника']),
            'plate_number' => fake()->bothify('?###??##'),
            'driver_person_id' => Person::factory(),
            'driver_name' => null,
            'comment' => fake()->optional()->sentence(),
        ];
    }
}
