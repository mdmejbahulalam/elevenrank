<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    protected $fillable = ['number', 'user_id', 'plan_id', 'amount_cents', 'currency', 'status', 'provider', 'provider_ref', 'licence_id', 'paid_at', 'refunded_at', 'note'];

    protected function casts(): array
    {
        return ['paid_at' => 'datetime', 'refunded_at' => 'datetime'];
    }

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function plan(): BelongsTo { return $this->belongsTo(Plan::class); }
    public function licence(): BelongsTo { return $this->belongsTo(Licence::class); }

    public static function nextNumber(): string
    {
        do { $n = 'ER-'.now()->format('ym').'-'.strtoupper(bin2hex(random_bytes(3))); } while (self::where('number', $n)->exists());

        return $n;
    }

    public function amount(): string
    {
        return ($this->currency === 'USD' ? '$' : $this->currency.' ').number_format($this->amount_cents / 100, 2);
    }
}
