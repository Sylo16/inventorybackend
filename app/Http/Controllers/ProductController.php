<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Deduction;

class ProductController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:100|unique:products,sku',
            'unit_price' => 'required|numeric|min:0.01',
            'quantity' => 'required|integer|min:0',
            'unit_of_measurement' => 'required|string|max:100',
            'category' => 'nullable|string|max:100',
            'image_url' => 'nullable|string',
        ]);

        // Handle base64 image if provided
        if (!empty($validated['image_url']) && str_starts_with($validated['image_url'], 'data:image')) {
            $image = $validated['image_url'];
            
            // Extract base64 data
            preg_match('/^data:image\/(\w+);base64,/', $image, $matches);
            $imageData = substr($image, strpos($image, ',') + 1);
            $imageType = $matches[1] ?? 'png';
            
            // Decode base64
            $decodedImage = base64_decode($imageData);
            
            // Generate unique filename
            $filename = 'product_' . time() . '_' . uniqid() . '.' . $imageType;
            
            // Save to storage/app/public/products/
            $path = 'products/' . $filename;
            \Storage::disk('public')->put($path, $decodedImage);
            
            // Update validated data with file path
            $validated['image_url'] = $path;
        } else {
            $validated['image_url'] = null;
        }

        $product = Product::create($validated);

        return response()->json([
            'message' => 'Product added successfully!',
            'product' => $product
        ], 201);
    }

    // List all products (even hidden ones)
    public function index(Request $request)
    {
        $products = Product::all(); 

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

    public function deduct($productName, Request $request)
    {
        $product = Product::where('name', $productName)->first();
    
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }
    
        // Deduct logic here, for example:
        $product->quantity -= $request->quantity;
        $product->save();
    
        return response()->json(['message' => 'Product quantity deducted successfully'], 200);
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

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $product->name = $request->input('name');
        $product->category = $request->input('category');
        $product->unit_price = $request->input('unitPrice');
        $product->unit_of_measurement = $request->input('unitOfMeasurement');

        // Handle image update
        if ($request->has('image_url')) {
            $image = $request->input('image_url');
            
            if (!empty($image) && str_starts_with($image, 'data:image')) {
                // Delete old image if exists
                if ($product->image_url) {
                    \Storage::disk('public')->delete($product->image_url);
                }
                
                // Extract base64 data
                preg_match('/^data:image\/(\w+);base64,/', $image, $matches);
                $imageData = substr($image, strpos($image, ',') + 1);
                $imageType = $matches[1] ?? 'png';
                
                // Decode base64
                $decodedImage = base64_decode($imageData);
                
                // Generate unique filename
                $filename = 'product_' . time() . '_' . uniqid() . '.' . $imageType;
                
                // Save to storage/app/public/products/
                $path = 'products/' . $filename;
                \Storage::disk('public')->put($path, $decodedImage);
                
                $product->image_url = $path;
            } elseif ($image === null) {
                // Delete image if null is sent
                if ($product->image_url) {
                    \Storage::disk('public')->delete($product->image_url);
                }
                $product->image_url = null;
            }
        }

        $product->updated_at = now();
        $product->save();

        return response()->json(['product' => $product]);
    }

    public function deducted(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found.'], 404);
        }

        if ($product->quantity < $request->quantity) {
            return response()->json(['message' => 'Not enough stock to deduct.'], 400);
        }

        $product->quantity -= $request->quantity;
        $product->updated_at = now();
        $product->save();

        return response()->json(['product' => $product], 200);
    }
}