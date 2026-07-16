<?php

// app/Models/Sidbm/SaldoModel.php

namespace App\Models\Sidbm;

use Illuminate\Database\Eloquent\Model;

/**
 * Model dinamis untuk membaca tabel saldo_{id} dari DB SIDBM.
 *
 * KONSEP: Karena setiap kecamatan punya tabel berbeda (saldo_1, saldo_2, dst),
 * kita tidak bisa pakai model statis. Model ini bisa diset nama tabelnya
 * secara runtime sebelum dipakai.
 *
 * Penggunaan:
 *   $model = new SaldoModel(1);        // → tabel saldo_1
 *   $model = new SaldoModel(42);       // → tabel saldo_42
 *   $data  = $model->newQuery()->get();
 */
class SaldoModel extends Model
{
    // Gunakan koneksi SIDBM, bukan koneksi default
    protected $connection = 'sidbm';

    // Tidak pakai timestamps (created_at, updated_at)
    public $timestamps = false;

    public function __construct(int $kecamatanId = 1, array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setTable('saldo_' . $kecamatanId);
    }
}
