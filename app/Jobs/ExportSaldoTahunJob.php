<?php

namespace App\Jobs;

use App\Models\ExportLog;
use App\Models\Notification;
use App\Services\SaldoExportService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Bus\Batchable;
use Illuminate\Queue\Middleware\SkipIfBatchCancelled;

class ExportSaldoTahunJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 900;

    public function __construct(
    public int $kecamatanId,
    public int $tahun,
    public ?string $triggeredBy = null,
    public int $userId = 0,
) {
}

    public function middleware(): array
{
    return [new SkipIfBatchCancelled];
}

    public function handle(SaldoExportService $saldoService): void
    {
        $result = $saldoService->export(
            $this->kecamatanId,
            $this->tahun,
            $this->triggeredBy ?? 'queue'
        );

        if ($this->userId > 0) {
            $kecamatan = \App\Models\Sidbm\Kecamatan::find($this->kecamatanId);
            $kecName = $kecamatan ? $kecamatan->nama_kec : 'Kecamatan ' . $this->kecamatanId;

            if ($result['success']) {
                Notification::exportSuccess(
                    $this->userId,
                    'saldo',
                    $result['filename'] ?? "Saldo {$kecName} {$this->tahun}"
                );
            } else {
                Notification::exportFailed(
                    $this->userId,
                    'saldo',
                    $result['message'] ?? 'Export saldo gagal'
                );
            }
        }
    }
}
