<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\Releases;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use ZipArchive;

class AdminReleasesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');
    }

    /** Builds a zip shaped like the Pro release. */
    private function zip(string $header, ?string $const = null, string $inner = 'elevenrank-pro/elevenrank-pro.php'): UploadedFile
    {
        $const ??= $header;
        $path = tempnam(sys_get_temp_dir(), 'erp').'.zip';
        $zip = new ZipArchive;
        $zip->open($path, ZipArchive::CREATE | ZipArchive::OVERWRITE);
        $zip->addFromString($inner, "<?php\n/**\n * Plugin Name: ElevenRank Pro\n * Version:           {$header}\n */\ndefine( 'ELEVENRANK_PRO_VERSION', '{$const}' );\n");
        $zip->close();

        return new UploadedFile($path, 'elevenrank-pro.zip', 'application/zip', null, true);
    }

    private function staff(): User
    {
        return User::factory()->create(['is_admin' => true]);
    }

    public function test_guests_are_sent_to_the_login_and_customers_are_refused(): void
    {
        $this->get('/admin/releases')->assertRedirect('/admin/login');
        $this->post('/admin/releases')->assertRedirect('/admin/login');

        $this->actingAs(User::factory()->create())->get('/admin/releases')->assertForbidden();
    }

    public function test_only_staff_can_sign_in(): void
    {
        $staff = $this->staff();
        $customer = User::factory()->create();

        $this->post('/admin/login', ['email' => $customer->email, 'password' => 'password'])->assertSessionHasErrors('email');
        $this->assertGuest();

        $this->post('/admin/login', ['email' => $staff->email, 'password' => 'wrong'])->assertSessionHasErrors('email');
        $this->post('/admin/login', ['email' => $staff->email, 'password' => 'password'])->assertRedirect('/admin/releases');
        $this->assertAuthenticatedAs($staff);

        $this->post('/admin/logout')->assertRedirect('/admin/login');
        $this->assertGuest();
    }

    public function test_publishing_a_valid_zip_updates_what_the_api_serves(): void
    {
        $this->actingAs($this->staff())
            ->post('/admin/releases', ['zip' => $this->zip('0.2.0')])
            ->assertRedirect('/admin/releases');

        $releases = app(Releases::class);
        $this->assertSame('0.2.0', $releases->currentVersion());
        Storage::assertExists('releases/elevenrank-pro.zip');
        Storage::assertExists('releases/elevenrank-pro-0.2.0.zip');
        $this->get('/admin/releases')->assertSee('0.2.0');
    }

    public function test_rejects_files_that_are_not_the_add_on(): void
    {
        $staff = $this->staff();

        $this->actingAs($staff)->post('/admin/releases', ['zip' => UploadedFile::fake()->createWithContent('x.zip', 'not a zip')])->assertSessionHasErrors('zip');
        $this->post('/admin/releases', ['zip' => $this->zip('0.2.0', '0.2.0', 'other-plugin/other.php')])->assertSessionHasErrors('zip');
        $this->post('/admin/releases', ['zip' => $this->zip('0.2.0', '0.3.0')])->assertSessionHasErrors('zip');
        $this->post('/admin/releases', ['zip' => $this->zip('banana')])->assertSessionHasErrors('zip');

        $this->assertSame('', app(Releases::class)->currentVersion(), 'Nothing is published when validation fails.');
    }

    public function test_an_older_version_needs_permission_and_can_be_restored(): void
    {
        $this->actingAs($this->staff());
        $this->post('/admin/releases', ['zip' => $this->zip('0.2.0')]);
        $this->post('/admin/releases', ['zip' => $this->zip('0.3.0')]);

        $this->post('/admin/releases', ['zip' => $this->zip('0.1.0')])->assertSessionHasErrors('zip');
        $releases = app(Releases::class);
        $this->assertSame('0.3.0', $releases->currentVersion());
        $this->assertSame(['0.3.0', '0.2.0'], $releases->history());

        $this->post('/admin/releases/restore', ['version' => '0.2.0'])->assertRedirect('/admin/releases');
        $this->assertSame('0.2.0', $releases->currentVersion());

        $this->post('/admin/releases/restore', ['version' => '9.9.9'])->assertSessionHasErrors('version');
        $this->post('/admin/releases/restore', ['version' => '../../.env'])->assertSessionHasErrors('version');
    }

    public function test_the_create_admin_command_makes_a_staff_account(): void
    {
        $this->artisan('admin:create boss@example.com')
            ->expectsQuestion('Password (at least 12 characters)', 'a-long-enough-pass')
            ->assertSuccessful();

        $this->assertTrue(User::where('email', 'boss@example.com')->firstOrFail()->is_admin);

        $this->artisan('admin:create short@example.com')
            ->expectsQuestion('Password (at least 12 characters)', 'short')
            ->assertFailed();
    }
}
