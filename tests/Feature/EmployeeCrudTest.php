<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Person;
use App\Models\StaffPosition;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployeeCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_access_employees_index(): void
    {
        $this->get(route('employees.index'))->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_employees_index(): void
    {
        $user = User::factory()->create();
        $person = Person::factory()->create([
            'last_name' => 'Хромов',
            'first_name' => 'Алексей',
            'middle_name' => null,
        ]);
        Employee::factory()->create(['person_id' => $person->id]);

        $this->actingAs($user)
            ->get(route('employees.index'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Employees/Index')
                ->has('employees.data', 1)
                ->where('employees.data.0.person_name', 'Хромов Алексей'));
    }

    public function test_authenticated_user_can_create_an_employee(): void
    {
        $user = User::factory()->create();
        $person = Person::factory()->create();
        $department = Department::factory()->create();
        $position = StaffPosition::factory()->create();

        $this->actingAs($user)
            ->post(route('employees.store'), [
                'person_id' => $person->id,
                'department_id' => $department->id,
                'staff_position_id' => $position->id,
                'personnel_number' => 'EMP-100',
                'hired_on' => '2024-01-15',
                'fired_on' => null,
                'is_active' => true,
            ])
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('employees.index'));

        $this->assertDatabaseHas('employees', [
            'person_id' => $person->id,
            'department_id' => $department->id,
            'staff_position_id' => $position->id,
            'personnel_number' => 'EMP-100',
        ]);
    }

    public function test_employee_store_requires_person(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->from(route('employees.create'))
            ->post(route('employees.store'), [
                'person_id' => null,
                'is_active' => true,
            ])
            ->assertSessionHasErrors(['person_id'])
            ->assertRedirect(route('employees.create'));
    }

    public function test_authenticated_user_can_update_an_employee(): void
    {
        $user = User::factory()->create();
        $employee = Employee::factory()->create([
            'personnel_number' => 'OLD-1',
        ]);
        $newDepartment = Department::factory()->create();

        $this->actingAs($user)
            ->put(route('employees.update', $employee), [
                'person_id' => $employee->person_id,
                'department_id' => $newDepartment->id,
                'staff_position_id' => $employee->staff_position_id,
                'personnel_number' => 'NEW-2',
                'hired_on' => '2023-05-01',
                'fired_on' => null,
                'is_active' => true,
            ])
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('employees.index'));

        $this->assertDatabaseHas('employees', [
            'id' => $employee->id,
            'department_id' => $newDepartment->id,
            'personnel_number' => 'NEW-2',
        ]);
    }

    public function test_authenticated_user_can_delete_an_employee(): void
    {
        $user = User::factory()->create();
        $employee = Employee::factory()->create();

        $this->actingAs($user)
            ->delete(route('employees.destroy', $employee))
            ->assertRedirect(route('employees.index'));

        $this->assertDatabaseMissing('employees', ['id' => $employee->id]);
    }
}
