<?php

namespace Database\Factories;

use App\Models\TicketChat;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Notification;

class NotificationFactory extends Factory
{
    /**
     * The name of the factory’s corresponding model.
     *
     * @var string
     */
    protected $model = Notification::class;

    /**
     * Define the model’s default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            // Link back to a reply (new one by default)
            'ticket_reply_id' => TicketChat::factory(),
            // Random email recipient
            'sent_to'         => $this->faker->safeEmail(),
            // Random status
            'status'          => $this->faker->randomElement(['queued', 'sent', 'failed']),
        ];
    }
}
