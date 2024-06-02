<?php

use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\JwtMiddleware;
use Illuminate\Support\Facades\Route;
use Modules\Book\Http\Controllers\BookController;

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

Route::prefix('book')->controller(BookController::class)->name('book.')
    ->middleware(JwtMiddleware::class)->group(function () {
        Route::middleware(AdminMiddleware::class)->group(function () {
            Route::post('/', 'storeAction')->name('store');
            Route::put('/{id}', 'updateAction')->name('update');
            Route::delete('/{id}', 'destroyAction')->name('destroy');
        });
        Route::get('/', 'listAction')->name('list');
        Route::get('/{id}', 'showAction')->name('show');
});
