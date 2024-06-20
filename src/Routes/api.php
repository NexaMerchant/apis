<?php

use Illuminate\Support\Facades\Route;
use NexaMerchant\Apis\Http\Controllers\Api\ExampleController;

Route::group(['middleware' => ['api','assign_request_id'], 'prefix' => 'api'], function () {
     Route::prefix('Apis')->group(function () {

        Route::controller(ExampleController::class)->prefix('example')->group(function () {

            Route::get('demo', 'demo')->name('Apis.api.example.demo');

        });

     });
});