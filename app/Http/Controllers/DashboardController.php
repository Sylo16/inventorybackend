<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function getDashboardData()
    {
        $today = Carbon::today();
        $monthStart = Carbon::now()->startOfMonth();
        
        // Total Sales Calculations
        $totalSales = DB::table('sales')->sum('amount');
        $todaySales = DB::table('sales')
            ->whereDate('sale_date', $today)
            ->sum('amount');
        $monthSales = DB::table('sales')
            ->whereBetween('sale_date', [$monthStart, $today])
            ->sum('amount');
            
        // Inventory Stats
        $totalItems = DB::table('products')->sum('quantity');
        $totalCategories = DB::table('products')
            ->distinct('category')
            ->count('category');
            
       // Critical Alerts
        $lowStock = DB::table('products')
            ->where('quantity', '<', 50)
            ->where('quantity', '>', 10)
            ->count();

       
        $criticalStock = DB::table('products')
            ->where('quantity', '<', 10)  
            ->where('quantity', '>', 0)
            ->count();

        $outOfStock = DB::table('products')
            ->where('quantity', '<=', 0)
            ->count();

        // Update the total alerts count to include critical stock
        $criticalAlerts = $lowStock + $outOfStock + $criticalStock;
                    
        // Calculate trends (simplified example)
        $yesterdaySales = DB::table('sales')
            ->whereDate('sale_date', $today->subDay())
            ->sum('amount');
        $salesTrend = $todaySales > $yesterdaySales ? '↑ 5%' : '↓ 2%';
            
        return response()->json([
            'total_sales' => $totalSales,
            'today_sales' => $todaySales,
            'month_sales' => $monthSales,
            'total_revenue' => $totalSales, // Using same as total sales for simplicity
            'gross_revenue' => $totalSales, // Using same as total sales for simplicity
            'total_items' => $totalItems,
            'total_categories' => $totalCategories,
            'low_stock' => $lowStock,
            'critical_stock' => $criticalStock,
            'out_of_stock' => $outOfStock,
            'critical_alerts' => $criticalAlerts,
            'sales_trend' => $salesTrend,
            'revenue_trend' => $salesTrend, // Same as sales trend for simplicity
            'inventory_trend' => '→ 0%', // Static for now
            'alert_trend' => $criticalAlerts > 0 ? '↑ ' . $criticalAlerts : '→ 0',
           
            
        ]);
    }
}