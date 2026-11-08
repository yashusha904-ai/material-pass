<?php

namespace Database\Factories;

use App\Models\MaterialPass;
use App\Models\MaterialPassComment;
use App\Models\Person;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MaterialPassComment>
 */
class MaterialPassCommentFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'material_pass_id' => MaterialPass::factory(),
            'commented_at' => now(),
            'role' => fake()->randomElement(['Инициатор', 'Согласующий', 'Охрана']),
            'body' => fake()->sentence(),
            'author_person_id' => Person::factory(),
            'author_user_id' => null,
        ];
    }
}
