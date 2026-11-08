<?php

namespace Tests\Feature;

use App\Models\Person;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PersonCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_access_people_index(): void
    {
        $this->get(route('people.index'))->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_people_index(): void
    {
        $user = User::factory()->create();
        Person::factory()->create([
            'last_name' => 'Иванов',
            'first_name' => 'Иван',
            'middle_name' => null,
        ]);

        $this->actingAs($user)
            ->get(route('people.index'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('People/Index')
                ->has('people.data', 1)
                ->where('people.data.0.full_name', 'Иванов Иван'));
    }

    public function test_authenticated_user_can_create_a_person(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('people.store'), [
            'last_name' => 'Петров',
            'first_name' => 'Пётр',
            'middle_name' => 'Петрович',
            'email' => 'petrov@example.com',
            'phone' => '+79001234567',
            'is_active' => true,
        ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('people.index'));

        $this->assertDatabaseHas('people', [
            'last_name' => 'Петров',
            'first_name' => 'Пётр',
            'email' => 'petrov@example.com',
            'is_active' => true,
        ]);
    }

    public function test_person_store_requires_names(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->from(route('people.create'))
            ->post(route('people.store'), [
                'last_name' => '',
                'first_name' => '',
                'is_active' => true,
            ])
            ->assertSessionHasErrors(['last_name', 'first_name'])
            ->assertRedirect(route('people.create'));
    }

    public function test_authenticated_user_can_update_a_person(): void
    {
        $user = User::factory()->create();
        $person = Person::factory()->create([
            'last_name' => 'Сидоров',
            'first_name' => 'Сидор',
        ]);

        $this->actingAs($user)
            ->put(route('people.update', $person), [
                'last_name' => 'Сидорова',
                'first_name' => 'Анна',
                'middle_name' => null,
                'email' => 'sidorova@example.com',
                'phone' => null,
                'is_active' => false,
            ])
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('people.index'));

        $this->assertDatabaseHas('people', [
            'id' => $person->id,
            'last_name' => 'Сидорова',
            'first_name' => 'Анна',
            'email' => 'sidorova@example.com',
            'is_active' => false,
        ]);
    }

    public function test_authenticated_user_can_delete_a_person(): void
    {
        $user = User::factory()->create();
        $person = Person::factory()->create();

        $this->actingAs($user)
            ->delete(route('people.destroy', $person))
            ->assertRedirect(route('people.index'));

        $this->assertDatabaseMissing('people', ['id' => $person->id]);
    }
}
