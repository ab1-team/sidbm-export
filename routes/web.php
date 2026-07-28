<?php
// routes/web.php

use App\Http\Controllers\ExportController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {

    Route::get('/',        [ExportController::class, 'index'])->name('export.index');
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