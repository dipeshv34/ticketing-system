<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

class AccessControlTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function unauthenticated_users_get_redirected_to_login()
    {
        $this->get('/tickets')->assertRedirect('/login');
        $this->get('/admin/clients')->assertRedirect('/login');
    }

    /** @test */
    public function wrong_roles_get_403_on_protected_routes()
    {
        $user = User::factory()->create(['role' => 'client']);

        $this->actingAs($user)
            ->get('/admin/clients')
            ->assertStatus(403);
    }
}
