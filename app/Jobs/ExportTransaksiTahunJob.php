<?php

namespace App\Jobs;

use App\Models\ExportLog;
use App\Services\TransaksiExportService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Bus\Batchable;
use Illuminate\Queue\Middleware\SkipIfBatchCancelled;

class ExportTransaksiTahunJob implements ShouldQueue
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

    public function handle(TransaksiExportService $transaksiService): void
    {
        $transaksiService->exportTahun(
            $this->kecamatanId,
            $this->tahun,
            $this->triggeredBy ?? 'queue'
        );
    }
}
