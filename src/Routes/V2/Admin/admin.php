<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix'     => 'api/v2/admin',
    'middleware' => ['sanctum.locale','assign_request_id'],
], function () {
    /**
     * Authentication routes.
     */
    require 'auth-routes.php';
});
