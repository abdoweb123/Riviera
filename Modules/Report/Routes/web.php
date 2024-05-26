<?php

use App\Http\Middleware\Localization;
use Illuminate\Support\Facades\Route;
use Modules\Report\Http\Controllers\ReportController;

Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::group(['middleware' => [Localization::class, 'auth:admin']], function () {
        Route::get('reports/sales', [ReportController::class, 'sales'])->name('reports.sales');
        Route::get('reports/financial', [ReportController::class, 'financial'])->name('reports.financial');
        Route::get('reports/client', [ReportController::class, 'client'])->name('reports.client');
        Route::get('reports/payment', [ReportController::class, 'payment'])->name('reports.payment');
        Route::get('reports/products', [ReportController::class, 'products'])->name('reports.products');
        Route::get('reports/mostselling', [ReportController::class, 'mostselling'])->name('reports.mostselling');
        Route::get('reports/vat', [ReportController::class, 'vat'])->name('reports.vat');
        Route::Get('exportData', [ReportController::class, 'exportData'])->name('exportData');
    });
});
