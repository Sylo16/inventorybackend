<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('products')->insert([
            [
                'name' => 'Cement 40kg',
                'sku' => Str::uuid(),
                'unit_price' => 230.00,
                'quantity' => 100,
                'unit_of_measurement' => 'bag',
                'category' => 'Construction Materials',
                'hidden' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Steel Bar 12mm',
                'sku' => Str::uuid(),
                'unit_price' => 150.50,
                'quantity' => 200,
                'unit_of_measurement' => 'piece',
                'category' => 'Hardware',
                'hidden' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Plywood 1/4"',
                'sku' => Str::uuid(),
                'unit_price' => 480.00,
                'quantity' => 50,
                'unit_of_measurement' => 'sheet',
                'category' => 'Wood Products',
                'hidden' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
