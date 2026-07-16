<?php

// app/Services/SaldoExportService.php

namespace App\Services;

use App\Models\Sidbm\SaldoModel;
use App\Models\ExportLog;

/**
 * Service untuk mengeksport data saldo ke JSON dan upload ke EnStorage.
 *
 * Flow:
 * 1. Query saldo_{kecamatanId} dari DB SIDBM untuk tahun tertentu
 * 2. Transformasi dari format tall → wide
 * 3. Upload ke EnStorage via EnStorageService
 * 4. Simpan metadata ke tabel export_logs
 */
class SaldoExportService
{
    public function __construct(
        private EnStorageService $enstorage
    ) {}

    /**
     * Export saldo satu kecamatan untuk satu tahun
     *
     * @param int    $kecamatanId
     * @param int    $tahun
     * @param string $triggeredBy  'manual' | 'scheduler' | nama user
     * @return array{success: bool, message: string, log_id: int|null}
     */
    public function export(int $kecamatanId, int $tahun, string $triggeredBy = 'manual'): array
    {
        // Buat log dengan status pending dulu
        $log = ExportLog::create([
            'kecamatan_id' => $kecamatanId,
            'jenis'        => 'saldo',
            'tahun'        => $tahun,
            'bulan'        => null,
            'filename'     => "saldo_{$tahun}.json",
            'status'       => 'pending',
            'triggered_by' => $triggeredBy,
        ]);

        try {
            // ── STEP 1: Query data dari DB SIDBM ──────────────────
            $model = new SaldoModel($kecamatanId);
            $rows  = $model->newQuery()
                ->where('tahun', $tahun)
                ->orderBy('kode_akun')
                ->orderBy('bulan')
                ->get(['kode_akun', 'bulan', 'debit', 'kredit']);

            if ($rows->isEmpty()) {
                $log->update([
                    'status'        => 'failed',
                    'error_message' => "Tidak ada data saldo untuk kecamatan {$kecamatanId} tahun {$tahun}",
                ]);

                return [
                    'success' => false,
                    'message' => "Tidak ada data saldo untuk tahun {$tahun}",
                    'log_id'  => $log->id,
                ];
            }

            // ── STEP 2: Transformasi Tall → Wide ──────────────────
            // Dari: banyak baris per akun per bulan
            // Ke  : 1 object per akun, semua bulan jadi kolom
            $saldoWide = [];

            foreach ($rows as $row) {
                $kodeAkun = $row->kode_akun;
                $bulan    = (int) $row->bulan;
                $bulanKey = str_pad($bulan, 2, '0', STR_PAD_LEFT);

                // Inisialisasi struktur akun jika belum ada
                if (!isset($saldoWide[$kodeAkun])) {
                    $saldoWide[$kodeAkun] = ['kode_akun' => $kodeAkun, 'tahun' => $tahun];

                    // Inisialisasi semua bulan dengan 0
                    for ($b = 0; $b <= 12; $b++) {
                        $bk = str_pad($b, 2, '0', STR_PAD_LEFT);
                        $saldoWide[$kodeAkun]["debit_{$bk}"]  = 0;
                        $saldoWide[$kodeAkun]["kredit_{$bk}"] = 0;
                    }
                }

                // Isi nilai bulan yang sesuai
                $saldoWide[$kodeAkun]["debit_{$bulanKey}"]  = (float) ($row->debit  ?? 0);
                $saldoWide[$kodeAkun]["kredit_{$bulanKey}"] = (float) ($row->kredit ?? 0);
            }

            $output = array_values($saldoWide);

            // ── STEP 3: Upload ke EnStorage ───────────────────────
            $filename = "saldo_{$tahun}.json";
            $result   = $this->enstorage->upload($kecamatanId, $filename, $output);

            if (!$result['success']) {
                $log->update([
                    'status'        => 'failed',
                    'error_message' => $result['message'],
                ]);

                return [
                    'success' => false,
                    'message' => $result['message'],
                    'log_id'  => $log->id,
                ];
            }

            // ── STEP 4: Update log dengan status success ──────────
            $log->update([
                'status'       => 'success',
                'file_url'     => $result['url'],
                'file_size'    => $result['size'],
                'record_count' => count($output),
            ]);

            return [
                'success' => true,
                'message' => "Berhasil export {$kodeAkun} akun",
                'log_id'  => $log->id,
            ];

        } catch (\Exception $e) {
            $log->update([
                'status'        => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Error tidak terduga: ' . $e->getMessage(),
                'log_id'  => $log->id,
            ];
        }
    }
}
