<?php

// app/Models/Sidbm/Kecamatan.php

namespace App\Models\Sidbm;

use Illuminate\Database\Eloquent\Model;

/**
 * Model untuk tabel kecamatan di DB SIDBM.
 * Dipakai untuk mendapatkan daftar semua client yang valid.
 */
class Kecamatan extends Model
{
    protected $connection = 'sidbm';
    protected $table      = 'kecamatan';
    public $timestamps    = false;

    /**
     * Ambil semua kecamatan yang aktif, hanya kolom yang dibutuhkan.
     * Dipakai saat export semua client sekaligus.
     */
    public static function getAllIds(): array
    {
        return static::pluck('id')->toArray();
    }
}
