<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Http\Requests\NotificationRequest;
use App\Http\Requests\StoreNotificationRequest;
use App\Http\Requests\UpdateNotificationRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Auth as FacadesAuth;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil ID pengguna yang sedang login
        $userId = Auth::user()->id;

        // Tandai semua notifikasi yang belum dibaca sebagai dibaca
        Notification::where('user_id', $userId)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        // Ambil notifikasi yang sudah ada
        $notifications = Notification::where('user_id', $userId)->latest()->get();

        // Hitung notifikasi yang belum dibaca (sebelum update status menjadi dibaca)
        $newNotificationCount = Notification::where('user_id', $userId)
            ->where('is_read', false)
            ->count();

        return view('notifications.index', compact('notifications', 'newNotificationCount'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Notification $notification)
    {
        $notification->delete();
        return redirect()->route('notifications.index')->with('success', 'Notification deleted successfully.');
    }
}
