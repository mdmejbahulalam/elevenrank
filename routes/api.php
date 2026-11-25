<?php

use App\Http\Controllers\LicenceApiController;
use Illuminate\Support\Facades\Route;

// Public on purpose: the licence key is the credential. 30 tries per IP per hour against guessing.
Route::middleware('throttle:30,60')->controller(LicenceApiController::class)->group(function () {
    Route::post('activate', 'activate');
    Route::post('deactivate', 'deactivate');
    Route::post('check', 'check');
    Route::post('update', 'update');
    Route::get('download', 'download')->name('licence.download');
});
