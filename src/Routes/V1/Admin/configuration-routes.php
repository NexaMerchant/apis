<?php

use Illuminate\Support\Facades\Route;
use NexaMerchant\Apis\Http\Controllers\Api\V1\Admin\Configuration\ConfigurationController;

Route::group(['middleware' => ['auth:sanctum', 'sanctum.admin']], function () {
    /**
     * Configuration routes.
     */
    Route::controller(ConfigurationController::class)->prefix('configuration')->group(function () {
        Route::get('', 'index');

        Route::post('', 'store');
    });
});
