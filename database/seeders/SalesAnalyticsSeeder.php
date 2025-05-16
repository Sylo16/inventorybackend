<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Carbon\Carbon;

class SalesAnalyticsSeeder extends Seeder
{
    public function run()
    {
        // Create products
        $products = [
            ['name' => '2x4 Lumber', 'sku' => 'LUM-2X4', 'unit_price' => 120.50, 'quantity' => 100, 
             'unit_of_measurement' => 'Piece', 'category' => 'Lumber'],
            ['name' => 'Cement', 'sku' => 'CEM-50KG', 'unit_price' => 250.00, 'quantity' => 200,
             'unit_of_measurement' => 'Bag', 'category' => 'Cementitious Products'],
            // Add more products...
        ];

        foreach ($products as $product) {
            Product::create($product);
        }

        // Create sales for the past 12 months
        for ($i = 0; $i < 12; $i++) {
            $saleDate = Carbon::now()->subMonths($i);
            
            $sale = Sale::create([
                'product_id' => Product::inRandomOrder()->first()->id,
                'total_amount' => 0,
                'created_at' => $saleDate,
                'updated_at' => $saleDate,
            ]);

            // Add random sale items
            $items = [];
            $total = 0;
            
            foreach (Product::inRandomOrder()->limit(rand(1, 5))->get() as $product) {
                $quantity = rand(1, 20);
                $unitPrice = $product->unit_price;
                $subtotal = $quantity * $unitPrice;
                
                $items[] = [
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'created_at' => $saleDate,
                    'updated_at' => $saleDate,
                ];
                
                $total += $subtotal;
            }

            SaleItem::insert($items);
            $sale->update(['total_amount' => $total]);
        }
    }
}