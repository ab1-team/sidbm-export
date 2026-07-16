<?php

// app/Models/ExportLog.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class ExportLog extends Model
{
    protected $fillable = [
        'kecamatan_id',
        'jenis',
        'tahun',
        'bulan',
        'filename',
        'file_url',
        'file_size',
        'record_count',
        'status',
        'error_message',
        'triggered_by',
    ];

    protected $casts = [
        'kecamatan_id' => 'integer',
        'tahun'        => 'integer',
        'bulan'        => 'integer',
        'file_size'    => 'integer',
        'record_count' => 'integer',
    ];

    // ── Scopes ──────────────────────────────────────────────

    public function scopeSuccess(Builder $query): Builder
    {
        return $query->where('status', 'success');
    }

    public function scopeFailed(Builder $query): Builder
    {
        return $query->where('status', 'failed');
    }

    public function scopeForKecamatan(Builder $query, int $kecamatanId): Builder
    {
        return $query->where('kecamatan_id', $kecamatanId);
    }

    // ── Accessors ───────────────────────────────────────────

    /**
     * Ukuran file dalam format yang mudah dibaca
     * Contoh: 1048576 → "1.00 MB"
     */
    public function getFileSizeHumanAttribute(): string
    {
        $bytes = $this->file_size ?? 0;

        if ($bytes < 1024)       return $bytes . ' B';
        if ($bytes < 1048576)    return round($bytes / 1024, 2) . ' KB';
        if ($bytes < 1073741824) return round($bytes / 1048576, 2) . ' MB';
        return round($bytes / 1073741824, 2) . ' GB';
    }

    /**
     * Label bulan dalam bahasa Indonesia
     */
    public function getBulanLabelAttribute(): string
    {
        if (!$this->bulan) return '-';

        $bulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
            4 => 'April',   5 => 'Mei',      6 => 'Juni',
            7 => 'Juli',    8 => 'Agustus',  9 => 'September',
            10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        return $bulan[$this->bulan] ?? '-';
    }
}
