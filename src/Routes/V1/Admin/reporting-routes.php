<?php

use Illuminate\Support\Facades\Route;
use Nicelizhi\Manage\Http\Controllers\Reporting\CustomerController;
use Nicelizhi\Manage\Http\Controllers\Reporting\ProductController;
use NexaMerchant\Apis\Http\Controllers\Api\V1\Admin\Reporting\SaleController;

/**
 * Reporting routes.
 */
Route::group([
    'middleware' => ['auth:sanctum', 'sanctum.admin'],
    'prefix'     => 'reporting',
], function () {
    /**
     * Sale routes.
     */
    Route::controller(SaleController::class)->prefix('sales')->group(function () {
        Route::get('stats', 'stats');
    });

    /**
     * Customer routes.
     */
    Route::controller(CustomerController::class)->prefix('customers')->group(function () {
        Route::get('stats', 'stats')->name('admin.reporting.customers.stats');
    });

    /**
     * Product routes.
     */
    Route::controller(ProductController::class)->prefix('products')->group(function () {
        Route::get('stats', 'stats')->name('admin.reporting.products.stats');
    });
});
