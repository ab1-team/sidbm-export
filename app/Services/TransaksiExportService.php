<?php

// app/Services/TransaksiExportService.php

namespace App\Services;

use App\Models\Sidbm\TransaksiModel;
use App\Models\ExportLog;

/**
 * Service untuk mengeksport data transaksi ke JSON dan upload ke EnStorage.
 *
 * Format output transaksi:
 * - 1 file per tahun per kecamatan
 * - Nama file: transaksi_2023.json
 * - Isi: flat array semua transaksi tahun tersebut
 */
class TransaksiExportService
{
    public function __construct(
        private EnStorageService $enstorage
    ) {}

    /**
     * Export transaksi satu tahun untuk satu kecamatan.
     * Langsung 1 file: transaksi_{tahun}.json (flat array, tidak ada file bulanan).
     *
     * @return array{success: int, failed: int, results: array}
     */
    public function exportTahun(int $kecamatanId, int $tahun, string $triggeredBy = 'manual'): array
    {
        $filename = "transaksi_{$tahun}.json";

        if (ExportLog::where('kecamatan_id', $kecamatanId)
            ->where('jenis', 'transaksi')
            ->where('tahun', $tahun)
            ->whereIn('status', ['processing', 'pending'])
            ->exists()) {
            return [
                'success' => 0,
                'failed'  => 1,
                'results' => [['success' => false, 'message' => "Export transaksi {$tahun} untuk kecamatan {$kecamatanId} sedang berjalan atau pending.", 'log_id' => null]],
            ];
        }

        $log = ExportLog::create([
            'kecamatan_id' => $kecamatanId,
            'jenis'        => 'transaksi',
            'tahun'        => $tahun,
            'bulan'        => null,
            'filename'     => $filename,
            'status'       => 'processing',
            'triggered_by' => $triggeredBy,
        ]);

        try {
            $model = new TransaksiModel($kecamatanId);
            $rows  = $model->newQuery()
                ->whereYear('tgl_transaksi', $tahun)
                ->whereNull('deleted_at')
                ->orderBy('tgl_transaksi')
                ->orderBy('idt')
                ->get([
                    'idt', 'tgl_transaksi', 'rekening_debit', 'rekening_kredit',
                    'idtp', 'id_pinj', 'id_pinj_i', 'keterangan_transaksi',
                    'relasi', 'jumlah', 'urutan', 'id_user',
                    'created_at', 'updated_at',
                ]);

            $grouped = [];
            $idx = 1;

            foreach ($rows as $row) {
                $key = (string) $idx++;

                $grouped[$key] = [
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
                ];
            }

            $result = $this->enstorage->upload($kecamatanId, $filename, $grouped);

            if (!$result['success']) {
                $log->update([
                    'status'        => 'failed',
                    'error_message' => $result['message'],
                ]);

                return [
                    'success' => 0,
                    'failed'  => 1,
                    'results' => [['success' => false, 'message' => $result['message'], 'log_id' => $log->id]],
                ];
            }

            $totalRecords = array_sum(array_map('count', $grouped));

            $log->update([
                'status'    => 'success',
                'file_url'  => $result['url'] ?? null,
                'file_size' => $result['size'],
                'record_count' => $totalRecords,
            ]);

            return [
                'success' => 1,
                'failed'  => 0,
                'results' => [['success' => true, 'message' => "Berhasil export " . $totalRecords . " transaksi", 'log_id' => $log->id]],
            ];

        } catch (\Exception $e) {
            $log->update([
                'status'        => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            return [
                'success' => 0,
                'failed'  => 1,
                'results' => [['success' => false, 'message' => 'Error: ' . $e->getMessage(), 'log_id' => $log->id]],
            ];
        }
    }

    /**
     * Export transaksi satu bulan untuk satu kecamatan (legacy, untuk backward compat).
     *
     * @return array{success: bool, message: string, log_id: int|null}
     */
    public function exportBulan(int $kecamatanId, int $tahun, int $bulan, string $triggeredBy = 'manual'): array
    {
        $bulanPadded = str_pad($bulan, 2, '0', STR_PAD_LEFT);
        $filename    = "transaksi_{$tahun}_{$bulanPadded}.json";

        $log = ExportLog::create([
            'kecamatan_id' => $kecamatanId,
            'jenis'        => 'transaksi',
            'tahun'        => $tahun,
            'bulan'        => $bulan,
            'filename'     => $filename,
            'status'       => 'pending',
            'triggered_by' => $triggeredBy,
        ]);

        $log->update(['status' => 'processing']);

        try {
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

            $log->update([
                'status'       => 'success',
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
}
