<?php

namespace Database\Factories;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Person;
use App\Models\StaffPosition;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'person_id' => Person::factory(),
            'department_id' => Department::factory(),
            'staff_position_id' => StaffPosition::factory(),
            'personnel_number' => fake()->unique()->numerify('EMP-#####'),
            'hired_on' => fake()->dateTimeBetween('-5 years', '-1 month'),
            'fired_on' => null,
            'is_active' => true,
        ];
    }
}
