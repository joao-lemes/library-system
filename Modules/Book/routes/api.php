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

Route::prefix('book')->middleware(JwtMiddleware::class)->name('book.')->group(function () {
    Route::middleware(AdminMiddleware::class)->group(function () {
        Route::post('/', [BookController::class, 'storeAction'])->name('store');
        Route::put('/{id}', [BookController::class, 'updateAction'])->name('update');
        Route::delete('/{id}', [BookController::class, 'destroyAction'])->name('destroy');
    });
    Route::get('/', [BookController::class, 'listAction'])->name('list');
    Route::get('/{id}', [BookController::class, 'showAction'])->name('show');
});
