<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//Route::get('/', function () {
//    return view('welcome');
//});


Route::prefix('/')->middleware(['auth:web', 'activation', 'soft_deleted'])
    ->group(function () {
        Route::prefix('auto')->group(function () {
            Route::resource('managers', \App\Http\Controllers\ManagerController::class);
            Route::resource('admins', \App\Http\Controllers\AdminController::class);
            Route::resource('supervisors', \App\Http\Controllers\SupervisorController::class);
            Route::resource('keepers', \App\Http\Controllers\KeeperController::class);
            Route::resource('parents', \App\Http\Controllers\ParentController::class);
            Route::resource('students', \App\Http\Controllers\StudentController::class);

            Route::resource('branches', \App\Http\Controllers\BranchController::class);
            Route::resource('centers', \App\Http\Controllers\CenterController::class);
            Route::resource('apis', \App\Http\Controllers\APIKEYController::class);
            Route::resource('supervision_committees', \App\Http\Controllers\SupervisionCommitteeController::class);
            Route::resource('groups', \App\Http\Controllers\GroupController::class);
        });

        Route::prefix('auto')->group(function () {
            Route::get('/', [\App\Http\Controllers\backend\DashboardController::class, 'dashboard'])->name('admin.dashboard');

            // Excel Routes
            Route::get('/apis/excel/report', [\App\Http\Controllers\APIKEYController::class, 'getReport'])->name('apis.report.xlsx');
            Route::get('/apis/excel/report/{id}', [\App\Http\Controllers\APIKEYController::class, 'getReportSpecificAPI'])->name('api.report.xlsx');
            Route::get('/supervision_committees/excel/report', [\App\Http\Controllers\SupervisionCommitteeController::class, 'getReport'])->name('supervision_committees.report.xlsx');
            Route::get('/supervision_committees/excel/report/{id}', [\App\Http\Controllers\SupervisionCommitteeController::class, 'getReportSpecificSupervisionCommittee'])->name('supervision_committee.report.xlsx');
            Route::get('/centers/excel/report', [\App\Http\Controllers\CenterController::class, 'getReport'])->name('centers.report.xlsx');
            Route::get('/centers/excel/report/{id}', [\App\Http\Controllers\CenterController::class, 'getReportSpecificCenter'])->name('center.report.xlsx');
            Route::get('/branch/excel/report', [\App\Http\Controllers\BranchController::class, 'getReport'])->name('branches.report.xlsx');
            Route::get('/branch/excel/report/{id}', [\App\Http\Controllers\BranchController::class, 'getReportSpecificBranch'])->name('branch.report.xlsx');
            Route::get('/group/excel/report', [\App\Http\Controllers\GroupController::class, 'getReport'])->name('groups.report.xlsx');
            Route::get('/group/excel/report/{id}', [\App\Http\Controllers\GroupController::class, 'getReportSpecificGroup'])->name('group.report.xlsx');

            Route::get('/user/excel/report', [\App\Http\Controllers\UserController::class, 'getReport'])->name('users.report.xlsx');
            Route::get('/user/excel/report/{id}', [\App\Http\Controllers\UserController::class, 'getReportSpecificUser'])->name('user.report.xlsx');
            Route::get('/user/excel/position-report/{position?}', [\App\Http\Controllers\UserController::class, 'getReportSpecificPosition'])->name('position.report.xlsx');

            // Assign Supervisor to SC
            Route::get('/supervisor-to-sc/{id}', [\App\Http\Controllers\SupervisionCommitteeController::class, 'showAddSupverisors'])->name('supervisors.to.sc');
            Route::post('/supervisor-to-sc/{id}/supervisor/{s_id}', [\App\Http\Controllers\SupervisionCommitteeController::class, 'addSupervisorToSC']);

            // Assign Supervisor to Branch
            Route::get('/supervisor-to-branch/{id}', [\App\Http\Controllers\BranchController::class, 'showAddSupverisors'])->name('supervisors.to.branch');
            Route::post('/supervisor-to-branch/{branch_id}/supervisor/{s_id}', [\App\Http\Controllers\BranchController::class, 'addSupervisorToBranch']);

            // Block Routes
            Route::get('/blockes/{blocked_id}/{guard?}', [\App\Http\Controllers\BlockController::class, 'show'])->name('user.blocks');
            Route::post('block/{blocked_id}/{guard}', [\App\Http\Controllers\BlockController::class, 'store'])->name('blocks.store');

            Route::get('/logout', [\App\Http\Controllers\AuthenticationController::class, 'logout'])->name('logout')->withoutMiddleware(['activation', 'soft_deleted']);
        });
    });

Route::prefix('auto')->middleware(['guest:web'])->group(function () {
    Route::get('{guard}/login', [\App\Http\Controllers\AuthenticationController::class, 'showLogin'])->name('login');
    Route::post('login', [\App\Http\Controllers\AuthenticationController::class, 'login']);
});
