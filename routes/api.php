<?php

use App\Http\Controllers\Api\FileController;
use Illuminate\Support\Facades\Route;

Route::prefix('exports')->group(function () {
    Route::get('/', [FileController::class, 'listExports']);
    Route::get('/download/{path}', [FileController::class, 'downloadFile'])
        ->where('path', '.*');
    Route::get('/stream/{path}', [FileController::class, 'streamFile'])
        ->where('path', '.*');
});
