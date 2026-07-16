<?php

// config/database.php
// Hanya bagian yang relevan — merge dengan config default Laravel

return [

    'default' => env('DB_CONNECTION', 'mysql'),

    'connections' => [

        // ── Koneksi 1: Database lokal sidbm-export ──────────────
        // Dipakai untuk menyimpan metadata export (tabel export_logs)
        'mysql' => [
            'driver'    => 'mysql',
            'host'      => env('DB_HOST', '127.0.0.1'),
            'port'      => env('DB_PORT', '3306'),
            'database'  => env('DB_DATABASE', 'sidbm_export'),
            'username'  => env('DB_USERNAME', 'root'),
            'password'  => env('DB_PASSWORD', ''),
            'charset'   => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix'    => '',
            'strict'    => true,
            'engine'    => null,
        ],

        // ── Koneksi 2: Database SIDBM utama ─────────────────────
        // Dipakai untuk membaca saldo_{id} dan transaksi_{id}
        // Koneksi ini READ ONLY — tidak boleh ada write/delete ke sini
        'sidbm' => [
            'driver'    => 'mysql',
            'host'      => env('SIDBM_DB_HOST', '127.0.0.1'),
            'port'      => env('SIDBM_DB_PORT', '3306'),
            'database'  => env('SIDBM_DB_DATABASE', 'sidbm'),
            'username'  => env('SIDBM_DB_USERNAME', 'root'),
            'password'  => env('SIDBM_DB_PASSWORD', ''),
            'charset'   => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix'    => '',
            'strict'    => true,
            'engine'    => null,
        ],

    ],

    'migrations' => [
        'table'  => 'migrations',
        'update_date_on_publish' => true,
    ],

];
