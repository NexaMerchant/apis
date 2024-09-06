<?php

use Illuminate\Support\Facades\Route;
use NexaMerchant\Apis\Http\Controllers\Api\V2\Admin\User\AccountController;
use NexaMerchant\Apis\Http\Controllers\Api\V2\Admin\User\AuthController;
use NexaMerchant\Apis\Http\Controllers\Api\V2\Admin\User\RoleController;
use NexaMerchant\Apis\Http\Controllers\Api\V2\Admin\User\PermissionController;
use NexaMerchant\Apis\Http\Controllers\Api\V2\Admin\User\AdminController;


Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');

    Route::post('forgot-password', 'forgotPassword');
});

Route::group(['middleware' => ['auth:sanctum', 'sanctum.admin']], function () {
    Route::controller(AuthController::class)->group(function () {
        Route::delete('logout', 'logout');
    });

    Route::controller(AccountController::class)->group(function () {
        Route::get('get', 'get');

        Route::put('update', 'update');
    });

    // menu

    Route::post('logout', [AdminController::class, 'logout']);
    Route::post('refresh', [AdminController::class, 'refresh']);

    Route::get('get-user-info', [AdminController::class, 'admin']);
    Route::get('get-menu', [AdminController::class, 'menu']);

    // role
    Route::get('/role/index', [RoleController::class, 'index']);
    Route::put('/role/update', [RoleController::class, 'update']);
    Route::put('/role/set-status', [RoleController::class, 'setStatus']);
    Route::post('/role/create', [RoleController::class, 'create']);
    Route::delete('/role/delete', [RoleController::class, 'delete']);
    Route::get('/role/get-roles', [RoleController::class, 'getRoles']);
    // permission
    Route::get('/permission/index', [PermissionController::class, 'index']);
    Route::put('/permission/update', [PermissionController::class, 'update']);
    Route::put('/permission/set-status', [PermissionController::class, 'setStatus']);
    Route::post('/permission/create', [PermissionController::class, 'create']);
    Route::delete('/permission/delete', [PermissionController::class, 'delete']);
    Route::get('/permission/get-tree', [PermissionController::class, 'getTree']); // 获取权限树

    // user
    Route::get('/user/index', [AdminController::class, 'index']);
    Route::put('/user/update', [AdminController::class, 'update']);
    Route::put('/user/set-status', [AdminController::class, 'setStatus']);
    Route::post('/user/create', [AdminController::class, 'create']);
    Route::delete('/user/delete', [AdminController::class, 'delete']);


});