<?php

// app/Services/TransaksiExportService.php

namespace App\Services;

use App\Models\Sidbm\TransaksiModel;
use App\Models\ExportLog;

/**
 * Service untuk mengeksport data transaksi ke JSON dan upload ke EnStorage.
 *
 * Format output transaksi:
 * - 1 file per bulan per tahun per kecamatan
 * - Nama file: transaksi_2023_01.json
 * - Isi: array transaksi bulan tersebut
 *
 * Contoh: transaksi_2023_06.json berisi 100 transaksi Juni 2023
 */
class TransaksiExportService
{
    public function __construct(
        private EnStorageService $enstorage
    ) {}

    /**
     * Export transaksi satu bulan untuk satu kecamatan
     *
     * @param int    $kecamatanId
     * @param int    $tahun
     * @param int    $bulan        1-12
     * @param string $triggeredBy
     * @return array{success: bool, message: string, log_id: int|null}
     */
    public function exportBulan(int $kecamatanId, int $tahun, int $bulan, string $triggeredBy = 'manual'): array
    {
        $bulanPadded = str_pad($bulan, 2, '0', STR_PAD_LEFT);
        $filename    = "transaksi_{$tahun}_{$bulanPadded}.json";

        // Buat log pending
        $log = ExportLog::create([
            'kecamatan_id' => $kecamatanId,
            'jenis'        => 'transaksi',
            'tahun'        => $tahun,
            'bulan'        => $bulan,
            'filename'     => $filename,
            'status'       => 'pending',
            'triggered_by' => $triggeredBy,
        ]);

        $log->update([
    'status' => 'processing',
]);

        try {
            // ── STEP 1: Query transaksi bulan ini ─────────────────
            $model = new TransaksiModel($kecamatanId);
            $rows  = $model->newQuery()
                ->whereYear('tgl_transaksi', $tahun)
                ->whereMonth('tgl_transaksi', $bulan)
                ->whereNull('deleted_at')
                ->orderBy('tgl_transaksi')
                ->orderBy('idt')
                ->get([
                    'idt', 'tgl_transaksi', 'rekening_debit', 'rekening_kredit',
                    'idtp', 'id_pinj', 'id_pinj_i', 'keterangan_transaksi',
                    'relasi', 'jumlah', 'urutan', 'id_user',
                    'created_at', 'updated_at',
                ]);

            if ($rows->isEmpty()) {

    $this->enstorage->upload($kecamatanId, $filename, []);

    $log->update([
        'status'       => 'success',
        'record_count' => 0,
        'file_size'    => 2,
    ]);

    return [
        'success' => true,
        'message' => "Tidak ada transaksi",
        'log_id'  => $log->id,
    ];
}

            // ── STEP 2: Mapping & cast tipe data ──────────────────
            // Cast penting agar JSON menghasilkan tipe yang benar
            // (angka sebagai number, bukan string)
            $transaksi = $rows->map(fn($row) => [
                'idt'                  => (int)   $row->idt,
                'tgl_transaksi'        =>          $row->tgl_transaksi,
                'rekening_debit'       =>          $row->rekening_debit,
                'rekening_kredit'      =>          $row->rekening_kredit,
                'idtp'                 => (int)   $row->idtp,
                'id_pinj'              => (int)   $row->id_pinj,
                'id_pinj_i'            => (int)   $row->id_pinj_i,
                'keterangan_transaksi' =>          $row->keterangan_transaksi,
                'relasi'               =>          $row->relasi,
                'jumlah'               => (float) $row->jumlah,
                'urutan'               => (int)   $row->urutan,
                'id_user'              => (int)   $row->id_user,
                'created_at'           =>          $row->created_at,
                'updated_at'           =>          $row->updated_at,
            ])->toArray();

            // ── STEP 3: Upload ke EnStorage ───────────────────────
            $result = $this->enstorage->upload($kecamatanId, $filename, $transaksi);

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

            // ── STEP 4: Update log ────────────────────────────────
            $log->update([
                'status'       => 'success',
                'file_id'      => $result['file_id'] ?? null,
                'file_url'     => $result['url'] ?? null,
                'file_size'    => $result['size'],
                'record_count' => count($transaksi),
            ]);

            return [
                'success' => true,
                'message' => "Berhasil export " . count($transaksi) . " transaksi",
                'log_id'  => $log->id,
            ];

        } catch (\Exception $e) {
            $log->update([
                'status'        => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'log_id'  => $log->id,
            ];
        }
    }

    /**
     * Export semua bulan dalam satu tahun untuk satu kecamatan
     *
     * @return array{success: int, failed: int, results: array}
     */
    public function exportTahun(int $kecamatanId, int $tahun, string $triggeredBy = 'manual'): array
    {
        $success = 0;
        $failed  = 0;
        $results = [];

        for ($bulan = 1; $bulan <= 12; $bulan++) {
            $result = $this->exportBulan($kecamatanId, $tahun, $bulan, $triggeredBy);
            $results[] = array_merge($result, ['bulan' => $bulan]);

            $result['success'] ? $success++ : $failed++;
        }
        // Jika ada minimal satu file yang berhasil diexport,
// gabungkan menjadi transaksi_2023.json
if ($success > 0) {
    $this->mergeTahun($kecamatanId, $tahun);
}
        return compact('success', 'failed', 'results');
    }

    /**
 * Menggabungkan seluruh file transaksi bulanan menjadi satu file tahunan.
 */
private function mergeTahun(int $kecamatanId, int $tahun): void
{
    $folder = storage_path("app/private/kecamatan_{$kecamatanId}");

    $hasil = [];

    for ($bulan = 1; $bulan <= 12; $bulan++) {

        $bulanKey = sprintf("%02d", $bulan);

        $file = "{$folder}/transaksi_{$tahun}_{$bulanKey}.json";

        if (file_exists($file)) {

            $json = json_decode(file_get_contents($file), true);

            $hasil[$bulanKey] = is_array($json) ? $json : [];

        } else {

            // Kalau file tidak ada, tetap buat array kosong
            $hasil[$bulanKey] = [];
        }
    }

    file_put_contents(
        "{$folder}/transaksi_{$tahun}.json",
        json_encode(
            $hasil,
            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        )
    );

    // Hapus file bulanan
    for ($bulan = 1; $bulan <= 12; $bulan++) {

        $bulanKey = sprintf("%02d", $bulan);

        $file = "{$folder}/transaksi_{$tahun}_{$bulanKey}.json";

        if (file_exists($file)) {
            unlink($file);
        }
    }
}
}
