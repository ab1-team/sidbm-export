<?php

namespace App\Jobs;

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
) {
}

    public function middleware(): array
{
    return [new SkipIfBatchCancelled];
}

    public function handle(SaldoExportService $saldoService): void
    {
        $saldoService->export(
            $this->kecamatanId,
            $this->tahun,
            $this->triggeredBy ?? 'queue'
        );
    }
}