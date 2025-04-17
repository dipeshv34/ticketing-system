<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Clients;

class ClientManagementTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function super_admin_can_create_update_and_deactivate_clients()
    {
        // Create Super Admin
        $super = User::create([
            'name'     => 'SA',
            'email'    => 'sa@example.com',
            'password' => Hash::make('password'),
            'role'     => 'super_admin',
        ]);

        // Create a client
        $this->actingAs($super)
            ->post(route('admin.clients.store'), ['name' => 'Acme Corp', 'active' => true])
            ->assertRedirect(route('admin.clients.index'));

        $this->assertDatabaseHas('clients', ['name' => 'Acme Corp', 'active' => 1]);

        // Update the client
        $client = Clients::first();
        $this->actingAs($super)
            ->patch(route('admin.clients.update', $client), ['name' => 'Acme Ltd', 'active' => false])
            ->assertRedirect(route('admin.clients.index'));

        $this->assertDatabaseHas('clients', ['id' => $client->id, 'name' => 'Acme Ltd', 'active' => 0]);

        // Deactivate via destroy (soft‑deactivate)
        $this->actingAs($super)
            ->delete(route('admin.clients.destroy', $client))
            ->assertRedirect(route('admin.clients.index'));

        $this->assertDatabaseHas('clients', ['id' => $client->id, 'active' => 0]);
    }

    /** @test */
    public function admin_or_client_cannot_manage_clients()
    {
        $clientModel = Clients::create(['name' => 'Test Client']);

        $admin = User::create([
            'name'      => 'Admin',
            'email'     => 'admin@example.com',
            'password'  => Hash::make('password'),
            'role'      => 'admin',
            'client_id' => $clientModel->id,
        ]);

        $user = User::create([
            'name'      => 'Client User',
            'email'     => 'user@example.com',
            'password'  => Hash::make('password'),
            'role'      => 'client',
            'client_id' => $clientModel->id,
        ]);

        // Both should be forbidden
        foreach ([$admin, $user] as $actor) {
            $this->actingAs($actor)
                ->post(route('admin.clients.store'), ['name' => 'X'])
                ->assertStatus(403);
        }
    }
}
