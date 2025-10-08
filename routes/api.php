<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/whoami',[AuthController::class, 'whoami']);

    Route::get('/transactions', [TransactionController::class, 'index']);
});
