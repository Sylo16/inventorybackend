<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::with('products')->get();
        return response()->json($customers);
    }

    public function store(Request $request)
    {
        // Log the incoming request for debugging
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

        try {
            DB::beginTransaction();

            $customerData = collect($validated)->only(['name', 'phone', 'purchase_date'])->toArray();
            $customer = Customer::create($customerData);

            // Create related purchased products
            foreach ($validated['products'] as $product) {
                $customer->products()->create($product);
            }

            // Create notification
            Notification::create([
                'type' => 'customer_added',
                'message' => "New customer '{$customer->name}' added.",
                'read' => false,
            ]);

            DB::commit();

            // Return the created customer with products
            return response()->json($customer->load('products'), 201);

        } catch (\Exception $ex) {
            DB::rollBack();
            Log::error('Error storing customer: ' . $ex->getMessage());
            return response()->json(['message' => 'Server error occurred'], 500);
        }
    }

        public function update(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);

        // Validate incoming data
        $validated = $request->validate([
            'products' => 'required|array',
            'products.*.product_name' => 'required|string',
            'products.*.category' => 'required|string',
            'products.*.unit' => 'required|string',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.purchase_date' => 'required|date',
        ]);

        // Remove all previous products (if you want to replace) or just add new ones
        // $customer->products()->delete(); // Uncomment if you want to replace all

        // If you want to append, just add new products:
        foreach ($validated['products'] as $product) {
            $customer->products()->create($product);
        }

        $customer->load('products');
        return response()->json($customer);
    }

    public function show($id)
{
    $customer = Customer::with('products')->findOrFail($id);
    return response()->json($customer);
}


}
