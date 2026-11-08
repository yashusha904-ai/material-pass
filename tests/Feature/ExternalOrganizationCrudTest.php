<?php

namespace Tests\Feature;

use App\Models\ExternalOrganization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExternalOrganizationCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_access_external_organizations_index(): void
    {
        $this->get(route('external-organizations.index'))->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_external_organizations_index(): void
    {
        $user = User::factory()->create();
        ExternalOrganization::factory()->create(['name' => 'ООО Контрагент']);

        $this->actingAs($user)
            ->get(route('external-organizations.index'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('ExternalOrganizations/Index')
                ->has('organizations.data', 1)
                ->where('organizations.data.0.name', 'ООО Контрагент'));
    }

    public function test_authenticated_user_can_create_an_external_organization(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('external-organizations.store'), [
                'name' => 'ООО Ромашка',
                'short_name' => 'Ромашка',
                'inn' => '1234567890',
                'kpp' => '123456789',
                'address' => 'г. Москва',
                'is_active' => true,
            ])
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('external-organizations.index'));

        $this->assertDatabaseHas('external_organizations', [
            'name' => 'ООО Ромашка',
            'inn' => '1234567890',
            'kpp' => '123456789',
        ]);
    }

    public function test_external_organization_store_requires_name(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->from(route('external-organizations.create'))
            ->post(route('external-organizations.store'), [
                'name' => '',
                'is_active' => true,
            ])
            ->assertSessionHasErrors(['name'])
            ->assertRedirect(route('external-organizations.create'));
    }

    public function test_authenticated_user_can_update_an_external_organization(): void
    {
        $user = User::factory()->create();
        $organization = ExternalOrganization::factory()->create(['name' => 'Старое']);

        $this->actingAs($user)
            ->put(route('external-organizations.update', $organization), [
                'name' => 'Новое имя',
                'short_name' => 'Новое',
                'inn' => '0987654321',
                'kpp' => null,
                'address' => null,
                'is_active' => false,
            ])
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('external-organizations.index'));

        $this->assertDatabaseHas('external_organizations', [
            'id' => $organization->id,
            'name' => 'Новое имя',
            'inn' => '0987654321',
            'is_active' => false,
        ]);
    }

    public function test_authenticated_user_can_delete_an_external_organization(): void
    {
        $user = User::factory()->create();
        $organization = ExternalOrganization::factory()->create();

        $this->actingAs($user)
            ->delete(route('external-organizations.destroy', $organization))
            ->assertRedirect(route('external-organizations.index'));

        $this->assertDatabaseMissing('external_organizations', ['id' => $organization->id]);
    }
}
