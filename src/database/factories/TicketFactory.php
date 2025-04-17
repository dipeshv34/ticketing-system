<?php

namespace Database\Factories;

use App\Models\Clients;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Ticket;
use App\Models\User;

class TicketFactory extends Factory
{
    /**
     * The name of the factory’s corresponding model.
     *
     * @var string
     */
    protected $model = Ticket::class;

    /**
     * Define the model’s default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'subject'    => $this->faker->sentence(4),
            'status'     => $this->faker->randomElement(['open','closed','on_hold']),
            // Create or use an existing client
            'client_id'  => Clients::factory(),
            // Create or use an existing user as creator
            'created_by' => User::factory(),
            // assigned_to is optional
            'assigned_to' => null,
        ];
    }
}
