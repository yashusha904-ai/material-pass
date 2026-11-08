<?php

namespace Tests\Feature;

use App\Models\MaterialPass;
use App\Models\User;
use Database\Seeders\DomainSmokeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MaterialPassPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_view_material_pass_pages(): void
    {
        $this->get(route('material-passes.create'))->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_open_draft_scaffold(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('material-passes.create'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('MaterialPasses/Show')
                ->where('materialPass.status', 'draft')
                ->where('materialPass.id', null));
    }

    public function test_authenticated_user_can_view_seeded_material_pass(): void
    {
        $this->seed(DomainSmokeSeeder::class);

        $user = User::query()->where('email', 'test@example.com')->firstOrFail();
        $pass = MaterialPass::query()->where('number', 1)->firstOrFail();

        $this->actingAs($user)
            ->get(route('material-passes.show', $pass))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('MaterialPasses/Show')
                ->where('materialPass.number', 1)
                ->has('materialPass.vehicles', 1)
                ->has('materialPass.items', 2)
                ->has('materialPass.approvals', 2)
                ->has('materialPass.route_passages', 1)
                ->has('materialPass.comments', 1));
    }
}
