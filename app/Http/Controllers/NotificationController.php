<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    // Halaman daftar notifikasi (pagination)
    public function index()
    {
        $user = Auth::user();
        $notifications = $user->notifications()->latest()->paginate(20);
        $unreadCount = $user->unreadNotifications()->count();

        return view('notifications.index', compact('notifications', 'unreadCount'));
    }

    // Tandai satu notifikasi sebagai terbaca (web form)
    public function markRead($id)
    {
        $user = Auth::user();
        $notification = $user->notifications()->where('id', $id)->firstOrFail();
        $notification->markAsRead();

        // jika request AJAX, kembalikan JSON
        if (request()->ajax()) {
            return response()->json(['status' => 'ok']);
        }

        return back();
    }

    // Tandai semua terbaca
    public function markAllRead()
    {
        $user = Auth::user();
        $user->unreadNotifications->markAsRead();

        if (request()->ajax()) {
            return response()->json(['status' => 'ok']);
        }

        return back();
    }

    // Endpoint JSON: jumlah unread
    public function unreadCount()
    {
        return response()->json(['unread' => Auth::user()->unreadNotifications()->count()]);
    }

    // Endpoint JSON: daftar notifikasi terbaru (limit X)
    public function latest()
    {
        $data = Auth::user()->notifications()->latest()->take(10)->get()->map(function ($n) {
            return [
                'id' => $n->id,
                'data' => $n->data,
                'read_at' => $n->read_at,
                'time' => $n->created_at->diffForHumans(),
                'created_at' => $n->created_at->toDateTimeString(),
            ];
        });
        return response()->json(['notifications' => $data]);
    }
}
