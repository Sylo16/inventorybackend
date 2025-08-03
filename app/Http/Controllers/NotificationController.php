<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use App\Models\Product;


class NotificationController extends Controller
{
    // Get all notifications for the authenticated user
    public function index()
{
    return response()->json([
        'notifications' => Notification::where('user_id', auth()->id())
            ->where(function($query) {
                $query->whereNull('snoozed_until')
                      ->orWhere('snoozed_until', '<', now());
            })
            ->orderBy('read')
            ->orderBy('created_at', 'desc')
            ->get(),
        'unread_count' => Notification::where('user_id', auth()->id())
            ->where('read', false)
            ->where(function($query) {
                $query->whereNull('snoozed_until')
                      ->orWhere('snoozed_until', '<', now());
            })
            ->count()
    ]);
}

public function store(Request $request)
{
    $validated = $request->validate([
        'type' => 'required|string|in:product_added,inventory_update,customer_added,damaged_product_reported,product_received,product_deducted,product_archived,product_configured,product_unhidden,customer_product_added,out_of_stock,critical_stock,low_stock',
        'message' => 'required|string',
        'product_id' => 'nullable|exists:products,id',
        'quantity' => 'nullable|integer',
        'product_name' => 'nullable|string'
    ]);

    // Check for existing similar unread notification
    $existing = Notification::where('user_id', auth()->id())
        ->where('type', $validated['type'])
        ->where('product_id', $validated['product_id'] ?? null)
        ->where('read', false)
        ->exists();

    if ($existing) {
        return response()->json(['message' => 'Duplicate notification suppressed'], 200);
    }

    $notification = Notification::create([
        'user_id' => auth()->id(),
        'type' => $validated['type'],
        'message' => $validated['message'],
        'product_id' => $validated['product_id'] ?? null,
        'product_name' => $validated['product_name'] ?? null,
        'quantity' => $validated['quantity'] ?? null,
        'read' => false
    ]);

    return response()->json($notification, 201);
}

public function markAsRead(Notification $notification)
{
    if ($notification->user_id !== auth()->id()) {
        abort(403);
    }

    $notification->update([
        'read' => true,
        'snoozed_until' => now()->addHours(24) // Snooze for 24 hours
    ]);

    return response()->json($notification);
}

// Add this new method
public function checkStockLevels()
{
    $products = Product::where('hidden', false)->get();
    $notifications = [];

    foreach ($products as $product) {
        $type = null;
        $message = null;

        if ($product->quantity === 0) {
            $type = 'out_of_stock';
            $message = "{$product->name} is out of stock!";
        } elseif ($product->quantity < 5) {
            $type = 'critical_stock';
            $message = "{$product->name} has critical stock ({$product->quantity} left)!";
        } elseif ($product->quantity < 20) {
            $type = 'low_stock';
            $message = "{$product->name} is running low ({$product->quantity} left)";
        }

        if ($type) {
            $existing = Notification::where('user_id', auth()->id())
                ->where('type', $type)
                ->where('product_id', $product->id)
                ->where('read', false)
                ->exists();

            if (!$existing) {
                $notification = Notification::create([
                    'user_id' => auth()->id(),
                    'type' => $type,
                    'message' => $message,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'quantity' => $product->quantity,
                    'read' => false
                ]);
                $notifications[] = $notification;
            }
        }
    }

    return response()->json(['generated_notifications' => $notifications]);
}
 public function markAllAsRead()
    {
        Notification::where('user_id', auth()->id())
            ->where('read', false)
            ->update(['read' => true]);

        return response()->json(['message' => 'All notifications marked as read.']);
    }
}
