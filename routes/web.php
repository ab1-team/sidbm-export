<?php

// routes/web.php

use App\Http\Controllers\ExportController;
use Illuminate\Support\Facades\Route;

Route::get('/',        [ExportController::class, 'index'])->name('export.index');
Route::post('/run',    [ExportController::class, 'run'])->name('export.run');
Route::get('/logs',    [ExportController::class, 'logs'])->name('export.logs');
