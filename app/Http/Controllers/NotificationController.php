<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($notifications);
    }

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

        // You might want to broadcast this event for real-time updates
        // broadcast(new NewNotification($notification))->toOthers();

        return response()->json($notification, 201);
    }

    public function markAsRead(Notification $notification)
    {
        if ($notification->user_id !== auth()->id()) {
            abort(403);
        }

        $notification->update(['read' => true]);
        return response()->json($notification);
    }
}