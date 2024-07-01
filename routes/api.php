<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Mail;

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

Route::middleware('auth:api')->group(function () {

    // Authenticated routes
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/refresh', [AuthController::class, 'refresh'])->name('refresh');
    Route::post('/me', [AuthController::class, 'me'])->name('me');

    // Routes for superadmin role
    Route::middleware('role:superadmin')->group(function () {

        Route::apiResource('companies', CompanyController::class);

        // Routes for users
        Route::prefix('users')->group(function () {
            Route::post('create-superadmin', [UserController::class, 'createSuperAdmin'])->name('user.create-superadmin');
            Route::post('invite-employee', [UserController::class, 'inviteEmployee'])->name('user.invite-employee');

            Route::get('send-mail', function () {
                try {
                    Mail::to('recipient@example.com')->send(new \App\Mail\TestMail());
                    return 'Email sent!';
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('Mailjet Error: ' . $e->getMessage());
                    return 'Failed to send email: ' . $e->getMessage();
                }


            });
        });
    });

    // Routes for employee role
    Route::middleware('role:employee')->group(function () {
        Route::get('/employee', function () {
            return "hello Employee";
        });
    });
});
