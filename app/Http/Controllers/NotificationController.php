<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    /**
     * Display a listing of the user's notifications.
     */
    public function index(Request $request)
    {
        $notifications = $request->user()->notifications()->latest()->get();

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Show a specific notification.
     */
    public function show(Request $request, $notificationId)
    {
        $notification = $request->user()->notifications()->findOrFail($notificationId);
        return response()->json($notification);
    }

    /**
     * Mark a notification as read.
     */
    public function markAsRead(Request $request, $notificationId)
    {
        $notification = $request->user()->notifications()->findOrFail($notificationId);
        $notification->markAsRead();

        return redirect()->route('notifications.index')->with('success', __('Notification marked as read.'));
    }

     public function markAllAsRead()
{
    auth()->user()->unreadNotifications->markAsRead();

    return redirect()->back()->with('success', 'All notifications marked as read.');
}

    

    /**
     * Delete a notification.
     */
    public function destroy(Request $request, $notificationId)
    {
        $notification = $request->user()->notifications()->findOrFail($notificationId);
        $notification->delete();

        return redirect()->route('notifications.index')->with('success', __('Notification deleted.'));
    }

    /**
     * Get the count of unread notifications for the authenticated user.
     */
    public function getUnreadCount(Request $request)
    {
        $unreadCount = $request->user()->unreadNotifications()->count();

        return response()->json(['unread_count' => $unreadCount]);
    }
}