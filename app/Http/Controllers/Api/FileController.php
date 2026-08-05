<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ExportLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function listExports(Request $request)
    {
        $query = ExportLog::query()
            ->orderByDesc('created_at');

        if ($request->filled('kecamatan_id')) {
            $query->where('kecamatan_id', $request->kecamatan_id);
        }

        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $perPage = min($request->integer('per_page', 50), 100);
        $exports = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $exports->items(),
            'meta' => [
                'current_page' => $exports->currentPage(),
                'last_page' => $exports->lastPage(),
                'per_page' => $exports->perPage(),
                'total' => $exports->total(),
            ],
        ]);
    }

    public function downloadFile(Request $request, string $path)
    {
        $path = urldecode($path);

        if (!str_starts_with($path, 'exports/')) {
            $path = 'exports/' . ltrim($path, '/');
        }

        if (!Storage::disk('local')->exists($path)) {
            return response()->json([
                'success' => false,
                'message' => 'File tidak ditemukan.',
            ], 404);
        }

        $fullPath = Storage::disk('local')->path($path);
        $filename = basename($path);

        return response()->download($fullPath, $filename, [
            'Content-Type' => 'application/json',
        ]);
    }

    public function streamFile(Request $request, string $path)
    {
        $path = urldecode($path);

        if (!str_starts_with($path, 'exports/')) {
            $path = 'exports/' . ltrim($path, '/');
        }

        if (!Storage::disk('local')->exists($path)) {
            return response()->json([
                'success' => false,
                'message' => 'File tidak ditemukan.',
            ], 404);
        }

        $fullPath = Storage::disk('local')->path($path);
        $filename = basename($path);
        $filesize = filesize($fullPath);

        $stream = fopen($fullPath, 'r');

        return response()->stream(function () use ($stream) {
            fpassthru($stream);
            fclose($stream);
        }, 200, [
            'Content-Type' => 'application/json',
            'Content-Length' => $filesize,
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Accept-Ranges' => 'bytes',
        ]);
    }
}
