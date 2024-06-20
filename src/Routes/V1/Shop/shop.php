<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix'     => 'api/v1',
    'middleware' => ['sanctum.locale', 'sanctum.currency','assign_request_id'],
], function () {
    /**
     * Core routes.
     */
    require 'core-routes.php';

    /**
     * Catalog routes.
     */
    require 'catalog-routes.php';

    /**
     * Customer routes.
     */
    require 'customers-routes.php';
});
