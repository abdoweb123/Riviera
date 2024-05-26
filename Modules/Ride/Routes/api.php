<?php

use Illuminate\Support\Facades\Route;
use Modules\Ride\Http\Controllers\APIController;
use Modules\Ride\Http\Controllers\ConfirmController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('/{lang}/ride')->group(function () {
    Route::post('store', [APIController::class, 'store']);
    Route::post('confirm', [ConfirmController::class, 'confirm']);
    Route::get('get-rides', [APIController::class, 'getRides']);
    Route::get('get-details', [APIController::class, 'getRideDetails']);
    Route::get('route', [APIController::class, 'getRideRoute']);
    Route::get('driver-details', [APIController::class, 'driverDetails']);
    Route::any('get-driver-location', [APIController::class, 'getDriverLocation']);
    Route::any('cancellation-reasons ', [APIController::class, 'cancellationReasons']);
    Route::post('cancellation-reason ', [APIController::class, 'cancellationReason']);

});
