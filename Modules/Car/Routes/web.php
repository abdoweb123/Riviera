<?php

use App\Http\Middleware\Localization;
use Illuminate\Support\Facades\Route;
use Modules\Car\Http\Controllers\Controller;
use Modules\Car\Http\Controllers\CarTypeController;

Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::group(['middleware' => [Localization::class, 'auth:admin']], function () {
        Route::resources(['cars' => Controller::class]);
        Route::resources(['cars-types' => CarTypeController::class]);
    });
});
