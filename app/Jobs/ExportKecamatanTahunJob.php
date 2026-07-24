<?php

// app/Jobs/ExportKecamatanTahunJob.php

namespace App\Jobs;

use App\Services\SaldoExportService;
use App\Services\TransaksiExportService;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\SkipIfBatchCancelled;
use Illuminate\Queue\SerializesModels;
use Throwable;

class ExportKecamatanTahunJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 900; // 15 menit per kombinasi, sesuaikan kalau perlu
    public $tries   = 3;

    public function __construct(
        public int $kecamatanId,
        public int $tahun,
        public string $jenis,       // saldo|transaksi|semua
        public ?string $triggeredBy = null,
    ) {
        $this->onQueue('export');
    }

    public function middleware(): array
    {
        // Kalau batch di-cancel, job yang belum diproses otomatis di-skip
        return [new SkipIfBatchCancelled];
    }

    public function handle(
    SaldoExportService $saldoService,
    TransaksiExportService $transaksiService
): void {

    if ($this->batch()?->cancelled()) {
        return;
    }

    $user = $this->triggeredBy ?? 'queue';

    if (in_array($this->jenis, ['saldo', 'semua'])) {
        $saldoService->export(
            $this->kecamatanId,
            $this->tahun,
            $user
        );
    }

    if (in_array($this->jenis, ['transaksi', 'semua'])) {
        $transaksiService->exportTahun(
            $this->kecamatanId,
            $this->tahun,
            $user
        );
    }
}
    public function failed(Throwable $exception): void
    {
        report($exception);
    }
}