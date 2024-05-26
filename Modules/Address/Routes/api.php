<?php

use Illuminate\Support\Facades\Route;
use Modules\Address\Http\Controllers\APIController;

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

Route::prefix('/{lang}/address')->group(function () {
    Route::post('store', [APIController::class, 'store']);
    Route::post('get', [APIController::class, 'get']);
    Route::post('delete/{id}', [APIController::class, 'delete']);
});
