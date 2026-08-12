<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Notification extends Model
{
    use HasUuids;

    protected $fillable = [
        'type',
        'title',
        'body',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_notifications')
            ->withPivot('read_at')
            ->withTimestamps();
    }

    public function markAsReadForUser(int $userId): void
    {
        $this->users()->updateExistingPivot($userId, [
            'read_at' => now(),
        ]);
    }

    public function isReadByUser(int $userId): bool
    {
        return $this->users()
            ->where('user_id', $userId)
            ->whereNotNull('user_notifications.read_at')
            ->exists();
    }

    public static function sendToUser(int $userId, string $title, string $body = '', array $data = []): self
    {
        $notification = self::create([
            'type' => 'info',
            'title' => $title,
            'body' => $body,
            'data' => $data,
        ]);

        $notification->users()->attach($userId);

        return $notification;
    }

    public static function exportSuccess(int $userId, string $jenis, string $filename): self
    {
        return self::sendToUser(
            $userId,
            "Export {$jenis} berhasil",
            "File {$filename} telah berhasil di-export.",
            ['type' => 'export', 'jenis' => $jenis, 'filename' => $filename]
        );
    }

    public static function exportFailed(int $userId, string $jenis, string $error): self
    {
        $notification = self::create([
            'type' => 'error',
            'title' => "Export {$jenis} gagal",
            'body' => $error,
            'data' => ['type' => 'export', 'jenis' => $jenis, 'error' => $error],
        ]);

        $notification->users()->attach($userId);

        return $notification;
    }

    public static function batchComplete(int $userId, int $total, int $success, int $failed): self
    {
        return self::sendToUser(
            $userId,
            "Export batch selesai",
            "Total: {$total} | Berhasil: {$success} | Gagal: {$failed}",
            ['type' => 'batch', 'total' => $total, 'success' => $success, 'failed' => $failed]
        );
    }
}
