<?php

use App\Http\Controllers\Account\AuthController as AccountAuth;
use App\Http\Controllers\Account\DashboardController as Account;
use App\Http\Controllers\Account\PasswordResetController as PasswordReset;
use App\Http\Controllers\Account\SelfServiceController as SelfService;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\LicenceController;
use App\Http\Controllers\Admin\ReleaseController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\TwoFactorController as TwoFactor;
use App\Http\Controllers\LemonSqueezyWebhookController;
use App\Http\Controllers\SeoFilesController;
use App\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Route;

// Marketing site
Route::view('/', 'site.home');
Route::view('features', 'site.features');
Route::view('pro', 'site.pro');
Route::get('pricing', [SiteController::class, 'pricing']);
Route::view('docs', 'site.docs');
Route::view('docs/hooks', 'site.hooks');
Route::view('changelog', 'site.changelog');
Route::view('support', 'site.support');
Route::view('about', 'site.about');
Route::view('compare/yoast-seo', 'site.compare');
Route::get('blog', fn () => view('site.blog', ['posts' => config('blog.posts')]));
Route::get('blog/{slug}', function (string $slug) {
	$post = collect(config('blog.posts'))->firstWhere('slug', $slug);
	abort_unless($post, 404);

	return view('site.blog-post', ['post' => $post, 'related' => collect(config('blog.posts'))->where('slug', '!=', $slug)->take(3)]);
});
Route::get('robots.txt', [SeoFilesController::class, 'robots']);
Route::get('sitemap.xml', [SeoFilesController::class, 'sitemap']);
Route::get('llms.txt', [SeoFilesController::class, 'llms']);
Route::get('terms', [SiteController::class, 'terms']);
Route::get('privacy', [SiteController::class, 'privacy']);
Route::get('refunds', [SiteController::class, 'refunds']);
Route::get('gpl', [SiteController::class, 'gpl']);

// Payment provider webhooks (inactive until configured)
Route::post('webhooks/lemonsqueezy', LemonSqueezyWebhookController::class)->middleware('throttle:60,1');

// Customer account
Route::prefix('account')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', [AccountAuth::class, 'login'])->name('account.login');
        Route::post('login', [AccountAuth::class, 'authenticate'])->middleware('throttle:10,1');
        Route::get('register', [AccountAuth::class, 'register']);
        Route::post('register', [AccountAuth::class, 'store'])->middleware('throttle:10,1');
        Route::get('forgot-password', [PasswordReset::class, 'request']);
        Route::post('forgot-password', [PasswordReset::class, 'send'])->middleware('throttle:5,1');
        Route::get('reset-password/{token}', [PasswordReset::class, 'form'])->name('password.reset');
        Route::post('reset-password', [PasswordReset::class, 'reset'])->middleware('throttle:10,1');
    });

    Route::middleware('auth')->group(function () {
        Route::post('logout', [AccountAuth::class, 'logout']);
        Route::get('/', [Account::class, 'overview']);
        Route::get('licences', [Account::class, 'licences']);
        Route::post('licences/{licence}/deactivate', [Account::class, 'deactivateSite']);
        Route::get('downloads', [Account::class, 'downloads']);
        Route::get('download', [Account::class, 'download'])->middleware('throttle:20,1');
        Route::get('orders', [Account::class, 'orders']);
        Route::get('orders/{order:number}', [Account::class, 'order']);
        Route::get('orders/{order:number}/invoice', [SelfService::class, 'invoice']);
        Route::post('orders/{order:number}/refund', [SelfService::class, 'requestRefund']);
        Route::post('licences/{licence}/label', [SelfService::class, 'renameLicence']);
        Route::get('export', [SelfService::class, 'export'])->middleware('throttle:5,1');
        Route::get('tickets', [Account::class, 'tickets']);
        Route::post('tickets', [Account::class, 'storeTicket']);
        Route::get('tickets/{ticket}', [Account::class, 'ticket']);
        Route::post('tickets/{ticket}/reply', [Account::class, 'replyTicket']);
        Route::get('profile', [Account::class, 'profile']);
        Route::post('profile', [Account::class, 'updateProfile']);
    });
});

Route::middleware('auth')->group(function () {
    Route::get('checkout/{plan:slug}', [Account::class, 'checkout']);
    Route::post('checkout/{plan:slug}', [Account::class, 'placeOrder']);
});

// Staff area
Route::prefix('admin')->group(function () {
    Route::get('login', [AuthController::class, 'show'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->middleware('throttle:10,1');

    Route::get('two-factor', [TwoFactor::class, 'challenge']);
    Route::post('two-factor', [TwoFactor::class, 'verify'])->middleware('throttle:6,1');

    Route::middleware(['auth', 'admin', 'staff2fa'])->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('two-factor/setup', [TwoFactor::class, 'setup']);
        Route::post('two-factor/setup', [TwoFactor::class, 'confirm'])->middleware('throttle:10,1');
        Route::get('/', [StaffController::class, 'overview'])->middleware('can:staff.overview');
        Route::get('search', [StaffController::class, 'search'])->middleware('can:staff.search');
        Route::middleware('can:staff.customers')->group(function () {
            Route::get('customers', [StaffController::class, 'customers']);
            Route::get('customers/{user}', [StaffController::class, 'customer']);
        });
        Route::get('orders', [StaffController::class, 'orders'])->middleware('can:staff.orders.view');
        Route::get('orders.csv', [StaffController::class, 'ordersCsv'])->middleware('can:staff.orders.view');
        Route::post('orders/{order}', [StaffController::class, 'orderAction']); // each action is authorised inside
        Route::middleware('can:staff.plans')->group(function () {
            Route::get('plans', [StaffController::class, 'plans']);
            Route::post('plans/{plan}', [StaffController::class, 'updatePlan']);
        });
        Route::middleware('can:staff.tickets')->group(function () {
            Route::get('tickets', [StaffController::class, 'tickets']);
            Route::get('tickets/{ticket}', [StaffController::class, 'ticket']);
            Route::post('tickets/{ticket}/reply', [StaffController::class, 'replyTicket']);
        });
        Route::get('audit', [StaffController::class, 'audit'])->middleware('can:staff.audit');
        Route::middleware('can:staff.system')->group(function () {
            Route::get('system', [StaffController::class, 'system']);
            Route::post('system', [StaffController::class, 'runSystem']);
        });

        Route::middleware('can:staff.licences')->group(function () {
            Route::get('licences', [LicenceController::class, 'index']);
            Route::post('licences', [LicenceController::class, 'store']);
            Route::post('licences/{licence}', [LicenceController::class, 'update']);
            Route::post('licences/{licence}/status', [LicenceController::class, 'setStatus']);
        });
        Route::middleware('can:staff.releases')->group(function () {
            Route::get('releases', [ReleaseController::class, 'index']);
            Route::post('releases', [ReleaseController::class, 'store']);
            Route::post('releases/restore', [ReleaseController::class, 'restore']);
        });
    });
});
