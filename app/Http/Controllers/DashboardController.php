<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
        public function getDashboardData()
    {
        try {
            $today = Carbon::today();
            $monthStart = Carbon::now()->startOfMonth();
            
            // Total Sales Calculations - use total_amount instead of amount
            $totalSales = DB::table('sales')->sum('total_amount');
            $todaySales = DB::table('sales')
                ->whereDate('sale_date', $today)
                ->sum('total_amount');
            $monthSales = DB::table('sales')
                ->whereBetween('sale_date', [$monthStart, $today])
                ->sum('total_amount');
                
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

            
            // NEW: Calculate in_stock (quantity >= 50)
            $inStock = DB::table('products')
                ->where('quantity', '>=', 50)
                ->count();

            $criticalAlerts = $lowStock + $outOfStock + $criticalStock;
                        
            // Calculate trends
            $yesterdaySales = DB::table('sales')
                ->whereDate('sale_date', $today->copy()->subDay()) // Use copy() to avoid modifying original
                ->sum('total_amount');
                
            $salesTrend = $todaySales > $yesterdaySales ? '↑ 5%' : '↓ 2%';
                
            return response()->json([
                'total_sales' => $totalSales,
                'today_sales' => $todaySales,
                'month_sales' => $monthSales,
                'total_items' => $totalItems,
                'total_categories' => $totalCategories,
                'low_stock' => $lowStock,
                'critical_stock' => $criticalStock,
                'out_of_stock' => $outOfStock,
                'in_stock' => $inStock,
                'critical_alerts' => $criticalAlerts,
                'sales_trend' => $salesTrend,
                'inventory_trend' => '→ 0%',
                'alert_trend' => $criticalAlerts > 0 ? '↑ ' . $criticalAlerts : '→ 0',
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Dashboard data error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to load dashboard data',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}