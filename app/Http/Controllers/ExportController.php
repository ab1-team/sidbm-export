<?php

// app/Http/Controllers/ExportController.php

namespace App\Http\Controllers;

use App\Models\ExportLog;
use App\Models\Sidbm\Kecamatan;
use App\Services\EnStorageService;
use App\Services\SaldoExportService;
use App\Services\TransaksiExportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use App\Jobs\ExportSaldoTahunJob;
use App\Jobs\ExportTransaksiTahunJob;

class ExportController extends Controller
{
    public function __construct(
        private SaldoExportService     $saldoService,
        private TransaksiExportService $transaksiService,
        private EnStorageService       $enstorage,
    ) {}

    /**
     * Halaman utama — dashboard export
     */
    public function index()
    {
        $batasArsip  = (int) config('app.arsip_batas_tahun', now()->year - 2);
        $tahunList   = range(2018, $batasArsip - 1);
        $kecamatanList = Kecamatan::orderBy('id')->get(['id', 'nama_kec']);

        // Ringkasan log terbaru
        $recentLogs = ExportLog::with([])
            ->latest()
            ->limit(20)
            ->get();

        // Statistik
        $stats = [
            'total_success' => ExportLog::where('status', 'success')->count(),
            'total_failed'  => ExportLog::where('status', 'failed')->count(),
            'total_pending' => ExportLog::where('status', 'pending')->count(),
        ];

        $enstoragePing = $this->enstorage->ping();

        return view('exports.index', compact(
            'tahunList', 'kecamatanList', 'recentLogs', 'stats', 'enstoragePing', 'batasArsip'
        ));
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

       
$saldoJobs = [];
$transaksiJobs = [];

foreach ($kecamatanList as $kec) {
    foreach ($tahunList as $tahun) {

        if ($jenis === 'saldo') {

            $saldoJobs[] = new ExportSaldoTahunJob(
                $kec->id,
                $tahun,
                $user
            );

        } elseif ($jenis === 'transaksi') {

            $transaksiJobs[] = new ExportTransaksiTahunJob(
                $kec->id,
                $tahun,
                $user
            );

        } elseif ($jenis === 'semua') {

            $saldoJobs[] = new ExportSaldoTahunJob(
                $kec->id,
                $tahun,
                $user
            );

            $transaksiJobs[] = new ExportTransaksiTahunJob(
                $kec->id,
                $tahun,
                $user
            );
        }
    }
}

$batchSaldo = null;
$batchTransaksi = null;

if (!empty($saldoJobs)) {
    $batchSaldo = Bus::batch($saldoJobs)
        ->name('export-saldo-' . now()->format('YmdHis'))
        ->onQueue('saldo')
        ->allowFailures()
        ->dispatch();
}

if (!empty($transaksiJobs)) {
    $batchTransaksi = Bus::batch($transaksiJobs)
        ->name('export-transaksi-' . now()->format('YmdHis'))
        ->onQueue('transaksi')
        ->allowFailures()
        ->dispatch();
}
return response()->json([
    'success' => true,
    'message' => 'Export berjalan di background.',
    'saldo_batch_id' => $batchSaldo?->id,
    'transaksi_batch_id' => $batchTransaksi?->id,
    'total_jobs' => count($saldoJobs) + count($transaksiJobs),
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

        $logs = ExportLog::query()
            ->when($kecamatanId, fn($q) => $q->where('kecamatan_id', $kecamatanId))
            ->when($jenis,       fn($q) => $q->where('jenis', $jenis))
            ->when($status,      fn($q) => $q->where('status', $status))
            ->latest()
            ->paginate(25);

        $kecamatanList = Kecamatan::orderBy('id')->get(['id', 'nama_kec']);

        return view('exports.logs', compact('logs', 'kecamatanList', 'kecamatanId', 'jenis', 'status'));
    }
}