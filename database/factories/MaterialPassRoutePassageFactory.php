<?php

namespace Database\Factories;

use App\Models\MaterialPass;
use App\Models\MaterialPassRoutePassage;
use App\Models\Person;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MaterialPassRoutePassage>
 */
class MaterialPassRoutePassageFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'material_pass_id' => MaterialPass::factory(),
            'sort_order' => 0,
            'checkpoint' => fake()->randomElement(['КПП-1', 'КПП-2', 'КПП-Центральный']),
            'passage_status' => fake()->randomElement(['ожидает', 'пропущен', 'отклонён']),
            'passed_at' => null,
            'security_officer_person_id' => Person::factory(),
            'security_officer_name' => null,
        ];
    }
}
