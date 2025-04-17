<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Notification extends Model
{
    use HasFactory;
    protected $fillable = ['ticket_chat_id', 'sent_to', 'status'];

    public function reply(): BelongsTo
    {
        return $this->belongsTo(TicketChat::class, 'ticket_chat_id');
    }
}
