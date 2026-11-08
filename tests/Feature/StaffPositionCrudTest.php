<?php

namespace Tests\Feature;

use App\Models\StaffPosition;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StaffPositionCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_access_staff_positions_index(): void
    {
        $this->get(route('staff-positions.index'))->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_staff_positions_index(): void
    {
        $user = User::factory()->create();
        StaffPosition::factory()->create(['title' => 'Инженер']);

        $this->actingAs($user)
            ->get(route('staff-positions.index'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('StaffPositions/Index')
                ->has('staffPositions.data', 1)
                ->where('staffPositions.data.0.title', 'Инженер'));
    }

    public function test_authenticated_user_can_create_a_staff_position(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('staff-positions.store'), [
                'code' => 'ENG-01',
                'title' => 'Инженер-технолог',
                'is_active' => true,
            ])
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('staff-positions.index'));

        $this->assertDatabaseHas('staff_positions', [
            'code' => 'ENG-01',
            'title' => 'Инженер-технолог',
        ]);
    }

    public function test_staff_position_store_requires_title(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->from(route('staff-positions.create'))
            ->post(route('staff-positions.store'), [
                'title' => '',
                'is_active' => true,
            ])
            ->assertSessionHasErrors(['title'])
            ->assertRedirect(route('staff-positions.create'));
    }

    public function test_authenticated_user_can_update_a_staff_position(): void
    {
        $user = User::factory()->create();
        $position = StaffPosition::factory()->create(['title' => 'Старое']);

        $this->actingAs($user)
            ->put(route('staff-positions.update', $position), [
                'code' => 'NEW-01',
                'title' => 'Новое название',
                'is_active' => false,
            ])
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('staff-positions.index'));

        $this->assertDatabaseHas('staff_positions', [
            'id' => $position->id,
            'code' => 'NEW-01',
            'title' => 'Новое название',
            'is_active' => false,
        ]);
    }

    public function test_authenticated_user_can_delete_a_staff_position(): void
    {
        $user = User::factory()->create();
        $position = StaffPosition::factory()->create();

        $this->actingAs($user)
            ->delete(route('staff-positions.destroy', $position))
            ->assertRedirect(route('staff-positions.index'));

        $this->assertDatabaseMissing('staff_positions', ['id' => $position->id]);
    }
}
