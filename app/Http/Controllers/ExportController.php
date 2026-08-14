<?php

// app/Http/Controllers/ExportController.php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;
use App\Models\ExportLog;
use App\Models\Notification;
use App\Models\Sidbm\Kecamatan;
use App\Services\EnStorageService;
use App\Services\SaldoExportService;
use App\Services\TransaksiExportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use App\Jobs\ExportSaldoTahunJob;
use App\Jobs\ExportTransaksiTahunJob;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Cache;

class ExportController extends Controller
{
    public function __construct(
        private SaldoExportService     $saldoService,
        private TransaksiExportService $transaksiService,
        private EnStorageService       $enstorage,
    ) {}

    /**
     * Halaman Dashboard — hanya statistik
     */
    public function dashboard()
    {
        $stats = [
            'total'         => ExportLog::count(),
            'total_success' => ExportLog::where('status', 'success')->count(),
            'total_failed'  => ExportLog::where('status', 'failed')->count(),
            'total_pending' => ExportLog::where('status', 'pending')->count(),
        ];

        $enstoragePing = $this->enstorage->ping();

        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $monthlyData = collect(range(1, 12))->map(function ($month) {
            return ExportLog::whereMonth('created_at', $month)
                ->whereYear('created_at', now()->year)
                ->count();
        })->toArray();

        return view('dashboard', compact('stats', 'enstoragePing', 'months', 'monthlyData'));
    }

    /**
     * Halaman Export Data — form export dan log terbaru
     */
    public function exportData()
    {
        $batasArsip  = (int) config('app.arsip_batas_tahun', now()->year - 2);
        $tahunList   = range(2018, $batasArsip - 1);
        $kecamatanList = Kecamatan::orderBy('id')->get(['id', 'nama_kec']);

        $stats = [
            'total'         => ExportLog::count(),
            'total_success' => ExportLog::where('status', 'success')->count(),
            'total_failed'  => ExportLog::where('status', 'failed')->count(),
            'total_pending' => ExportLog::where('status', 'pending')->count(),
        ];

        $enstoragePing = $this->enstorage->ping();

        return view('exports.export-data', compact(
            'tahunList', 'kecamatanList', 'stats', 'enstoragePing', 'batasArsip'
        ));
    }

    /**
     * Halaman utama lama — redirect ke dashboard
     */
    public function index()
    {
        return redirect()->route('dashboard');
    }

        public function latestLogs()
    {
        $logs = ExportLog::latest()
            ->limit(20)
            ->get();

        return response()->json([
            'success' => true,
            'logs' => $logs,
        ]);
    }

    public function viewFile(Request $request)
    {
        $kecamatanId = $request->query('kecamatan');
        $type = $request->query('type');
        $tahun = $request->query('tahun');

        if (!$kecamatanId || !$type || !$tahun) {
            abort(400, 'Parameter kecamatan, type, dan tahun diperlukan.');
        }

        $filename = "{$type}_{$tahun}.json";
        $path = "exports/kecamatan_{$kecamatanId}/{$filename}";

        if (!\Illuminate\Support\Facades\Storage::disk('local')->exists($path)) {
            abort(404, 'File tidak ditemukan.');
        }

        $content = \Illuminate\Support\Facades\Storage::disk('local')->get($path);

        return response($content, 200, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
        ]);
    }

    public function viewExport(Request $request)
    {
        $kecamatanId = $request->query('kecamatan');
        $type = $request->query('type');
        $tahun = $request->query('tahun');

        if (!$kecamatanId || !$type || !$tahun) {
            abort(400, 'Parameter kecamatan, type, dan tahun diperlukan.');
        }

        $filename = "{$type}_{$tahun}.json";
        $path = "exports/kecamatan_{$kecamatanId}/{$filename}";

        if (!\Illuminate\Support\Facades\Storage::disk('local')->exists($path)) {
            abort(404, 'File tidak ditemukan.');
        }

        $content = \Illuminate\Support\Facades\Storage::disk('local')->get($path);
        $data = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            abort(400, 'File bukan JSON yang valid.');
        }

        $isSaldo = $type === 'saldo';

        return view('exports.viewer', compact('filename', 'kecamatanId', 'type', 'tahun', 'data', 'isSaldo'));
    }

    /**
     * Jalankan export via AJAX dari UI (mode manual: 1 kecamatan + 1 tahun)
     * Dipanggil saat user klik tombol Export di halaman
     */
    public function run(Request $request)
    {
        $request->validate([
            'kecamatan_id' => 'required|integer|min:1',
            'tahun'        => 'required|integer|min:2000',
            'jenis'        => 'required|in:saldo,transaksi,semua',
        ]);

        $kecamatanId = (int) $request->kecamatan_id;
        $tahun       = (int) $request->tahun;
        $jenis       = $request->jenis;
        $batasArsip  = (int) config('app.arsip_batas_tahun', now()->year - 2);

        // Validasi batas arsip
        if ($tahun >= $batasArsip) {
            return response()->json([
                'success' => false,
                'message' => "Tahun {$tahun} belum bisa diarsip. Batas arsip: sebelum {$batasArsip}.",
            ], 422);
        }

        $results = [];

        // Export Saldo
        if (in_array($jenis, ['saldo', 'semua'])) {
            $results['saldo'] = $this->saldoService->export($kecamatanId, $tahun, auth()->user()?->name ?? 'ui');
        }

        // Export Transaksi (semua bulan)
        if (in_array($jenis, ['transaksi', 'semua'])) {
            $results['transaksi'] = $this->transaksiService->exportTahun($kecamatanId, $tahun, auth()->user()?->name ?? 'ui');
        }

        $overallSuccess = collect($results)->every(fn($r) => $r['success'] ?? ($r['success'] > 0));

        if (auth()->check()) {
            if ($overallSuccess) {
                Notification::exportSuccess(
                    auth()->id(),
                    $jenis,
                    $jenis === 'semua' ? 'Semua data' : ($results[$jenis]['filename'] ?? 'unknown')
                );
            } else {
                Notification::exportFailed(
                    auth()->id(),
                    $jenis,
                    'Export selesai dengan error'
                );
            }
        }

        return response()->json([
            'success' => $overallSuccess,
            'message' => $overallSuccess ? 'Export berhasil' : 'Export selesai dengan beberapa error',
            'results' => $results,
        ]);
    }

    /**
     * Dispatch export SEMUA kecamatan × semua tahun ke background (queue batch)
     * Urutan: kecamatan_1/tahun tertua → kecamatan_1/tahun terbaru → kecamatan_2/... dst
     * Berjalan lewat queue worker, sehingga TIDAK terpengaruh jika browser/tab ditutup.
     */
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

        $user = auth()->user()?->name ?? 'ui';

$jobs = [];

foreach ($kecamatanList as $kec) {

    foreach ($tahunList as $tahun) {

        if (in_array($jenis, ['saldo', 'semua'])) {
            $jobs[] = new ExportSaldoTahunJob(
                $kec->id,
                $tahun,
                $user,
                auth()->id()
            );
        }

        if (in_array($jenis, ['transaksi', 'semua'])) {
            $jobs[] = new ExportTransaksiTahunJob(
                $kec->id,
                $tahun,
                $user,
                auth()->id()
            );
        }

    }

}


$batch = Bus::batch($jobs)
    ->name('export-' . now()->format('YmdHis'))
    ->onQueue('export')
    ->allowFailures()
    ->dispatch();

$this->ensureQueueWorkerRunning();


    return response()->json([
    'success' => true,
    'message' => 'Export berjalan di background.',
    'batch_id' => $batch->id,
    'total_jobs' => count($jobs),
]);
    }

    /**
     * Cek progress batch export (dipanggil oleh polling di frontend)
     */
    public function batchStatus(string $batchId)
    {
        $batch = Bus::findBatch($batchId);

        if (! $batch) {
            return response()->json(['success' => false, 'message' => 'Batch tidak ditemukan.'], 404);
        }

        return response()->json([
            'success'   => true,
            'total'     => $batch->totalJobs,
            'processed' => $batch->processedJobs(),
            'failed'    => $batch->failedJobs,
            'pending'   => $batch->pendingJobs,
            'finished'  => $batch->finished(),
            'cancelled' => $batch->cancelled(),
            'percent'   => $batch->totalJobs > 0
                ? round(($batch->processedJobs() / $batch->totalJobs) * 100)
                : 0,
        ]);
    }

    /**
     * Batalkan sisa proses batch yang sedang berjalan
     */
    public function batchCancel(string $batchId)
    {
        $batch = Bus::findBatch($batchId);

        if (! $batch) {
            return response()->json(['success' => false, 'message' => 'Batch tidak ditemukan.'], 404);
        }

        $batch->cancel();

        return response()->json(['success' => true, 'message' => 'Sisa proses dibatalkan.']);
    }

    /**
     * Halaman log per kecamatan
     */
    public function logs(Request $request)
    {
        $kecamatanId = $request->query('kecamatan_id');
        $jenis       = $request->query('jenis');
        $status      = $request->query('status');
        $tahun       = $request->query('tahun');

        $logs = ExportLog::query()
            ->when($kecamatanId, fn($q) => $q->where('kecamatan_id', $kecamatanId))
            ->when($jenis,       fn($q) => $q->where('jenis', $jenis))
            ->when($status,      fn($q) => $q->where('status', $status))
            ->when($tahun,       fn($q) => $q->where('tahun', $tahun))
            ->latest()
            ->paginate(25);

        $kecamatanList = Kecamatan::orderBy('id')->get(['id', 'nama_kec']);
        $tahunList = ExportLog::select('tahun')->distinct()->orderByDesc('tahun')->pluck('tahun');

        $stats = [
            'success' => ExportLog::where('status', 'success')->count(),
            'failed'  => ExportLog::where('status', 'failed')->count(),
        ];

        $enstoragePing = $this->enstorage->ping();

        return view('exports.logs', compact('logs', 'kecamatanList', 'kecamatanId', 'jenis', 'status', 'tahun', 'tahunList', 'stats', 'enstoragePing'));
    }

private function ensureQueueWorkerRunning(): void
{
    Log::info('ensureQueueWorkerRunning dipanggil');
    $lockKey = 'queue-worker-running';

    if (Cache::has($lockKey)) {
        return;
    }

    Cache::put($lockKey, true, now()->addHours(6));

    $php = PHP_BINARY;
    $artisan = base_path('artisan');

    $command = sprintf(
        'start "Laravel Queue Worker" cmd /k "%s %s queue:work database --queue=export --tries=1 --timeout=900 --stop-when-empty"',
        $php,
        $artisan
    );

    pclose(popen($command, 'r'));
}
}
