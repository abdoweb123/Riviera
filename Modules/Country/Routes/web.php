<?php

use App\Http\Middleware\Localization;
use Illuminate\Support\Facades\Route;
use Modules\Country\Http\Controllers\CityController;
use Modules\Country\Http\Controllers\CountryController;
use Modules\Country\Http\Controllers\RegionController;

Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::group(['middleware' => [Localization::class]], function () {
        Route::resource('countries', CountryController::class);
        Route::resource('country.regions', RegionController::class);
        Route::resource('country.region.cities', CityController::class);
    });
});
