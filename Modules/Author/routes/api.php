<?php

use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\JwtMiddleware;
use Illuminate\Support\Facades\Route;
use Modules\Author\Http\Controllers\AuthorController;

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

Route::prefix('author')->middleware(JwtMiddleware::class)->name('author.')->group(function () {
    Route::middleware(AdminMiddleware::class)->group(function () {
        Route::post('/', [AuthorController::class, 'storeAction'])->name('store');
        Route::put('/{id}', [AuthorController::class, 'updateAction'])->name('update');
        Route::delete('/{id}', [AuthorController::class, 'destroyAction'])->name('destroy');
    });
    Route::get('/', [AuthorController::class, 'listAction'])->name('list');
    Route::get('/{id}', [AuthorController::class, 'showAction'])->name('show');
});
