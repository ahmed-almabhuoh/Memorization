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

Route::prefix('auto')->middleware(['auth:sanctum', 'deleted-api'])->group(function () {

    /*
     * General Routes (All Roles)
     * */
    Route::prefix('mng/accounts')->group(function () {
        Route::get('/', [\App\Http\Controllers\api\accounts\AccountController::class, 'getAccount']);
        Route::put('/update', [\App\Http\Controllers\api\accounts\AccountController::class, 'updateMyAccount']);
        Route::put('/change-password', [\App\Http\Controllers\api\accounts\AccountController::class, 'changeMyPassword']);
        Route::get('/delete-account', [\App\Http\Controllers\api\accounts\AccountController::class, 'deleteMyAccount']);
    });

//    Route::get('/test', function () {
//        return response()->json([
//            'message' => 'Here'
//        ]);
//    })->middleware('parent-api');


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
     * Keepers
     * */
    Route::prefix('keepers')->middleware(['keeper-api'])->group(function () {
        /*
         * Keeper CRUD
         * */
        Route::prefix('/')->withoutMiddleware(['keeper-api'])->group(function () {
            Route::get('/', [\App\Http\Controllers\api\keepers\KeeperController::class, 'index']);
            Route::get('/{keeper_id}', [\App\Http\Controllers\api\keepers\KeeperController::class, 'show']);
            Route::post('/create', [\App\Http\Controllers\api\keepers\KeeperController::class, 'store']);
            Route::put('/{keeper_id}', [\App\Http\Controllers\api\keepers\KeeperController::class, 'update']);
            Route::delete('/{keeper_id}', [\App\Http\Controllers\api\keepers\KeeperController::class, 'destroy']);
        });


        /*
         * Keeper Groups
         * */
        Route::prefix('groups')->group(function () {
            Route::get('/', [\App\Http\Controllers\api\keepers\KeeperGroupController::class, 'getGroups']);
            Route::get('/students/{group_id?}', [\App\Http\Controllers\api\keepers\KeeperGroupController::class, 'getGroupStudents']);
        });
    });

    /*
     * Student & Parent
     * */
    Route::prefix('/')->middleware(['student-parent-api'])->group(function () {


        /*
         * Parents
         * */
        Route::prefix('/parents')->withoutMiddleware(['student-parent-api'])->group(function () {
            Route::get('/', [\App\Http\Controllers\api\parents\ParentController::class, 'index']);
            Route::get('/{parent_id}', [\App\Http\Controllers\api\parents\ParentController::class, 'show']);
            Route::post('/create', [\App\Http\Controllers\api\parents\ParentController::class, 'store']);
            Route::put('/{parent_id}', [\App\Http\Controllers\api\parents\ParentController::class, 'update']);
            Route::delete('/{parent_id}', [\App\Http\Controllers\api\parents\ParentController::class, 'destroy']);
        });


        /*
         * Students
         * */
        Route::prefix('/students')->withoutMiddleware(['student-parent-api'])->group(function () {
            Route::get('/', [\App\Http\Controllers\api\students\StudentController::class, 'index']);
            Route::get('/{student_id}', [\App\Http\Controllers\api\students\StudentController::class, 'show']);
            Route::post('/create', [\App\Http\Controllers\api\students\StudentController::class, 'store']);
            Route::put('/{student_id}', [\App\Http\Controllers\api\students\StudentController::class, 'update']);
            Route::delete('/{student_id}', [\App\Http\Controllers\api\students\StudentController::class, 'destroy']);
        });


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


    // Log out
    Route::get('logout', [\App\Http\Controllers\api\AuthenticationController::class, 'logout'])->name('logout');
});



