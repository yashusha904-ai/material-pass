<?php

namespace Database\Factories;

use App\Enums\MaterialPassStatus;
use App\Models\Department;
use App\Models\Employee;
use App\Models\MaterialPass;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<MaterialPass>
 */
class MaterialPassFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $initiator = Employee::factory();
        $from = Department::factory();
        $to = Department::factory();

        return [
            'number' => fake()->unique()->numberBetween(1, 9999),
            'document_date' => fake()->dateTimeBetween('-1 month', 'now'),
            'status' => MaterialPassStatus::Draft,
            'qr_token' => (string) Str::uuid(),
            'initiator_employee_id' => $initiator,
            'initiator_department_id' => $from,
            'initiator_organization_name' => "АО 'Полюс Красноярск'",
            'from_department_id' => $from,
            'to_department_id' => $to,
            'to_location_name' => null,
            'valid_until' => fake()->dateTimeBetween('+1 day', '+3 months'),
            'seal_numbers' => [
                fake()->numerify('#########'),
                fake()->numerify('#########'),
            ],
            'issued_to_employee_id' => Employee::factory(),
            'issued_to_department_id' => Department::factory(),
            'issued_to_organization_name' => "АО 'Полюс Красноярск'",
            'issued_to_external_organization_id' => null,
            'created_by_user_id' => User::factory(),
        ];
    }

    public function draft(): static
    {
        return $this->state(fn (): array => [
            'status' => MaterialPassStatus::Draft,
        ]);
    }
}
