<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Activation extends Model
{
    protected $fillable = ['licence_id', 'site'];

    public function licence(): BelongsTo
    {
        return $this->belongsTo(Licence::class);
    }
}
