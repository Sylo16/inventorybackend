<?php

namespace App\Http\Controllers;

use App\Models\DamagedProduct;
use Illuminate\Http\Request;
use App\Models\Notification;

class DamagedProductController extends Controller
{

    public function store(Request $request)
{
    $validated = $request->validate([
        'customer_name' => 'required|string|max:255',
        'product_name' => 'required|string|max:255',
        'quantity' => 'required|integer|min:1',
        'reason' => 'required|string|max:255',
        'date' => 'required|date',
        'unit_of_measurement' => 'required|string|max:50',  // Validate unit of measurement
    ]);

    $damagedProduct = DamagedProduct::create($validated);

     // Create a notification
    Notification::create([
        'type' => 'damaged_product_reported',
        'message' => "Damaged product reported: {$request->product_name} by {$request->customer_name}",
        'read' => false,
        'product_id' => null, // Optional: Add if related to a specific product
    ]);

    return response()->json([
        'message' => 'Damaged product recorded successfully!',
        'damagedProduct' => $damagedProduct
    ], 201);
}

    public function stats()
        {
            $total = DamagedProduct::sum('quantity');
            $recent = DamagedProduct::orderBy('date', 'desc')
                ->limit(5)
                ->get();

            return response()->json([
                'total_damaged' => $total,
                'recent_damages' => $recent
            ]);
        }
   

    public function index()
{
    $damagedProducts = DamagedProduct::orderBy('created_at', 'desc')->get();
    return response()->json($damagedProducts);
}
}
