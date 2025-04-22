<?php

namespace App\Http\Controllers;

use App\Models\DamagedProduct;
use Illuminate\Http\Request;

class DamagedProductController extends Controller
{
    // Store a newly created damaged product in the database
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

    return response()->json([
        'message' => 'Damaged product recorded successfully!',
        'damagedProduct' => $damagedProduct
    ], 201);
}

    // Retrieve all damaged product records
   

    public function index()
{
    $damagedProducts = DamagedProduct::orderBy('created_at', 'desc')->get();
    return response()->json($damagedProducts);
}
}
