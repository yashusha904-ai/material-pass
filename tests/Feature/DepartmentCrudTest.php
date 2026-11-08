<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DepartmentCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_access_departments_index(): void
    {
        $this->get(route('departments.index'))->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_departments_index(): void
    {
        $user = User::factory()->create();
        Department::factory()->create(['name' => 'Лаборатория']);

        $this->actingAs($user)
            ->get(route('departments.index'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Departments/Index')
                ->has('departments.data', 1)
                ->where('departments.data.0.name', 'Лаборатория'));
    }

    public function test_authenticated_user_can_create_a_department_with_parent(): void
    {
        $user = User::factory()->create();
        $parent = Department::factory()->create(['name' => 'Головное']);

        $this->actingAs($user)
            ->post(route('departments.store'), [
                'parent_id' => $parent->id,
                'code' => 'DEP-10',
                'name' => 'Дочернее',
                'organization_name' => "АО 'Полюс'",
                'is_active' => true,
            ])
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('departments.index'));

        $this->assertDatabaseHas('departments', [
            'parent_id' => $parent->id,
            'code' => 'DEP-10',
            'name' => 'Дочернее',
            'organization_name' => "АО 'Полюс'",
        ]);
    }

    public function test_department_store_requires_name(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->from(route('departments.create'))
            ->post(route('departments.store'), [
                'name' => '',
                'is_active' => true,
            ])
            ->assertSessionHasErrors(['name'])
            ->assertRedirect(route('departments.create'));
    }

    public function test_authenticated_user_can_update_a_department(): void
    {
        $user = User::factory()->create();
        $department = Department::factory()->create(['name' => 'Старое']);

        $this->actingAs($user)
            ->put(route('departments.update', $department), [
                'parent_id' => null,
                'code' => 'UPD-1',
                'name' => 'Обновлённое',
                'organization_name' => null,
                'is_active' => true,
            ])
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('departments.index'));

        $this->assertDatabaseHas('departments', [
            'id' => $department->id,
            'name' => 'Обновлённое',
            'code' => 'UPD-1',
        ]);
    }

    public function test_department_cannot_be_its_own_parent(): void
    {
        $user = User::factory()->create();
        $department = Department::factory()->create();

        $this->actingAs($user)
            ->from(route('departments.edit', $department))
            ->put(route('departments.update', $department), [
                'parent_id' => $department->id,
                'name' => $department->name,
                'is_active' => true,
            ])
            ->assertSessionHasErrors(['parent_id'])
            ->assertRedirect(route('departments.edit', $department));
    }

    public function test_authenticated_user_can_delete_a_department(): void
    {
        $user = User::factory()->create();
        $department = Department::factory()->create();

        $this->actingAs($user)
            ->delete(route('departments.destroy', $department))
            ->assertRedirect(route('departments.index'));

        $this->assertDatabaseMissing('departments', ['id' => $department->id]);
    }
}
