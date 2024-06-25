<?php

use Illuminate\Http\Request;
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

include_once __DIR__ . '/auth.php';

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', [\App\Http\Controllers\UserController::class, 'user']);
    Route::put('/user', [\App\Http\Controllers\UserController::class, 'update']);
    Route::post('/user/passphrase', [\App\Http\Controllers\UserController::class, 'setPassphrase']);

    Route::get('/user/exchange-transactions', [\App\Http\Controllers\UserController::class, 'exchangeTransactions']);

    Route::get('/user/transfer-transactions', [\App\Http\Controllers\UserController::class, 'transferTransactions']);
    Route::get('/user/wallet-transfers', [\App\Http\Controllers\UserController::class, 'walletTransfers']);

    Route::get('/exchange-transactions/candlesticks', [\App\Http\Controllers\ExchangeTransactionController::class, 'candlesticks']);
    Route::post('/exchange-transactions', [\App\Http\Controllers\ExchangeTransactionController::class, 'store']);

    Route::post('/transfer', [\App\Http\Controllers\TransferController::class, 'store']);

    Route::post('/transfer-transactions', [\App\Http\Controllers\TransferTransactionController::class, 'store']);
});
