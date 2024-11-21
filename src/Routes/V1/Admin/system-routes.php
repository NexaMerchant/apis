<?php

use Illuminate\Support\Facades\Route;

use NexaMerchant\Apis\Http\Controllers\Api\V1\Admin\System\TinyMCEController;

/**
 * System routes.
 */
Route::group([
    'middleware' => ['auth:sanctum', 'sanctum.admin'],
    'prefix'     => 'system',
], function () {
    /**
     * TinyMCE routes.
     */
    Route::controller(TinyMCEController::class)->prefix('tinymce')->group(function () {
        Route::post('upload', 'upload');
    });
});