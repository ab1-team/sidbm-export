<?php

namespace App\Http\Controllers\Api;

use App\Models\Sidbm\Kecamatan;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Services\SaldoExportService;
use App\Services\TransaksiExportService;
use Illuminate\Http\Request;
use App\Jobs\ExportSaldoTahunJob;
use App\Jobs\ExportTransaksiTahunJob;

class ExportController extends Controller
{
    public function __construct(
        private SaldoExportService $saldoService,
        private TransaksiExportService $transaksiService,
    ) {
    }

public function saldo(Request $request)
{
    $request->validate([
        'kecamatan_id' => 'required|integer|min:1',
        'tahun'        => 'required|integer|min:2018',
    ]);

    $result = $this->saldoService->export(
        (int) $request->kecamatan_id,
        (int) $request->tahun,
        auth()->user()?->name ?? 'api'
    );

    return response()->json([
        'success'    => $result['success'],
        'message'    => $result['message'],
        'log_id'     => $result['log_id'] ?? null,
    ]);
}

public function transaksi(Request $request)
{
    $request->validate([
        'kecamatan_id' => 'required|integer|min:1',
        'tahun'        => 'required|integer|min:2018',
    ]);

    $result = $this->transaksiService->exportTahun(
        (int) $request->kecamatan_id,
        (int) $request->tahun,
        auth()->user()?->name ?? 'api'
    );

    return response()->json([
        'success'  => $result['success'] > 0,
        'message'  => $result['results'][0]['message'] ?? 'Export selesai',
        'log_id'   => $result['results'][0]['log_id'] ?? null,
    ]);
}

public function exportBoth(Request $request)
{
    $request->validate([
        'kecamatan_id' => 'required|integer|min:1',
        'tahun'        => 'required|integer|min:2018',
    ]);

    $kecamatanId = (int) $request->kecamatan_id;
    $tahun = (int) $request->tahun;
    $user = auth()->user()?->name ?? 'api';

    try {
        $saldoResult = $this->saldoService->export($kecamatanId, $tahun, $user);
    } catch (\Exception $e) {
        $saldoResult = ['success' => false, 'message' => $e->getMessage()];
    }

    try {
        $transaksiResult = $this->transaksiService->exportTahun($kecamatanId, $tahun, $user);
    } catch (\Exception $e) {
        $transaksiResult = ['success' => 0, 'failed' => 1, 'results' => [['success' => false, 'message' => $e->getMessage()]]];
    }

    $logs = \App\Models\ExportLog::latest()->limit(20)->get();

    return response()->json([
        'success'  => $saldoResult['success'] || $transaksiResult['success'] > 0,
        'message'  => 'Export selesai',
        'logs'     => $logs,
        'results'  => [
            'saldo' => $saldoResult,
            'transaksi' => $transaksiResult,
        ],
    ]);
}

public function show(Request $request)
{
    $kecamatanId = $request->query('kecamatan');
    $type = $request->query('type');
    $tahun = $request->query('tahun');
    $download = $request->query('download');

    if (!$kecamatanId || !$type || !$tahun) {
        abort(400, 'Parameter kecamatan, type, dan tahun diperlukan.');
    }

    $filename = "{$type}_{$tahun}.json";
    $path = "exports/kecamatan_{$kecamatanId}/{$filename}";

    if (!Storage::disk('local')->exists($path)) {
        abort(404, 'File tidak ditemukan.');
    }

    $content = Storage::disk('local')->get($path);

    if ($download === '1') {
        return response($content, 200, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    return response($content, 200, [
        'Content-Type' => 'application/json',
        'Content-Disposition' => 'inline; filename="' . $filename . '"',
    ]);
}

public function view(Request $request)
{
    $kecamatanId = $request->query('kecamatan');
    $type = $request->query('type');
    $tahun = $request->query('tahun');

    if (!$kecamatanId || !$type || !$tahun) {
        abort(400, 'Parameter kecamatan, type, dan tahun diperlukan.');
    }

    $filename = "{$type}_{$tahun}.json";
    $path = "exports/kecamatan_{$kecamatanId}/{$filename}";

    if (!Storage::disk('local')->exists($path)) {
        abort(404, 'File tidak ditemukan.');
    }

    $content = json_decode(Storage::disk('local')->get($path), true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        abort(400, 'File bukan JSON yang valid.');
    }

    $isSaldo = $type === 'saldo';

    return view('exports.viewer', [
        'filename' => $path,
        'data' => $content,
        'isSaldo' => $isSaldo,
        'kecamatanId' => $kecamatanId,
        'type' => $type,
        'tahun' => $tahun,
    ]);
}

public function runAll(Request $request)
{
    $request->validate([
        'jenis' => 'required|in:saldo,transaksi,semua',
    ]);

    $jenis      = $request->jenis;
    $batasArsip = (int) config('app.arsip_batas_tahun', now()->year - 2);
    $tahunList  = range(2018, $batasArsip - 1);

    $kecamatanList = Kecamatan::orderBy('id')->get(['id']);

    if ($kecamatanList->isEmpty()) {
        return response()->json([
            'success' => false,
            'message' => 'Tidak ada data kecamatan.',
        ], 422);
    }

    $user = auth()->user()?->name ?? 'api';

    $jobs = [];

    foreach ($kecamatanList as $kec) {
        foreach ($tahunList as $tahun) {
            if ($jenis === 'saldo' || $jenis === 'semua') {
                $jobs[] = new ExportSaldoTahunJob($kec->id, $tahun, $user);
            }

            if ($jenis === 'transaksi' || $jenis === 'semua') {
                $jobs[] = new ExportTransaksiTahunJob($kec->id, $tahun, $user);
            }
        }
    }

    $batch = Bus::batch($jobs)
        ->name('export-' . now()->format('YmdHis'))
        ->onQueue('export')
        ->allowFailures()
        ->dispatch();

    $this->triggerQueueWorker();

    return response()->json([
        'success'    => true,
        'message'    => 'Export berjalan di background.',
        'batch_id'   => $batch->id,
        'total_jobs' => count($jobs),
    ]);
}

private function triggerQueueWorker(): void
{
    $lockFile = storage_path('logs/queue_worker.lock');
    $lockData = null;

    if (file_exists($lockFile)) {
        $lockData = json_decode(file_get_contents($lockFile), true);
        $pid = $lockData['pid'] ?? null;

        if ($pid && $this->isProcessRunning($pid)) {
            return;
        }
    }

    $php = PHP_BINARY;
    $artisan = base_path('artisan');

    $command = sprintf(
        'start "" /B "%s" "%s" queue:work database --queue=export --tries=1 --timeout=900 --stop-when-empty > NUL 2>&1',
        $php,
        $artisan
    );

    pclose(popen($command, 'r'));

    $pid = getmypid();
    file_put_contents($lockFile, json_encode([
        'pid' => $pid,
        'started_at' => date('Y-m-d H:i:s'),
    ]));
}

private function isProcessRunning(int $pid): bool
{
    if (stristr(PHP_OS, 'WIN')) {
        $output = shell_exec("tasklist /FI \"PID eq $pid\" 2>nul");
        return $output && strpos($output, (string) $pid) !== false;
    }

    return posix_kill($pid, 0);
}
}