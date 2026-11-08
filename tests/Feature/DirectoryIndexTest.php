<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DirectoryIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_view_directories_hub(): void
    {
        $this->get(route('directories.index'))
            ->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_directories_hub(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('directories.index'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Directories/Index')
                ->has('directories', 5)
                ->where('directories.0.name', 'Физические лица')
                ->where('directories.0.route', 'people.index')
                ->where('directories.4.route', 'external-organizations.index'));
    }
}
