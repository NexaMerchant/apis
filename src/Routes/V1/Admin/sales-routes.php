<?php

use Illuminate\Support\Facades\Route;
use NexaMerchant\Apis\Http\Controllers\Api\V1\Admin\Sales\InvoiceController;
use NexaMerchant\Apis\Http\Controllers\Api\V1\Admin\Sales\OrderController;
use NexaMerchant\Apis\Http\Controllers\Api\V1\Admin\Sales\RefundController;
use NexaMerchant\Apis\Http\Controllers\Api\V1\Admin\Sales\ShipmentController;
use NexaMerchant\Apis\Http\Controllers\Api\V1\Admin\Sales\TransactionController;

Route::group([
    'middleware' => ['auth:sanctum', 'sanctum.admin'],
    'prefix'     => 'sales',
], function () {
    /**
     * Order routes.
     */
    Route::controller(OrderController::class)->prefix('orders')->group(function () {
        Route::get('', 'allResources');

        Route::get('{id}', 'getResource');

        Route::post('{id}/cancel', 'cancel');

        Route::post('{id}/comments', 'comment');

    });

    /**
     * Shipment routes.
     */
    Route::controller(ShipmentController::class)->prefix('shipments')->group(function () {
        Route::get('', 'allResources');

        Route::get('{id}', 'getResource');

        Route::post('{order_id}', 'store');

    });

    /**
     * Invoice routes.
     */
    Route::controller(InvoiceController::class)->prefix('invoices')->group(function () {
        Route::get('', 'allResources');

        Route::get('{id}', 'getResource');

        Route::post('{order_id}', 'store');
    });

    /**
     * Refund routes.
     */
    Route::controller(RefundController::class)->prefix('refunds')->group(function () {
        Route::get('', 'allResources');

        Route::get('{id}', 'getResource');

        Route::post('{order_id}', 'store');

    });

    /**
     * Transaction routes.
     */
    Route::controller(TransactionController::class)->prefix('transactions')->group(function () {
        Route::get('', 'allResources');

        Route::get('{id}', 'getResource');

        Route::post('', 'store');
    });
});
