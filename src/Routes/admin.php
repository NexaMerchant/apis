<?php

use Illuminate\Support\Facades\Route;
use NexaMerchant\Apis\Http\Controllers\Admin\ExampleController;

Route::group(['middleware' => ['admin','admin_option_log'], 'prefix' => config('app.admin_url')], function () {
    Route::prefix('Apis')->group(function () {

        Route::controller(ExampleController::class)->prefix('example')->group(function () {

            Route::get('demo', 'demo')->name('Apis.admin.example.demo');

        });

    });
});