<?php

// app/Models/Sidbm/TransaksiModel.php

namespace App\Models\Sidbm;

use Illuminate\Database\Eloquent\Model;

/**
 * Model dinamis untuk membaca tabel transaksi_{id} dari DB SIDBM.
 *
 * Penggunaan:
 *   $model = new TransaksiModel(1);    // → tabel transaksi_1
 *   $data  = $model->newQuery()
 *               ->whereYear('tgl_transaksi', 2023)
 *               ->whereMonth('tgl_transaksi', 1)
 *               ->get();
 */
class TransaksiModel extends Model
{
    protected $connection = 'sidbm';
    public $timestamps    = false;

    public function __construct(int $kecamatanId = 1, array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setTable('transaksi_' . $kecamatanId);
    }
}
