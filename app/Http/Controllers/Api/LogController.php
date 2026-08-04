<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ExportLog;

class LogController extends Controller
{
    public function latest()
    {
       $logs = ExportLog::latest()
    ->limit(20)
    ->get([
        'id',
        'kecamatan_id',
        'jenis',
        'tahun',
        'bulan',
        'filename',
        'file_url',
        'status',
        'record_count',
        'file_size',
        'created_at',
    ]);

        return response()->json([
            'success' => true,
            'logs' => $logs,
        ]);
    }
}