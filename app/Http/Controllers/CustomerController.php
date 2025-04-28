<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; // Import Log

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::with('products')->get();
        return response()->json($customers);
    }

    public function store(Request $request)
    {
        // Log the incoming request payload for debugging
        Log::info('Incoming customer request', $request->all());

        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'phone' => 'required|string|max:15',
                'purchase_date' => 'required|date',
                'products' => 'required|array',
                'products.*.product_name' => 'required|string',
                'products.*.category' => 'required|string',
                'products.*.unit' => 'required|string',
                'products.*.quantity' => 'required|integer|min:1',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }

        // Extract only necessary customer fields
        $customerData = collect($validated)->only(['name', 'phone', 'purchase_date'])->toArray();

        // Create the customer record
        $customer = Customer::create($customerData);

        // Add related purchased products
        foreach ($validated['products'] as $product) {
            $customer->products()->create($product);
        }

        // Return the newly created customer with their products
        return response()->json($customer->load('products'), 201);
    }
    
}
