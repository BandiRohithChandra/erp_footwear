<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientNotificationController extends Controller
{
    public function index()
    {
        // Get all notifications for the logged-in client (read & unread)
        $notifications = Auth::user()->notifications()->latest()->get();

        // Count unread notifications (optional, for navbar badge)
        $unreadCount = Auth::user()->unreadNotifications()->count();

        return view('notifications.client.index', compact('notifications', 'unreadCount'));
    }

    public function markAllAsRead()
{
    auth()->user()->unreadNotifications->markAsRead();

    return redirect()->back()->with('success', 'All notifications marked as read.');
}
}
