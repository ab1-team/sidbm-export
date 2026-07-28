<?php

namespace App\Http\Controllers\Api;

use App\Models\Sidbm\Kecamatan;
use Illuminate\Support\Facades\Bus;
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

    $batch = Bus::batch([
        new ExportSaldoTahunJob(
            (int) $request->kecamatan_id,
            (int) $request->tahun,
            auth()->user()?->name ?? 'api'
        ),
    ])
    ->name('export-saldo-' . now()->format('YmdHis'))
    ->onQueue('export')
    ->allowFailures()
    ->dispatch();

    return response()->json([
        'success'    => true,
        'message'    => 'Export saldo berjalan di background.',
        'batch_id'   => $batch->id,
        'total_jobs' => 1,
    ]);
}
   public function transaksi(Request $request)
{
    $request->validate([
        'kecamatan_id' => 'required|integer|min:1',
        'tahun'        => 'required|integer|min:2018',
    ]);

    $batch = Bus::batch([
        new ExportTransaksiTahunJob(
            (int) $request->kecamatan_id,
            (int) $request->tahun,
            auth()->user()?->name ?? 'api'
        ),
    ])
    ->name('export-transaksi-' . now()->format('YmdHis'))
    ->onQueue('export')
    ->allowFailures()
    ->dispatch();

    return response()->json([
        'success'    => true,
        'message'    => 'Export transaksi berjalan di background.',
        'batch_id'   => $batch->id,
        'total_jobs' => 1,
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
        $jobs[] = new ExportSaldoTahunJob(
            $kec->id,
            $tahun,
            $user
        );
    }

    if ($jenis === 'transaksi' || $jenis === 'semua') {
        $jobs[] = new ExportTransaksiTahunJob(
            $kec->id,
            $tahun,
            $user
        );
    }
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