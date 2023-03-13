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

    Route::get('/test', function () {
        return response()->json([
            'message' => 'Here'
        ]);
    })->middleware('parent-api');

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

    /*
     * Supervisors
     * */
    Route::prefix('supervisors')->group(function () {
        Route::get('/', [\App\Http\Controllers\api\Supervisors\SupervisorController::class, 'index']);
        Route::get('/{supervisor_id}', [\App\Http\Controllers\api\Supervisors\SupervisorController::class, 'show']);
        Route::post('create', [\App\Http\Controllers\api\Supervisors\SupervisorController::class, 'store']);
        Route::put('/{supervisor_id}', [\App\Http\Controllers\api\Supervisors\SupervisorController::class, 'update']);
        Route::delete('/{supervisor_id}', [\App\Http\Controllers\api\Supervisors\SupervisorController::class, 'destroy']);
    });

    /*
     * Student & Parent
     * */
    Route::prefix('/')->middleware(['student-parent-api'])->group(function () {

        /*
         * Account Routes
         * */
        Route::prefix('account')->group(function () {
            Route::get('/', [\App\Http\Controllers\api\AccountController::class, 'getAccoountInformation']);
            Route::put('/update', [\App\Http\Controllers\api\AccountController::class, 'update']);
            Route::put('/change-password', [\App\Http\Controllers\api\AccountController::class, 'changePassword']);
        });

        /*
         * Keeps API
         * */
        Route::prefix('keep')->group(function () {
            Route::get('/', [\App\Http\Controllers\api\KeepsController::class, 'index']);


            /*
             * Avilable also to keeper
             * */
            Route::prefix('/')->withoutMiddleware(['student-parent-api'])->group(function () {
                Route::post('/create/student/{student_id}', [\App\Http\Controllers\api\KeepsController::class, 'setKeep']);
                Route::get('juz/{juz_id?}', [\App\Http\Controllers\api\KeepsController::class, 'loadQuranResources']);
            });
        });
    });

    Route::prefix('keepers')->middleware(['keeper-api'])->group(function () {
        Route::prefix('groups')->group(function () {
            Route::get('/', [\App\Http\Controllers\api\keepers\KeeperGroupController::class, 'getGroups']);
            Route::get('/students/{group_id?}', [\App\Http\Controllers\api\keepers\KeeperGroupController::class, 'getGroupStudents']);
        });
    });

    // Log out
    Route::get('logout', [\App\Http\Controllers\api\AuthenticationController::class, 'logout']);
});



