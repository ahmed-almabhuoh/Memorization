<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::prefix('auto')->group(function () {
    Route::post('login', [\App\Http\Controllers\api\AuthenticationController::class, 'login']);
});

Route::prefix('auto')->middleware('auth:sanctum')->group(function () {

    // Resources
    Route::prefix('/')->group(function () {
        Route::resource('admins', \App\Http\Controllers\api\AdminController::class);
    });

    // Blocks
    // Blocks
    Route::prefix('blocks')->group(function () {
        Route::get('/', [\App\Http\Controllers\api\BlocksController::class, 'index']);

        // Admins
        Route::get('admins', [\App\Http\Controllers\api\BlocksController::class, 'admins']);
        Route::post('admins/between', [\App\Http\Controllers\api\BlocksController::class, 'blocksBetween']);
        Route::get('admins-status/{status?}', [\App\Http\Controllers\api\BlocksController::class, 'blockAdminStatus']);
        Route::get('admins/{id}', [\App\Http\Controllers\api\BlocksController::class, 'getAdminBlocks']);
        Route::get('admins/{id}/{status?}', [\App\Http\Controllers\api\BlocksController::class, 'getAdminWithStatusBlocks']);
        Route::get('admins-search/{search?}', [\App\Http\Controllers\api\BlocksController::class, 'searchForAdminBlocks']);
    });

    // Log out
    Route::get('logout', [\App\Http\Controllers\api\AuthenticationController::class, 'logout']);
});

