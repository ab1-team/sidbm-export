<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class EnStorageService
{
    private string $baseUrl;
    private string $apiKey;
    private string $folderPrefix;
    private int $timeout;

    public function __construct()
    {
        $this->baseUrl      = config('enstorage.url');
        $this->apiKey       = config('enstorage.api_key');
        $this->folderPrefix = config('enstorage.folder_prefix', 'kecamatan_');
        $this->timeout      = config('enstorage.timeout', 60);
    }

    /**
     * Upload file JSON ke EnStorage.
     *
     * Flow:
     * 1. Buat folder {prefix}{kecamatanId} di root (cache UUID)
     * 2. Multipart upload ke /api/v1/files/upload
     *
     * @param int    $kecamatanId  ID kecamatan — dipakai sebagai nama folder
     * @param string $filename     Nama file, misal: saldo_2023.json
     * @param array  $data         Data yang di-encode sebagai JSON
     * @return array{success: bool, url: string|null, size: int|null, message: string, file_id: string|null}
     */
    public function upload(int $kecamatanId, string $filename, array $data): array
    {
        try {
            $jsonContent = json_encode($data, JSON_UNESCAPED_UNICODE);
            $fileSize    = strlen($jsonContent);
            $folderName  = $this->folderPrefix . $kecamatanId;

            $folderId = $this->getOrCreateFolder($folderName);
            if (!$folderId) {
                return [
                    'success'  => false,
                    'url'      => null,
                    'size'     => null,
                    'file_id'  => null,
                    'message'  => 'Gagal membuat folder di EnStorage.',
                ];
            }

            Log::debug('[EnStorage] Uploading', [
                'folder_id'  => $folderId,
                'filename'   => $filename,
                'size'       => $fileSize,
            ]);

            $response = Http::timeout($this->timeout)
                ->acceptJson()
                ->attach('file[]', $jsonContent, $filename)
                ->post("{$this->baseUrl}/api/v1/files/upload", [
                    'folder_id' => $folderId,
                    'shareable' => false,
                    'client_key' => "sidbm_" . uniqid(),
                ]);

            $body = $response->json();

            Log::debug('[EnStorage] Upload response', [
                'status'     => $response->status(),
                'body'       => $body,
            ]);

            if ($response->status() === 429) {
                return [
                    'success'  => false,
                    'url'      => null,
                    'size'     => null,
                    'file_id'  => null,
                    'message'  => 'Rate limit tercapai. Coba beberapa menit lagi.',
                ];
            }

            if ($response->status() === 409) {
                return [
                    'success'  => false,
                    'url'      => null,
                    'size'     => null,
                    'file_id'  => null,
                    'message'  => $body['message'] ?? 'Duplicate client_key atau konflik folder.',
                ];
            }

            if ($response->status() === 503) {
                return [
                    'success'  => false,
                    'url'      => null,
                    'size'     => null,
                    'file_id'  => null,
                    'message'  => 'EnStorage belum dikonfigurasi (OAuth/Google Account belum terhubung).',
                ];
            }

            if (!($body['success'] ?? false)) {
                return [
                    'success'  => false,
                    'url'      => null,
                    'size'     => null,
                    'file_id'  => null,
                    'message'  => $body['message'] ?? 'Upload gagal.',
                ];
            }

            $accepted  = $body['data']['accepted']  ?? [];
            $rejected  = $body['data']['rejected']  ?? [];
            $fileData  = $accepted[0] ?? null;

            if (!$fileData) {
                $rejectReason = $rejected[0]['reason'] ?? 'Unknown';
                $rejectName  = $rejected[0]['name']  ?? $filename;
                return [
                    'success'  => false,
                    'url'      => null,
                    'size'     => null,
                    'file_id'  => null,
                    'message'  => "File {$rejectName} ditolak: {$rejectReason}",
                ];
            }

            return [
                'success'  => true,
                'url'      => $fileData['share_url'] ?? null,
                'size'     => $fileSize,
                'file_id'  => $fileData['file_id'] ?? null,
                'message'  => $body['message'] ?? 'Upload berhasil.',
            ];
        } catch (\Exception $e) {
            Log::error('[EnStorage] Upload exception', ['error' => $e->getMessage()]);
            return [
                'success'  => false,
                'url'      => null,
                'size'     => null,
                'file_id'  => null,
                'message'  => $e->getMessage(),
            ];
        }
    }

    /**
     * Cek apakah koneksi ke EnStorage berhasil.
     */
    public function ping(): bool
    {
        try {
            $response = Http::timeout(10)
                ->withToken($this->apiKey)
                ->get("{$this->baseUrl}/api/v1/google-accounts");

            return $response->successful();
        } catch (\Exception) {
            return false;
        }
    }

    /**
     * Buat folder jika belum ada, atauAMBIL UUID yang sudah ada.
     *
     * @param string $name      Nama folder (misal: kecamatan_1)
     * @param string|null $parentId  UUID parent (null = root)
     * @return string|null UUID folder, atau null jika gagal
     */
    private function getOrCreateFolder(string $name, ?string $parentId = null): ?string
    {
        $cacheKey = "enstorage_folder:{$name}:" . ($parentId ?? 'root');

        return Cache::remember($cacheKey, 3600, function () use ($name, $parentId) {
            $existing = $this->findFolderByName($name, $parentId);
            if ($existing) {
                return $existing;
            }

            return $this->createFolder($name, $parentId);
        });
    }

    /**
     * Cari folder by name di root (parent_id=null).
     *
     * @return string|null UUID folder atau null
     */
    private function findFolderByName(string $name, ?string $parentId = null): ?string
    {
        try {
            $params = ['search' => $name, 'per_page' => 100];

            $response = Http::timeout($this->timeout)
                ->withToken($this->apiKey)
                ->get("{$this->baseUrl}/api/v1/folders", $params);

            if (!$response->successful()) {
                return null;
            }

            $body = $response->json();
            $items = $body['data'] ?? [];

            foreach ($items as $item) {
                if (($item['name'] ?? '') === $name && ($item['parent_id'] ?? null) === $parentId) {
                    return $item['id'];
                }
            }

            return null;
        } catch (\Exception) {
            return null;
        }
    }

    /**
     * Buat folder baru.
     *
     * @return string|null UUID folder atau null
     */
    private function createFolder(string $name, ?string $parentId = null): ?string
    {
        try {
            $payload = ['name' => $name];
            if ($parentId !== null) {
                $payload['parent_id'] = $parentId;
            }

            $response = Http::timeout($this->timeout)
                ->withToken($this->apiKey)
                ->post("{$this->baseUrl}/api/v1/folders", $payload);

            if (!$response->successful()) {
                $status = $response->status();
                $body   = $response->json();

                Log::warning('[EnStorage] Create folder failed', [
                    'name'   => $name,
                    'status' => $status,
                    'body'   => $body,
                ]);

                if ($status === 409) {
                    return $this->findFolderByName($name, $parentId);
                }

                if ($status === 422) {
                    Log::error('[EnStorage] Folder validation error', ['body' => $body]);
                }

                return null;
            }

            $body = $response->json();
            $id   = $body['data']['id'] ?? null;

            if ($id) {
                Log::info('[EnStorage] Folder created', ['name' => $name, 'id' => $id]);
            }

            return $id;
        } catch (\Exception $e) {
            Log::error('[EnStorage] Create folder exception', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Hapus cache folder (panggil jika folder perlu di-reset).
     */
    public function clearFolderCache(int $kecamatanId, ?string $parentId = null): void
    {
        $name = $this->folderPrefix . $kecamatanId;
        $cacheKey = "enstorage_folder:{$name}:" . ($parentId ?? 'root');
        Cache::forget($cacheKey);
    }
}
