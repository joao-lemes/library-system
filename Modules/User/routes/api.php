<?php

use App\Http\Middleware\AdminMiddleware;
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

Route::prefix('user')->controller(UserController::class)->name('user.')->group(function () {
    Route::post('/', 'storeAction')->name('store');
    Route::middleware(JwtMiddleware::class, AdminMiddleware::class)->group(function () {
        Route::get('/', 'listAction')->name('list');
    });
});

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'loginAction')->name('login');
    Route::middleware(JwtMiddleware::class)->group(function () {
        Route::post('logout', 'logoutAction')->name('logout');
        Route::get('me', 'getAuthenticatedUserAction')->name('me');
    });
});