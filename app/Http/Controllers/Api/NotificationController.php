<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function getNotifications()
    {
        $user = Auth::guard('sanctum')->user();

        $notifications = $user->notifications()->latest()->get();

        $newNotificationCount = $user->unreadNotifications->count();

        return response()->json([
            'success' => true,
            'notifications' => $notifications,
            'new_notifiaction_count' => $newNotificationCount
        ]);
    }

    public function markAllAsRead()
    {
        $user = Auth::guard('sanctum')->user();

        $user->unreadNotifications->markAsRead();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil menandai telah dibaca!'
        ]);
    }

    public function destroy($id)
    {
        $user = Auth::guard('sanctum')->user();

        $notification = $user->notifications()->find($id);

        if (!$notification) {

            return response()->json([
                'message' => 'Notification Not found.'
            ], 404);
        }

        if ($notification) {
            $notification->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Berhasil menghapus pesan!'
        ]);
    }
}
