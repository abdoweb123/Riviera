<?php

use Illuminate\Support\Facades\Route;
use Modules\Client\Http\Controllers\APIController;
use Modules\Client\Http\Controllers\DriverController;

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
    Route::group(['prefix' => 'client', 'as' => 'api.client.'], function () {
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

    });
    Route::GET('clients', [DriverController::class, 'index']);
});
