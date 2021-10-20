<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\ChangePasswordController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login', [AuthController::class, 'authenticate']);
Route::post('forgot-password', [ForgotPasswordController::class, 'sendPasswordResetEmail']);
Route::post('reset-password', [ChangePasswordController::class, 'passwordResetProcess']);
Route::post('register', [AuthController::class, 'register']);
Route::post('token/refresh', [AuthController::class, 'refreshToken']);

Route::get('login/{provider}',[AuthController::class, 'redirectToProvider']);
Route::get('login/{provider}/callback',[AuthController::class, 'handleProviderCallback']);

Route::group(['middleware' => ['jwt.verify']], function() {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('get_user', [AuthController::class, 'getUser']);
    // User routes
    Route::get('users',[UserController::class,'index']);
    Route::post('user', [UserController::class, 'store']);
    Route::get('user/{id}',[UserController::class, 'show']);
    Route::put('user/{id}',[UserController::class, 'update']);
    Route::delete('user/{id}',[UserController::class, 'destroy']);
    Route::post('user/change-password',[UserController::class, 'changePassword']);
    Route::post('user/avatar',[UserController::class,'uploadAvatar']);
    // Role routes
    Route::get('roles',[RoleController::class,'index']);
    Route::post('role',[RoleController::class,'store']);
    Route::get('role/{id}',[RoleController::class, 'show']);
    Route::put('role/{id}',[RoleController::class, 'update']);
    Route::delete('role/{id}',[RoleController::class, 'destroy']);
    // Permissions
    Route::get('permissions',[PermissionController::class,'index']);
    Route::post('permissions/ids',[PermissionController::class,'permissionIdsFromNames']);
    Route::post('permissions/names',[PermissionController::class,'permissionNamesFromIds']);
});
