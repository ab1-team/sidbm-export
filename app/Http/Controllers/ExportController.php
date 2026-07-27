<?php

// app/Http/Controllers/ExportController.php

namespace App\Http\Controllers;

use App\Models\ExportLog;
use App\Models\Sidbm\Kecamatan;
use App\Services\EnStorageService;
use App\Services\SaldoExportService;
use App\Services\TransaksiExportService;
use Illuminate\Http\Request;

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

        // Statistik untuk dashboard
        $totalKecamatan = $kecamatanList->count();

        // Count total saldo across all kecamatan
        $totalSaldo = 0;
        foreach ($kecamatanList as $kec) {
            try {
                $saldo = new \App\Models\Sidbm\SaldoModel($kec->id);
                $totalSaldo += $saldo->count();
            } catch (\Exception $e) {
                // Skip if table doesn't exist
            }
        }

        // Count total transaksi across all kecamatan
        $totalTransaksi = 0;
        foreach ($kecamatanList as $kec) {
            try {
                $transaksi = new \App\Models\Sidbm\TransaksiModel($kec->id);
                $totalTransaksi += $transaksi->count();
            } catch (\Exception $e) {
                // Skip if table doesn't exist
            }
        }

        // Last export
        $lastExport = ExportLog::where('status', 'success')->latest('created_at')->first();

        $enstoragePing = $this->enstorage->ping();

        return view('exports.index', compact(
            'tahunList', 'kecamatanList', 'totalKecamatan', 'totalSaldo', 'totalTransaksi', 'lastExport', 'enstoragePing', 'batasArsip'
        ));
    }

    /**
     * Halaman Export Data
     */
    public function export()
    {
        $batasArsip  = (int) config('app.arsip_batas_tahun', now()->year - 2);
        $tahunList   = range(2018, $batasArsip - 1);
        $kecamatanList = Kecamatan::orderBy('id')->get(['id', 'nama_kec']);
        $totalKecamatan = $kecamatanList->count();
        $enstoragePing = $this->enstorage->ping();

        $recentLogs = collect();

        return view('exports.export-data', compact(
            'tahunList', 'kecamatanList', 'totalKecamatan', 'enstoragePing', 'batasArsip', 'recentLogs'
        ));
    }

    /**
     * Jalankan export via AJAX dari UI
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
