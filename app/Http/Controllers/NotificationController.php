<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    // Get all notifications for the authenticated user
    public function index()
    {
        $notifications = Notification::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($notifications);
    }

    // Store a new notification
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|string',
            'message' => 'required|string',
            'product_id' => 'nullable|exists:products,id'
        ]);

        $notification = Notification::create([
            'user_id' => auth()->id(),
            'type' => $validated['type'],
            'message' => $validated['message'],
            'product_id' => $validated['product_id'] ?? null,
            'read' => false
        ]);

        // Optional: broadcast(new NewNotification($notification))->toOthers();

        return response()->json($notification, 201);
    }

    // Mark a single notification as read
    public function markAsRead(Notification $notification)
    {
        if ($notification->user_id !== auth()->id()) {
            abort(403);
        }

        $notification->update(['read' => true]);
        return response()->json($notification);
    }

    // Mark all notifications as read for the authenticated user
    public function markAllAsRead()
    {
        Notification::where('user_id', auth()->id())
            ->where('read', false)
            ->update(['read' => true]);

        return response()->json(['message' => 'All notifications marked as read.']);
    }
}
