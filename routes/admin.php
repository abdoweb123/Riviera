<?php

use App\Http\Controllers\Admin\ForgetController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Middleware\Localization;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => [Localization::class]], function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::POST('login', [LoginController::class, 'login']);
    Route::get('forget', [ForgetController::class, 'index'])->name('forget');
    Route::POST('forget', [ForgetController::class, 'forget'])->name('forget');
    Route::get('code/{uuid}', [ForgetController::class, 'code'])->name('code');
    Route::POST('resend', [ForgetController::class, 'resend'])->name('resend');
    Route::POST('verify', [ForgetController::class, 'verify'])->name('verify');
    Route::get('reset/{uuid}', [ForgetController::class, 'reset'])->name('reset');
    Route::POST('update-password/{uuid}', [ForgetController::class, 'update'])->name('update-password');
    Route::group(['middleware' => ['auth:admin']], function () {
        Route::any('/', [HomeController::class, 'home'])->name('home');
        Route::get('get/rides/{type}', [HomeController::class,'getRides'])->name('get_rides');
        Route::any('profile', [ProfileController::class, 'show'])->name('profile.show');
        Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::any('logout', [LoginController::class, 'logout'])->name('logout');
    });
});
