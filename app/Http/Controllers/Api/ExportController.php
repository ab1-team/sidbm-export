<?php

namespace App\Http\Controllers\Api;

use App\Jobs\ExportKecamatanTahunJob;
use App\Models\Sidbm\Kecamatan;
use Illuminate\Support\Facades\Bus;
use App\Http\Controllers\Controller;
use App\Services\SaldoExportService;
use App\Services\TransaksiExportService;
use Illuminate\Http\Request;

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
        'success' => $result['success'] ?? false,
        'message' => $result['message'] ?? 'Export selesai',
        'data'    => $result,
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
        'success' => $result['success'] ?? false,
        'message' => $result['message'] ?? 'Export selesai',
        'data'    => $result,
    ]);
}
    public function semua(Request $request)
{
    $request->validate([
        'kecamatan_id' => 'required|integer|min:1',
        'tahun'        => 'required|integer|min:2018',
    ]);

    $kecamatanId = (int) $request->kecamatan_id;
    $tahun       = (int) $request->tahun;
    $user        = auth()->user()?->name ?? 'api';

    $saldo = $this->saldoService->export(
        $kecamatanId,
        $tahun,
        $user
    );

    $transaksi = $this->transaksiService->exportTahun(
        $kecamatanId,
        $tahun,
        $user
    );

    $success = ($saldo['success'] ?? false) && ($transaksi['success'] ?? false);

    return response()->json([
        'success' => $success,
        'message' => $success
            ? 'Export saldo dan transaksi berhasil.'
            : 'Export selesai dengan beberapa kendala.',
        'data' => [
            'saldo'      => $saldo,
            'transaksi'  => $transaksi,
        ],
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
            $jobs[] = new ExportKecamatanTahunJob(
                $kec->id,
                $tahun,
                $jenis,
                $user
            );
        }
    }

    $batch = Bus::batch($jobs)
        ->name('export-' . now()->format('YmdHis'))
        ->onQueue('export')
        ->allowFailures()
        ->dispatch();

    return response()->json([
        'success'    => true,
        'message'    => 'Export berjalan di background.',
        'batch_id'   => $batch->id,
        'total_jobs' => count($jobs),
    ]);
}
}