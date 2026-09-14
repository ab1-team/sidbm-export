<?php

use App\Http\Controllers\Api\ExportController;
use App\Http\Controllers\Api\FileController;
use App\Http\Controllers\Api\LogController;
use App\Services\EnStorageService;
use Illuminate\Support\Facades\Route;

Route::get('/enstorage/ping', function () {
    $service = app(EnStorageService::class);
    return response()->json([
        'connected' => $service->ping(),
    ]);
});

Route::prefix('export')->group(function () {
    Route::get('/files', [ExportController::class, 'show']);
    Route::get('/logs', [LogController::class, 'latest']);
    Route::post('/run-all', [ExportController::class, 'runAll']);
    Route::post('/saldo', [ExportController::class, 'saldo']);
    Route::post('/semua', [ExportController::class, 'exportBoth']);
    Route::post('/transaksi', [ExportController::class, 'transaksi']);
});

Route::prefix('exports')->group(function () {
    Route::get('/', [FileController::class, 'listExports']);
    Route::get('/download/{path}', [FileController::class, 'downloadFile'])
        ->where('path', '.*');
    Route::get('/stream/{path}', [FileController::class, 'streamFile'])
        ->where('path', '.*');
});
