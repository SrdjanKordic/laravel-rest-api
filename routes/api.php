<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\ChangePasswordController;
use App\Http\Controllers\Auth\ForgotPasswordController;
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

Route::get('login/{provider}',[AuthController::class, 'redirectToProvider']);
Route::get('login/{provider}/callback',[AuthController::class, 'handleProviderCallback']);

Route::group(['middleware' => ['jwt.verify']], function() {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('get_user', [AuthController::class, 'getUser']);
    Route::get('users',[UserController::class,'index']);
});
