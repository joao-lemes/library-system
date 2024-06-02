<?php

use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\JwtMiddleware;
use Illuminate\Support\Facades\Route;
use Modules\Loan\Http\Controllers\LoanController;

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

Route::prefix('loan')->middleware(JwtMiddleware::class, AdminMiddleware::class)->name('loan.')->group(function () {
    Route::post('/', [LoanController::class, 'storeAction'])->name('store');
    Route::put('/return/{id}', [LoanController::class, 'returnLoanAction'])->name('return');
    Route::get('/', [LoanController::class, 'listAction'])->name('list');
    Route::get('/{id}', [LoanController::class, 'showAction'])->name('show');
});
