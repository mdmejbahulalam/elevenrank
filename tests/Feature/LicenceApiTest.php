<?php

namespace Tests\Feature;

use App\Models\Licence;
use App\Services\Releases;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LicenceApiTest extends TestCase
{
    use RefreshDatabase;

    private const BASE = '/wp-json/elevenrank-licence/v1/';

    private function api(string $action, array $data): \Illuminate\Testing\TestResponse
    {
        return $this->postJson(self::BASE.$action, $data);
    }

    public function test_activates_checks_and_deactivates_a_site(): void
    {
        $licence = Licence::issue('a@example.com', 1, now()->addYear());
        $body = ['key' => $licence->license_key, 'site' => 'https://www.Example.com/'];

        $this->api('check', $body)->assertJson(['status' => 'invalid']);
        $this->api('activate', $body)->assertJson(['status' => 'valid', 'message' => 'Licence activated.']);
        $this->api('check', ['key' => $licence->license_key, 'site' => 'http://example.com'])->assertJson(['status' => 'valid']);

        $this->api('deactivate', $body)->assertJson(['status' => 'inactive']);
        $this->api('check', $body)->assertJson(['status' => 'invalid']);
    }

    public function test_enforces_the_site_limit_and_frees_a_seat(): void
    {
        $licence = Licence::issue('a@example.com', 1, null);

        $this->api('activate', ['key' => $licence->license_key, 'site' => 'one.test'])->assertJson(['status' => 'valid']);
        $this->api('activate', ['key' => $licence->license_key, 'site' => 'two.test'])->assertJson(['status' => 'limit']);
        $this->api('deactivate', ['key' => $licence->license_key, 'site' => 'one.test']);
        $this->api('activate', ['key' => $licence->license_key, 'site' => 'two.test'])->assertJson(['status' => 'valid']);
    }

    public function test_reports_unknown_revoked_and_expired_keys(): void
    {
        $this->api('activate', ['key' => 'ER-NOPE', 'site' => 'x.test'])->assertJson(['status' => 'invalid']);

        $revoked = Licence::issue('a@example.com', 1, null);
        $revoked->update(['status' => 'revoked']);
        $this->api('activate', ['key' => $revoked->license_key, 'site' => 'x.test'])->assertJson(['status' => 'revoked']);

        $expired = Licence::issue('a@example.com', 1, now()->subDay());
        $this->api('check', ['key' => $expired->license_key, 'site' => 'x.test'])->assertJson(['status' => 'expired']);
    }

    public function test_update_and_download_need_a_valid_licence_on_an_activated_site(): void
    {
        Storage::fake('local');
        Storage::put(Releases::ZIP, 'zip-bytes');
        Storage::put(Releases::META, '{"version":"0.2.0"}');

        $licence = Licence::issue('a@example.com', 1, now()->addYear());
        $body = ['key' => $licence->license_key, 'site' => 'shop.test'];

        $this->api('update', $body)->assertExactJson([]);

        $this->api('activate', $body);
        $update = $this->api('update', $body)->assertJson(['version' => '0.2.0']);
        $this->assertStringContainsString('/wp-json/elevenrank-licence/v1/download', $update->json('package'));

        $this->get($update->json('package'))->assertOk()->assertHeader('content-type', 'application/zip');
        $this->get(self::BASE.'download?key='.$licence->license_key.'&site=other.test')->assertForbidden();

        $licence->update(['expires' => now()->subDay()]);
        $this->api('update', $body)->assertExactJson([]);
        $this->get($update->json('package'))->assertForbidden();
    }

    public function test_guessing_keys_is_rate_limited(): void
    {
        for ($i = 0; $i < 30; $i++) {
            $this->api('check', ['key' => 'ER-GUESS', 'site' => 'x.test'])->assertOk();
        }

        $this->api('check', ['key' => 'ER-GUESS', 'site' => 'x.test'])->assertStatus(429);
    }
}
