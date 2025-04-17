<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Clients;
use App\Models\Ticket;

class TicketFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected $clientModel, $admin, $clientUser;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed a client and two users
        $this->clientModel = Clients::create(['name' => 'TestClient']);
        $this->admin = User::create([
            'name'      => 'Admin',
            'email'     => 'admin@tc.com',
            'password'  => Hash::make('password'),
            'role'      => 'admin',
            'client_id' => $this->clientModel->id
        ]);
        $this->clientUser = User::create([
            'name'      => 'Client',
            'email'     => 'client@tc.com',
            'password'  => Hash::make('password'),
            'role'      => 'client',
            'client_id' => $this->clientModel->id
        ]);
    }

    /** @test */
    public function client_can_create_and_view_own_tickets()
    {
        $this->actingAs($this->clientUser)
            ->post(route('tickets.store'), [
                'subject' => 'Need Help',
                'message' => 'Please assist me.'
            ])
            ->assertRedirect();

        $ticket = Ticket::first();
        $this->assertEquals('Need Help', $ticket->subject);
        $this->assertDatabaseHas('ticket_chats', [
            'ticket_id' => $ticket->id,
            'message'   => 'Please assist me.'
        ]);

        // Can view it
        $this->actingAs($this->clientUser)
            ->get(route('tickets.show', $ticket))
            ->assertStatus(200)
            ->assertSeeText('Need Help');
    }

    /** @test */
    public function admin_can_view_and_update_ticket_status()
    {
        // Create a ticket by client
        $ticket = Ticket::create([
            'subject'    => 'Issue',
            'client_id'  => $this->clientModel->id,
            'created_by' => $this->clientUser->id
        ]);

        // Admin lists tickets
        $this->actingAs($this->admin)
            ->get(route('tickets.index'))
            ->assertStatus(200)
            ->assertSeeText('Issue');

        // Admin can update status
        $this->actingAs($this->admin)
            ->patch(route('tickets.update', $ticket), ['status' => 'closed'])
            ->assertRedirect();

        $this->assertDatabaseHas('tickets', ['id' => $ticket->id, 'status' => 'closed']);
    }

    /** @test */
    public function tickets_can_be_filtered_by_status()
    {
        Ticket::create([
            'subject'    => 'One',
            'status'     => 'open',
            'client_id'  => $this->clientModel->id,
            'created_by' => $this->clientUser->id
        ]);
        Ticket::create([
            'subject'    => 'Two',
            'status'     => 'closed',
            'client_id'  => $this->clientModel->id,
            'created_by' => $this->clientUser->id
        ]);

        $this->actingAs($this->clientUser)
            ->get(route('tickets.index', ['status' => 'closed']))
            ->assertSeeText('Two')
            ->assertDontSeeText('One');
    }

    /** @test */
    public function users_cannot_access_other_clients_tickets()
    {
        // Another client + ticket
        $other = Clients::create(['name' => 'Other']);
        $otherTicket = Ticket::create([
            'subject'    => 'OtherIssue',
            'client_id'  => $other->id,
            'created_by' => $this->clientUser->id
        ]);

        // clientUser should get 404
        $this->actingAs($this->clientUser)
            ->get(route('tickets.show', $otherTicket))
            ->assertStatus(404);
    }
}
