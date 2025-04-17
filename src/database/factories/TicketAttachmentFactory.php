<?php

namespace Database\Factories;

use App\Models\TicketChat;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\TicketAttachment;

class TicketAttachmentFactory extends Factory
{
    /**
     * The name of the factory’s corresponding model.
     *
     * @var string
     */
    protected $model = TicketAttachment::class;

    /**
     * Define the model’s default state.
     *
     * @return array
     */
    public function definition()
    {
        // Generate a pseudo‑random filename with extension
        $filename = $this->faker->uuid() . '.' . $this->faker->fileExtension;

        return [
            // Attach to a reply (new one by default)
            'ticket_reply_id' => TicketChat::factory(),
            // Fake file path, adjust disk/storage as needed
            'file_path'       => 'ticket_attachments/' . $filename,
        ];
    }
}
