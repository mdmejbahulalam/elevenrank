<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Licence extends Model
{
    protected $fillable = ['license_key', 'email', 'site_limit', 'expires', 'status', 'order_ref', 'user_id', 'plan_id'];

    protected function casts(): array
    {
        return ['expires' => 'datetime', 'site_limit' => 'integer'];
    }

    public function activations(): HasMany
    {
        return $this->hasMany(Activation::class);
    }

    /** Makes a key such as ER-4F9A-12BC-77D0-A1E3. */
    public static function makeKey(): string
    {
        return 'ER-'.strtoupper(implode('-', str_split(bin2hex(random_bytes(8)), 4)));
    }

    public static function issue(string $email, int $siteLimit, ?\DateTimeInterface $expires, string $orderRef = ''): self
    {
        return self::create([
            'license_key' => self::makeKey(),
            'email' => $email,
            'site_limit' => $siteLimit,
            'expires' => $expires,
            'status' => 'active',
            'order_ref' => $orderRef,
        ]);
    }

    public function isExpired(): bool
    {
        return $this->expires !== null && $this->expires->isPast();
    }

    /** @return string[] Sites the licence is active on. */
    public function sites(): array
    {
        return $this->activations()->pluck('site')->all();
    }

    /** Marks a site as active. False when the licence is full. */
    public function activateSite(string $site): bool
    {
        $sites = $this->sites();

        if (in_array($site, $sites, true)) {
            return true;
        }

        if ($this->site_limit > 0 && count($sites) >= $this->site_limit) {
            return false;
        }

        $this->activations()->create(['site' => $site]);

        return true;
    }

    public function deactivateSite(string $site): void
    {
        $this->activations()->where('site', $site)->delete();
    }

    /** http://www.Example.com/ and https://example.com are one site. */
    public static function normaliseSite(string $url): string
    {
        $url = strtolower(trim($url));

        return rtrim((string) preg_replace('#^https?://(www\.)?#', '', $url), '/');
    }
}
