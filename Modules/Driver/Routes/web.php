<?php

use App\Http\Middleware\Localization;
use Illuminate\Support\Facades\Route;
use Modules\Driver\Http\Controllers\Controller;
use Modules\Car\Http\Controllers\Controller as CarController;

Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::group(['middleware' => [Localization::class, 'auth:admin']], function () {
        Route::resources(['drivers' => Controller::class]);
        Route::get('driver/car/{driver}', [CarController::class,'edit'])->name('drivers.car.edit');
    });
});
