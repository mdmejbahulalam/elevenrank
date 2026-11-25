<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = ['slug', 'name', 'sites', 'price_cents', 'currency', 'months', 'active', 'sort'];

    protected function casts(): array
    {
        return ['active' => 'boolean'];
    }

    public function price(): string
    {
        return ($this->currency === 'USD' ? '$' : $this->currency.' ').number_format($this->price_cents / 100, $this->price_cents % 100 ? 2 : 0);
    }

    public function sitesLabel(): string
    {
        return $this->sites === 0 ? 'Unlimited sites' : $this->sites.($this->sites === 1 ? ' site' : ' sites');
    }
}
