<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\JoinInvitationController;

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


// Public routes
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::post('/validate-employee-creation', [UserController::class, 'validateEmployeeCreation']);
Route::post('/is-invitation-link-valid', [JoinInvitationController::class, 'isInvitationLinkValid']);

Route::middleware('auth:api')->group(function () {

    // Authenticated routes
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/refresh', [AuthController::class, 'refresh'])->name('refresh');
    Route::post('/me', [AuthController::class, 'me'])->name('me');

    Route::put('/update-user', [UserController::class, 'update'])->name('users.update');

    // Routes for superadmin role
    Route::middleware('role:superadmin')->group(function () {
        Route::apiResource('companies', CompanyController::class);
        Route::get('superadmins', [UserController::class, 'getSuperAdmins']);
        Route::get('employees', [UserController::class, 'getEmployees'])->name('user.get-employees');

        // Routes for invitations
        Route::prefix('invitations')->group(function () {
            Route::get('user-invitations', [JoinInvitationController::class, "getUserInvitations"]);
            Route::put('cancel/{id}', [JoinInvitationController::class, 'cancel'])->name('invitation.cancel');
        });
        // Routes for users
        Route::prefix('users')->group(function () {
            Route::post('create-superadmin', [UserController::class, 'createSuperAdmin'])->name('user.create-superadmin');
            Route::post('invite-employee', [UserController::class, 'inviteEmployee'])->name('user.invite-employee');


        });
    });

    // Routes for employee role
    Route::middleware('role:employee')->group(function () {
        Route::prefix('users')->group(function () {
            Route::get('company-employees', [UserController::class, 'getCompanyEmployees'])->name('user.company-employees');
        });
    });
});
