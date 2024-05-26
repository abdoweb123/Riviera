<?php

use Illuminate\Support\Facades\Route;
use Modules\Driver\Http\Controllers\APIController;
use Modules\Driver\Http\Controllers\DriverController;

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

Route::prefix('/{lang}')->group(function () {
    Route::group(['prefix' => 'driver', 'as' => 'api.driver.'], function () {
        Route::POST('login', [APIController::class, 'Login']);
        Route::POST('register', [APIController::class, 'register']);
        Route::GET('profile', [APIController::class, 'profile']);
        Route::POST('profile', [APIController::class, 'UpdateProfile']);
        Route::POST('profile-image', [APIController::class, 'UpdateImage']);
        Route::POST('delete-account', [APIController::class, 'DeleteAccount']);
        Route::POST('update-password', [APIController::class, 'UpdatePassword']);
        Route::POST('update-old-password', [APIController::class, 'UpdateOldPassword']);
        Route::POST('check_number', [APIController::class, 'CheckNumber']);
        Route::POST('tokens', [APIController::class, 'DeviceToken']);
        Route::POST('logout', [APIController::class, 'Logout']);

        Route::POST('change-car-status', [DriverController::class, 'changeCarStatus']);
        Route::GET('get-car-status', [DriverController::class, 'getCarStatus']);
        Route::GET('status-ride', [DriverController::class, 'statusRide']);
        Route::GET('get-rides', [DriverController::class, 'getRides']);
        Route::get('get-unassigned-rides', [DriverController::class, 'getWithoutAssignedDrivers']);
        Route::post('assignOrUnlike-ride', [DriverController::class, 'assignOrUnlike']);
        Route::any('get-current-ride', [DriverController::class, 'getCurrentRide']);
        Route::any('get-ride/{id?}', [DriverController::class, 'getRide']);
        Route::any('get-rides-date', [DriverController::class, 'getRidesDate']);
        Route::get('get-future-rides-dates', [DriverController::class, 'getFutureRidesDates']);

    });
    Route::GET('drivers', [DriverController::class, 'index']);
});
