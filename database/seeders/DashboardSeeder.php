<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Stat;
use App\Models\CategorySale;
use App\Models\SalesData;
use App\Models\TopSellingProduct;
use App\Models\RecentUpdate;
use App\Models\Inventory;

class DashboardSeeder extends Seeder
{
    public function run()
    {
        // Optional: Truncate tables to avoid duplicate seeding (development only)
        Stat::truncate();
        SalesData::truncate();
        CategorySale::truncate();
        TopSellingProduct::truncate();
        RecentUpdate::truncate();
        Inventory::truncate();

        // Seed Stat model
        Stat::create([
            'total_sales' => 150000,
            'today_sales' => 20000,
            'month_sales' => 80000,
            'sales_trend' => '↑ 15%',

            'total_revenue' => 120000,
            'gross_revenue' => 130000,
            'net_revenue' => 115000,
            'revenue_trend' => '↑ 10%',

            'total_items' => 540,
            'total_categories' => 8,
            'total_suppliers' => 4,
            'inventory_trend' => '↑ 5%',

            'critical_alerts' => 3,
            'low_stock' => 2,
            'out_of_stock' => 1,
            'alert_trend' => '↓ 2%',
        ]);

        // Seed SalesData model
        SalesData::insert([
            ['month' => 'Jan', 'sales' => 50000],
            ['month' => 'Feb', 'sales' => 80000],
            ['month' => 'Mar', 'sales' => 120000],
        ]);

        // Seed CategorySale model
        CategorySale::insert([
            ['category' => 'Cement', 'sales' => 60000, 'color' => '#FF6384'],
            ['category' => 'Gravel & Sand', 'sales' => 30000, 'color' => '#36A2EB'],
            ['category' => 'Structural Steel', 'sales' => 40000, 'color' => '#FFCE56'],
            ['category' => 'Concrete', 'sales' => 20000, 'color' => '#4BC0C0'],
            ['category' => 'Bricks', 'sales' => 30000, 'color' => '#9966FF'],
            ['category' => 'Tiles', 'sales' => 25000, 'color' => '#FF9F40'],
            ['category' => 'Roofing', 'sales' => 15000, 'color' => '#FF6384'],

        ]);

        // Seed TopSellingProduct model
        TopSellingProduct::insert([
            [
                'name' => 'Portland Cement (40kg)',
                'sales' => 50000,
                'quantity' => 1000,
                'trend' => '↑ 15%'
            ],
            [
                'name' => 'Rebar (10mm)',
                'sales' => 40000,
                'quantity' => 600,
                'trend' => '↑ 10%'
            ],
            [
                'name' => 'Concrete Mix (50kg)',
                'sales' => 30000,
                'quantity' => 800,
                'trend' => '↑ 5%'
            ],
            [
                'name' => 'Gravel (1 ton)',
                'sales' => 20000,
                'quantity' => 500,
                'trend' => '↓ 2%'
            ],
            [
                'name' => 'Sand (1 ton)',
                'sales' => 15000,
                'quantity' => 400,
                'trend' => '↑ 8%'

            ]
        ]);

        // Seed RecentUpdate model
        RecentUpdate::create([
            'update_text' => 'New delivery: 500 bags of Cement arrived',
            'time' => '2 hours ago',
            'priority' => 'high',
            'action' => 'Stock updated'
        ]);

        // Seed Inventory model
        Inventory::create([
            'product_name' => 'Portland Cement (40kg)',
            'quantity' => 1000,
        ]);
    }
}
