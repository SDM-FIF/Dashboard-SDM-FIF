<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Get user notifications (AJAX endpoint)
     */
    public function getNotifications(Request $request)
    {
        $userId = Auth::id();
        $notifications = Notification::where('user_id', $userId)
            ->latest()
            ->limit(10)
            ->get();

        $unreadCount = Notification::where('user_id', $userId)
            ->where('is_read', false)
            ->count();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount
        ]);
    }

    /**
     * Mark a specific notification as read and redirect if link exists
     */
    public function markAsRead(Request $request, $id)
    {
        $notification = Notification::where('user_id', Auth::id())->findOrFail($id);
        $notification->update(['is_read' => true]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        if ($notification->link) {
            return redirect($notification->link);
        }

        return redirect()->back();
    }

    /**
     * Mark all user notifications as read
     */
    public function markAllAsRead(Request $request)
    {
        Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Semua notifikasi telah ditandai sebagai dibaca.');
    }

    /**
     * Delete a specific notification
     */
    public function destroy(Request $request, $id)
    {
        $notification = Notification::where('user_id', Auth::id())->findOrFail($id);
        $notification->delete();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Notifikasi berhasil dihapus.');
    }

    /**
     * Delete all notifications for the user
     */
    public function destroyAll(Request $request)
    {
        Notification::where('user_id', Auth::id())->delete();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Semua notifikasi berhasil dihapus.');
    }
}
