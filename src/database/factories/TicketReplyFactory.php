<?php

namespace Database\Factories;

use App\Models\TicketChat;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Ticket;
use App\Models\User;

class TicketReplyFactory extends Factory
{
    /**
     * The name of the factory’s corresponding model.
     *
     * @var string
     */
    protected $model = TicketChat::class;

    /**
     * Define the model’s default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            // Create (or reuse) a ticket
            'ticket_id' => Ticket::factory(),
            // Create (or reuse) a user as the replier
            'user_id'   => User::factory(),
            // Random reply message
            'message'   => $this->faker->paragraph(),
        ];
    }
}
