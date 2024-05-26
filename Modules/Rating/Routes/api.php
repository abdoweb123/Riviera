<?php

use Illuminate\Support\Facades\Route;
use Modules\Rating\Http\Controllers\APIController;

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

Route::prefix('/{lang}/rating')->group(function () {
    Route::group(['as' => 'api.rating.'], function () {
        Route::POST('store', [APIController::class, 'store']);
        Route::get('get', [APIController::class, 'getTotalRating']);
    });
});
