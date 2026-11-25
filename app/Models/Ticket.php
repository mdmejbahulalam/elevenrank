<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket extends Model
{
    protected $fillable = ['user_id', 'subject', 'status', 'assigned_to'];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function replies(): HasMany { return $this->hasMany(TicketReply::class)->orderBy('id'); }
    public function assignee(): BelongsTo { return $this->belongsTo(User::class, 'assigned_to'); }
    /** What the customer may see: internal notes are left out. */
    public function publicReplies(): HasMany { return $this->hasMany(TicketReply::class)->where('is_note', false)->orderBy('id'); }
}
