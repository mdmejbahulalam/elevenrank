<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ReleaseController;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin')->group(function () {
    Route::get('login', [AuthController::class, 'show'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->middleware('throttle:10,1');

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('/', fn () => redirect('/admin/releases'));
        Route::get('releases', [ReleaseController::class, 'index']);
        Route::post('releases', [ReleaseController::class, 'store']);
        Route::post('releases/restore', [ReleaseController::class, 'restore']);
    });
});
