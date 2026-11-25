<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class AuditLog extends Model
{
    protected $fillable = ['user_id', 'action', 'subject', 'ip', 'reason'];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }

    public static function record(string $action, string $subject = '', ?string $reason = null): void
    {
        self::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'subject' => $subject,
            'ip' => (app()->runningInConsole() && ! app()->runningUnitTests()) ? null : request()->ip(),
            'reason' => $reason,
        ]);
    }
}
