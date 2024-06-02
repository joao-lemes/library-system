<?php

use App\Http\Middleware\JwtMiddleware;
use Illuminate\Support\Facades\Route;
use Modules\User\Http\Controllers\AuthController;
use Modules\User\Http\Controllers\UserController;

/*
 *--------------------------------------------------------------------------
 * API Routes
 *--------------------------------------------------------------------------
 *
 * Here is where you can register API routes for your application. These
 * routes are loaded by the RouteServiceProvider within a group which
 * is assigned the "api" middleware group. Enjoy building your API!
 *
*/

Route::post('user/register', [UserController::class, 'storeAction']);
Route::post('login', [AuthController::class, 'loginAction']);

Route::middleware(JwtMiddleware::class)->group(function () {
    Route::post('logout', [AuthController::class, 'logoutAction']);
    Route::get('user/me', [AuthController::class, 'getAuthenticatedUserAction']);
});