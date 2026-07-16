<?php

// app/Console/Commands/ExportArsip.php

namespace App\Console\Commands;

use App\Models\Sidbm\Kecamatan;
use App\Services\SaldoExportService;
use App\Services\TransaksiExportService;
use Illuminate\Console\Command;

/**
 * Artisan command untuk menjalankan export arsip data SIDBM.
 *
 * Cara penggunaan:
 *
 *   # Export saldo + transaksi kecamatan 1, tahun 2023
 *   php artisan export:arsip --kecamatan=1 --tahun=2023
 *
 *   # Export saldo saja
 *   php artisan export:arsip --kecamatan=1 --tahun=2023 --jenis=saldo
 *
 *   # Export transaksi saja
 *   php artisan export:arsip --kecamatan=1 --tahun=2023 --jenis=transaksi
 *
 *   # Export semua kecamatan tahun 2023
 *   php artisan export:arsip --all --tahun=2023
 *
 *   # Export semua kecamatan semua tahun yang memenuhi batas arsip
 *   php artisan export:arsip --all
 */
class ExportArsip extends Command
{
    protected $signature = 'export:arsip
                            {--kecamatan= : ID kecamatan yang akan dieksport}
                            {--tahun=     : Tahun yang akan dieksport}
                            {--jenis=     : Jenis data: saldo, transaksi, atau kosong untuk keduanya}
                            {--all        : Export semua kecamatan}';

    protected $description = 'Export data arsip SIDBM (saldo & transaksi) ke EnStorage';

    public function __construct(
        private SaldoExportService    $saldoService,
        private TransaksiExportService $transaksiService,
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $this->info('=== SIDBM Export Arsip ===');
        $this->newLine();

        // ── Tentukan daftar kecamatan ──────────────────────────
        if ($this->option('all')) {
            $kecamatanIds = Kecamatan::getAllIds();
            $this->info("Export semua kecamatan: " . count($kecamatanIds) . " kecamatan");
        } elseif ($this->option('kecamatan')) {
            $kecamatanIds = [(int) $this->option('kecamatan')];
        } else {
            $this->error('Pilih kecamatan dengan --kecamatan=ID atau --all untuk semua');
            return self::FAILURE;
        }

        // ── Tentukan tahun ─────────────────────────────────────
        $batasArsip = (int) config('app.arsip_batas_tahun', now()->year - 2);

        if ($this->option('tahun')) {
            $tahunList = [(int) $this->option('tahun')];

            if ($tahunList[0] >= $batasArsip) {
                $this->error("Tahun {$tahunList[0]} belum bisa diarsip. Batas arsip: sebelum {$batasArsip}.");
                return self::FAILURE;
            }
        } else {
            // Semua tahun yang memenuhi batas arsip
            $tahunList = range(2018, $batasArsip - 1);
            $this->info("Tahun yang akan diarsip: " . implode(', ', $tahunList));
        }

        // ── Tentukan jenis ─────────────────────────────────────
        $jenis = $this->option('jenis') ?: 'semua';
        $this->info("Jenis: {$jenis}");
        $this->newLine();

        // ── Mulai export ───────────────────────────────────────
        $totalSuccess = 0;
        $totalFailed  = 0;

        foreach ($kecamatanIds as $kecamatanId) {
            $this->line("📍 Kecamatan {$kecamatanId}");

            foreach ($tahunList as $tahun) {

                // Export Saldo
                if (in_array($jenis, ['saldo', 'semua'])) {
                    $result = $this->saldoService->export($kecamatanId, $tahun, 'artisan');

                    if ($result['success']) {
                        $this->line("  ✓ Saldo {$tahun} — {$result['message']}");
                        $totalSuccess++;
                    } else {
                        $this->warn("  ✗ Saldo {$tahun} — {$result['message']}");
                        $totalFailed++;
                    }
                }

                // Export Transaksi
                if (in_array($jenis, ['transaksi', 'semua'])) {
                    $transaksiResult = $this->transaksiService->exportTahun($kecamatanId, $tahun, 'artisan');

                    $this->line(
                        "  ✓ Transaksi {$tahun} — " .
                        "{$transaksiResult['success']} bulan berhasil, " .
                        "{$transaksiResult['failed']} bulan dilewati"
                    );

                    $totalSuccess += $transaksiResult['success'];
                    $totalFailed  += $transaksiResult['failed'];
                }
            }

            $this->newLine();
        }

        // ── Ringkasan ──────────────────────────────────────────
        $this->info("=== Selesai ===");
        $this->info("✓ Berhasil : {$totalSuccess}");

        if ($totalFailed > 0) {
            $this->warn("✗ Gagal/Skip: {$totalFailed}");
        }

        return self::SUCCESS;
    }
}
