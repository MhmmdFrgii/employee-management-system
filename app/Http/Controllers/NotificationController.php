<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        // Ambil semua notifikasi dan batasi hanya 10 notifikasi terbaru
        $notifications = $user->notifications()->latest()->get();

        // Hitung notifikasi yang belum dibaca
        $newNotificationCount = $user->unreadNotifications->count();

        return view('notifications.index', compact('notifications', 'newNotificationCount'));
    }

    /**
     * Menandai semua notifikasi sebagai sudah dibaca.
     */
    public function markAllAsRead()
    {
        $user = Auth::user();

        // Menandai semua notifikasi sebagai sudah dibaca
        $user->unreadNotifications->markAsRead();

        return redirect()->back()->with('success', 'Semua notifikasi telah ditandai sebagai dibaca.');
    }

    public function destroy($id)
    {
        // Ambil user yang sedang login
        $user = Auth::user();

        // Cari notifikasi berdasarkan ID dan user
        $notification = $user->notifications()->find($id);

        if ($notification) {
            $notification->delete();
        }

        return redirect()->route('notifications.index')->with('success', 'Notification deleted successfully.');
    }
}