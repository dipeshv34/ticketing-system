<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Clients;
use App\Models\Ticket;

class DashboardFeatureTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function super_admin_dashboard_shows_metrics()
    {
        // Prepare data
        $super = User::create([
            'name'     => 'Deep',
            'email'    => 'Deep@test.com',
            'password' => Hash::make('password'),
            'role'     => 'super_admin',
        ]);

        $c1 = Clients::create(['name' => 'C1']);
        $c2 = Clients::create(['name' => 'C2']);

        Ticket::factory()->count(3)->create(['client_id' => $c1->id, 'status' => 'open']);
        Ticket::factory()->count(2)->create(['client_id' => $c2->id, 'status' => 'closed']);

        $response = $this->actingAs($super)->get(route('tickets.index'));

        $response->assertStatus(200);
        $response->assertSeeText('Total Tickets: 5');
        $response->assertSeeText('Open: 3');
        $response->assertSeeText('Closed: 2');
    }
}
