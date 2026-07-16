<?php

// app/Services/EnStorageService.php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;

/**
 * Service untuk berkomunikasi dengan EnStorage API.
 *
 * KONSEP Service Class:
 * Daripada menulis logika HTTP request langsung di Command atau Controller,
 * kita pisahkan ke Service class. Keuntungannya:
 * - Bisa dipakai dari mana saja (Command, Controller, Job)
 * - Mudah di-test
 * - Perubahan API EnStorage cukup di satu tempat
 */
class EnStorageService
{
    private string $baseUrl;
    private string $apiKey;
    private string $folderPrefix;
    private int    $timeout;

    public function __construct()
    {
        $this->baseUrl      = config('enstorage.url');
        $this->apiKey       = config('enstorage.api_key');
        $this->folderPrefix = config('enstorage.folder_prefix', 'kecamatan_');
        $this->timeout      = config('enstorage.timeout', 60);
    }

    /**
     * Upload file JSON ke EnStorage
     *
     * @param int    $kecamatanId  ID kecamatan — dipakai sebagai nama folder
     * @param string $filename     Nama file, misal: saldo_2023.json
     * @param array  $data         Data yang akan di-encode sebagai JSON
     *
     * @return array{success: bool, url: string|null, size: int|null, message: string}
     */
    public function upload(int $kecamatanId, string $filename, array $data): array
    {
        try {
            // Encode data ke JSON
            $jsonContent = json_encode($data, JSON_UNESCAPED_UNICODE);
            $fileSize    = strlen($jsonContent);

            // Buat folder path: kecamatan_1/saldo_2023.json
            $folderPath = $this->folderPrefix . $kecamatanId;

            // Kirim ke EnStorage API
            $response = Http::timeout($this->timeout)
                ->withToken($this->apiKey)
                ->attach('file', $jsonContent, $filename, ['Content-Type' => 'application/json'])
                ->post("{$this->baseUrl}/api/v1/upload", [
                    'folder'  => $folderPath,
                ]);

            if ($response->successful()) {
                $body = $response->json();
                return [
                    'success' => true,
                    'url'     => $body['data']['url'] ?? null,
                    'size'    => $fileSize,
                    'message' => 'Upload berhasil',
                ];
            }

            return [
                'success' => false,
                'url'     => null,
                'size'    => null,
                'message' => 'Upload gagal: ' . $response->status() . ' ' . $response->body(),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'url'     => null,
                'size'    => null,
                'message' => 'Error: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Cek apakah koneksi ke EnStorage berhasil
     * Dipakai di UI untuk validasi konfigurasi
     */
    public function ping(): bool
    {
        try {
            $response = Http::timeout(10)
                ->withToken($this->apiKey)
                ->get("{$this->baseUrl}/api/v1/ping");

            return $response->successful();
        } catch (\Exception) {
            return false;
        }
    }
}
