<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TicketAttachment extends Model
{
    use HasFactory;
    protected $fillable = ['ticket_chat_id', 'file_path'];

    public function reply(): BelongsTo
    {
        return $this->belongsTo(TicketChat::class, 'ticket_chat_id');
    }
}
