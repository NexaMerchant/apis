<?php

use Illuminate\Support\Facades\Route;
use NexaMerchant\Apis\Http\Controllers\Web\ExampleController;

Route::group(['middleware' => ['locale', 'theme', 'currency'], 'prefix'=>'Apis'], function () {

    Route::controller(ExampleController::class)->prefix('example')->group(function () {

        Route::get('demo', 'demo')->name('Apis.web.example.demo');

    });

});

include "admin.php";