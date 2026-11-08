<?php

namespace Database\Factories;

use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Department>
 */
class DepartmentFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'parent_id' => null,
            'code' => strtoupper(fake()->unique()->bothify('DEP-###')),
            'name' => fake()->randomElement([
                'Пробирно-аналитическая лаборатория',
                'Гидрометаллургический участок',
                "БЦ 'Баланс'",
                'Служба безопасности',
            ]),
            'organization_name' => "АО 'Полюс Красноярск'",
            'is_active' => true,
        ];
    }
}
