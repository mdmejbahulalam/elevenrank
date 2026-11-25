<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketReply extends Model
{
    protected $fillable = ['ticket_id', 'user_id', 'is_staff', 'body', 'is_note'];

    protected function casts(): array
    {
        return ['is_staff' => 'boolean', 'is_note' => 'boolean'];
    }

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
}
