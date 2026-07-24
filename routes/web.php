<?php

// routes/web.php

use App\Http\Controllers\ExportController;
use Illuminate\Support\Facades\Route;

Route::get('/',        [ExportController::class, 'index'])->name('export.index');
Route::post('/run',    [ExportController::class, 'run'])->name('export.run');
Route::get('/logs',    [ExportController::class, 'logs'])->name('export.logs');
// routes/web.php — dekat route export.run yang sudah ada
Route::post('/exports/run-all', [ExportController::class, 'runAll'])->name('export.run-all');
Route::get('/exports/batch/{batchId}/status', [ExportController::class, 'batchStatus'])->name('export.batch-status');
Route::post('/exports/batch/{batchId}/cancel', [ExportController::class, 'batchCancel'])->name('export.batch-cancel');
Route::get('/exports/latest-logs', [ExportController::class, 'latestLogs'])->name('exports.latestLogs');