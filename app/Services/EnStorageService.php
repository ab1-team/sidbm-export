<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

/**
 * Service untuk menyimpan file ke local storage.
 * (Sementara tidak pakai EnStorage API eksternal, biar bisa fokus test alur queue dulu)
 */
class EnStorageService
{
    private string $folderPrefix;

    public function __construct()
    {
        $this->folderPrefix = config('enstorage.folder_prefix', 'kecamatan_');
    }

    /**
     * Simpan file JSON ke local storage
     *
     * @param int    $kecamatanId  ID kecamatan — dipakai sebagai nama folder
     * @param string $filename     Nama file, misal: saldo_2023.json
     * @param array  $data         Data yang akan di-encode sebagai JSON
     *
     * @return array{success: bool, url: string|null, size: int|null, message: string, file_id: string|null}
     */
    public function upload(int $kecamatanId, string $filename, array $data): array
    {
        try {
            $jsonContent = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            $fileSize    = strlen($jsonContent);

            $folderPath = "exports/{$this->folderPrefix}{$kecamatanId}";
            $fullPath   = $folderPath . '/' . $filename;
            $disk       = Storage::disk('local');

            if (!$disk->exists($folderPath)) {
                $disk->makeDirectory($folderPath);
            }

            $result = $disk->put($fullPath, $jsonContent);

            if (!$result) {
                return [
                    'success' => false,
                    'url'     => null,
                    'size'    => null,
                    'file_id' => null,
                    'message' => "Gagal menyimpan file: {$fullPath}",
                ];
            }

            $url = url("/api/exports/stream/{$fullPath}");

            return [
                'success' => true,
                'url'     => $url,
                'size'    => $fileSize,
                'file_id' => null,
                'message' => 'Disimpan lokal ke: ' . $fullPath,
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'url'     => null,
                'size'    => null,
                'file_id' => null,
                'message' => 'Error: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Cek apakah "penyimpanan" siap dipakai (selalu true untuk local storage)
     */
    public function ping(): bool
    {
        return true;
    }
}