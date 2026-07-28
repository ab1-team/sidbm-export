<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ExportController;
use App\Http\Controllers\Api\LogController;
use App\Http\Controllers\Api\BatchController;

Route::prefix('export')->group(function () {

    Route::post('/saldo', [ExportController::class, 'saldo']);

    Route::post('/transaksi', [ExportController::class, 'transaksi']);

    Route::post('/semua', [ExportController::class, 'semua']);

    Route::post('/run-all', [ExportController::class, 'runAll']);

    Route::get('/logs', [LogController::class, 'latest']);

});
Route::prefix('batch')->group(function () {

    Route::get('{batchId}', [BatchController::class, 'status']);

    Route::post('{batchId}/cancel', [BatchController::class, 'cancel']);

});
