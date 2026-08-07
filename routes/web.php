<?php
// routes/web.php

use App\Http\Controllers\ExportController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ExportController as ApiExportController;
use App\Http\Controllers\Api\BatchController;
use App\Http\Controllers\Api\LogController;


Route::middleware('auth')->group(function () {

    Route::prefix('api')->group(function () {

    Route::prefix('export')->group(function () {

        Route::post('/saldo', [ApiExportController::class, 'saldo']);
        Route::post('/transaksi', [ApiExportController::class, 'transaksi']);
        Route::post('/semua', [ApiExportController::class, 'exportBoth']);
        Route::post('/run-all', [ApiExportController::class, 'runAll']);
        Route::get('/logs', [LogController::class, 'latest']);
        Route::get('/files', [ApiExportController::class, 'show']);

    });

    Route::prefix('batch')->group(function () {

        Route::get('{batchId}', [BatchController::class, 'status']);
        Route::post('{batchId}/cancel', [BatchController::class, 'cancel']);

    });

});

    Route::get('/', [ExportController::class, 'dashboard'])->name('dashboard');

    Route::get('/export-data', [ExportController::class, 'exportData'])->name('export-data');


    Route::post('/run',    [ExportController::class, 'run'])->name('export.run');
    Route::get('/logs',    [ExportController::class, 'logs'])->name('export.logs');

    Route::post('/exports/run-all', [ExportController::class, 'runAll'])->name('export.run-all');
    Route::get('/exports/batch/{batchId}/status', [ExportController::class, 'batchStatus'])->name('export.batch-status');
    Route::post('/exports/batch/{batchId}/cancel', [ExportController::class, 'batchCancel'])->name('export.batch-cancel');
    Route::get('/exports/latest-logs', [ExportController::class, 'latestLogs'])->name('exports.latestLogs');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';