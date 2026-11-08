<?php

namespace Database\Factories;

use App\Models\MaterialPass;
use App\Models\MaterialPassItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MaterialPassItem>
 */
class MaterialPassItemFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'material_pass_id' => MaterialPass::factory(),
            'sort_order' => 0,
            'category' => fake()->randomElement(['Оборудование', 'Реагенты', 'Пробы', 'Прочее']),
            'name' => fake()->words(3, true),
            'owner_external_organization_id' => null,
            'owner_organization_name' => "АО 'Полюс Красноярск'",
            'quantity' => fake()->randomFloat(3, 1, 100),
            'packaging_type' => fake()->randomElement(['Ящик', 'Мешок', 'Контейнер', 'Без упаковки']),
            'total_weight' => fake()->randomFloat(3, 0.5, 500),
            'unit' => fake()->randomElement(['шт', 'кг', 'т', 'л']),
            'dump_number' => fake()->optional()->bothify('ОТВ-##'),
        ];
    }
}
