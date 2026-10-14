<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    // সব notifications দেখাও
    public function index()
    {
        $notifications = auth()->user()
            ->notifications()
            ->latest()
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

   // একটা notification mark as read
public function markAsRead($id)
{
    $notification = auth()->user()
        ->notifications()
        ->findOrFail($id);

    $notification->markAsRead();

    // AJAX request হলে JSON response
    if (request()->expectsJson()) {
        return response()->json([
            'success' => true,
            'url' => $notification->data['url'] ?? null,
            'unread_count' => auth()->user()->unreadNotifications->count(),
        ]);
    }

    // Normal request হলে redirect
    if (isset($notification->data['url'])) {
        return redirect($notification->data['url']);
    }

    return back()->with('success', 'Notification marked as read.');
}
    // সব notifications mark as read
    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();

        return back()->with('success', 'All notifications marked as read.');
    }
}