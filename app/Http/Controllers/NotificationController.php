<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->orWhere('user_id', null)
            ->latest()
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    public function show(Notification $notification)
    {
        // Faqat o'z bildirishnomalarini ko'ra oladi
        if ($notification->user_id && $notification->user_id != Auth::id()) {
            abort(403);
        }

        // O'qilmagan bo'lsa, o'qildi deb belgilash
        if (!$notification->is_read) {
            $notification->markAsRead();
        }

        return view('notifications.show', compact('notification'));
    }

    public function markAsRead(Notification $notification)
    {
        if ($notification->user_id && $notification->user_id != Auth::id()) {
            abort(403);
        }

        $notification->markAsRead();

        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Bildirishnoma o\'qildi deb belgilandi');
    }

    public function markAllAsRead()
    {
        Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        return back()->with('success', 'Barcha bildirishnomalar o\'qilgan deb belgilandi');
    }

    public function destroy(Notification $notification)
    {
        if ($notification->user_id && $notification->user_id != Auth::id()) {
            abort(403);
        }

        $notification->delete();

        return redirect()->route('notifications.index')
            ->with('success', 'Bildirishnoma o\'chirildi');
    }
}
