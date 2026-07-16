<?php

// config/enstorage.php

return [

    /*
    |--------------------------------------------------------------------------
    | EnStorage API
    |--------------------------------------------------------------------------
    | Konfigurasi untuk koneksi ke EnStorage.
    | Nilai diambil dari file .env.
    */

    'url'     => env('ENSTORAGE_URL', 'https://enstorage.enpiistudio.com'),
    'api_key' => env('ENSTORAGE_API_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Struktur folder di EnStorage
    |--------------------------------------------------------------------------
    | File akan disimpan dengan struktur:
    | kecamatan_{id}/saldo_2023.json
    | kecamatan_{id}/transaksi_2023_01.json
    */
    'folder_prefix' => 'kecamatan_',

    /*
    |--------------------------------------------------------------------------
    | Timeout
    |--------------------------------------------------------------------------
    | Timeout dalam detik untuk request ke EnStorage API.
    | Upload file JSON besar mungkin butuh waktu lebih lama.
    */
    'timeout' => 60,

];
