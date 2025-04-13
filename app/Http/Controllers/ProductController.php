<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
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

    public function index()
    {
        return response()->json(Product::all());
    }
    

    public function show($id)
    {
        $product = Product::findOrFail($id);
        return response()->json($product);
    }
}
