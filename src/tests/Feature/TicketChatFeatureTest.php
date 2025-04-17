<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Clients;
use App\Models\Ticket;
use App\Models\TicketChat;
use App\Models\TicketAttachment;
use App\Models\Notification;

class TicketChatFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected $client, $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = Clients::create(['name' => 'C']);
        $this->user = User::create([
            'name'      => 'user1',
            'email'     => 'user@user.com',
            'password'  => Hash::make('password'),
            'role'      => 'client',
            'client_id' => $this->client->id
        ]);
    }

    /** @test */
    public function can_reply_with_attachments_and_notifications_created()
    {
        Storage::fake('local');

        // Create initial ticket
        $ticket = Ticket::create([
            'subject'    => 'Help',
            'client_id'  => $this->client->id,
            'created_by' => $this->user->id,
        ]);

        $ticket->replies()->create(['user_id' => $this->user->id, 'message' => 'Hi']);

        $file = UploadedFile::fake()->create('error.log', 100);
        $response = $this->actingAs($this->user)
            ->post(route('tickets.replies.store', $ticket), [
                'message'     => 'Here is log',
                'attachments' => [$file],
            ]);

        $response->assertRedirect(route('tickets.show', $ticket));

        // Reply recorded
        $this->assertDatabaseHas('ticket_chats', ['message' => 'Here is log']);

        $reply = TicketChat::where('message', 'Here is log')->first();

        // Attachment recorded
        $this->assertDatabaseHas('ticket_attachments', [
            'ticket_chat_id' => $reply->id,
        ]);

        // Notification queued for other participants
        $this->assertDatabaseHas('notifications', [
            'ticket_chat_id' => $reply->id,
            'status'          => 'queued',
        ]);
    }
}
