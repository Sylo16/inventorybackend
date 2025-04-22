<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    // Create new product
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:100|unique:products,sku',
            'description' => 'nullable|string',
            'unit_price' => 'required|numeric|min:0.01',
            'quantity' => 'required|integer|min:0',
            'unit_of_measurement' => 'required|string|max:100',
            'category' => 'nullable|string|max:100',
        ]);

        $product = Product::create($validated);

        return response()->json([
            'message' => 'Product added successfully!',
            'product' => $product
        ], 201);
    }

    // List all products (even hidden ones)
    public function index(Request $request)
    {
        $products = Product::all(); // Get all products including hidden ones

        return response()->json($products);
    }

    // Show a specific product
    public function show($id)
    {
        $product = Product::findOrFail($id);
        return response()->json($product);
    }

    // Receive stock
    public function receive($id, Request $request)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);

        $product = Product::findOrFail($id);
        $product->quantity += $request->quantity;
        $product->save();

        return response()->json([
            'message' => 'Product quantity increased successfully.',
            'product' => $product,
        ]);
    }

    // Deduct stock
    public function deduct($id, Request $request)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);

        $product = Product::findOrFail($id);

        if ($product->quantity < $request->quantity) {
            return response()->json(['error' => 'Not enough stock to deduct.'], 400);
        }

        $product->quantity -= $request->quantity;
        $product->save();

        return response()->json([
            'message' => 'Product quantity deducted successfully.',
            'product' => $product,
        ]);
    }

    // Hide product (mark as hidden, but keep it in the list)
    public function hideProduct($id)
    {
        $product = Product::findOrFail($id);
        $product->hidden = true;  // Mark as hidden, but don't remove from the list
        $product->save();

        return response()->json([
            'message' => 'Product marked as hidden successfully.',
            'product' => $product,
        ]);
    }

    // Unhide product
    public function unhideProduct($id)
    {
        $product = Product::findOrFail($id);
        $product->hidden = false;  // Mark as visible again
        $product->save();

        return response()->json(['message' => 'Product unhidden successfully']);
    }
}

