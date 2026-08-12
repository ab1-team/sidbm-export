<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = auth()->user()
            ->notifications()
            ->orderBy('user_notifications.created_at', 'desc')
            ->limit(50)
            ->get();

        return response()->json([
            'success' => true,
            'notifications' => $notifications,
        ]);
    }

    public function unread()
    {
        $count = auth()->user()
            ->notifications()
            ->whereNull('user_notifications.read_at')
            ->count();

        return response()->json([
            'success' => true,
            'count' => $count,
        ]);
    }

    public function markAsRead(string $id)
    {
        $notification = Notification::where('id', $id)->first();

        if (!$notification) {
            return response()->json(['success' => false, 'message' => 'Notifikasi tidak ditemukan.'], 404);
        }

        $notification->markAsReadForUser(auth()->id());

        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        auth()->user()
            ->notifications()
            ->whereNull('user_notifications.read_at')
            ->update(['user_notifications.read_at' => now()]);

        return response()->json(['success' => true]);
    }

    public function destroy(string $id)
    {
        $notification = Notification::where('id', $id)->first();

        if (!$notification) {
            return response()->json(['success' => false, 'message' => 'Notifikasi tidak ditemukan.'], 404);
        }

        $notification->users()->detach(auth()->id());

        return response()->json(['success' => true]);
    }
}
