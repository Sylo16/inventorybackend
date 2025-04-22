<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stat;
use App\Models\CategorySale;
use App\Models\SalesData;
use App\Models\TopSellingProduct;
use App\Models\RecentUpdate;
use App\Models\Inventory;

class DashboardController extends Controller
{
    public function index()
    {
        $stat = Stat::first(); // Assuming there's only one record in the Stat table

        // Fetch sales data, revenue, inventory, and alerts
        $totalSales = SalesData::sum('sales');
        $totalRevenue = TopSellingProduct::sum('sales');
        $totalInventory = Inventory::sum('quantity');
        $criticalAlerts = RecentUpdate::where('is_critical', true)->count();

        return response()->json([
            'total_sales' => $stat->total_sales ?? 0,
            'today_sales' => $stat->today_sales ?? 0,
            'month_sales' => $stat->month_sales ?? 0,
            'sales_trend' => $stat->sales_trend ?? 'N/A',
            'total_revenue' => $stat->total_revenue ?? 0,
            'gross_revenue' => $stat->gross_revenue ?? 0,
            'net_revenue' => $stat->net_revenue ?? 0,
            'revenue_trend' => $stat->revenue_trend ?? 'N/A',
            'total_items' => $stat->total_items ?? 0,
            'total_categories' => $stat->total_categories ?? 0,
            'inventory_trend' => $stat->inventory_trend ?? 'N/A',
            'critical_alerts' => $stat->critical_alerts ?? 0,
            'low_stock' => $stat->low_stock ?? 0,
            'out_of_stock' => $stat->out_of_stock ?? 0,
            'alert_trend' => $stat->alert_trend ?? 'N/A',

            'total_sales_from_data' => $totalSales,
            'total_revenue_from_products' => $totalRevenue,
            'inventory_quantity' => $totalInventory,
            'critical_alerts_count' => $criticalAlerts,

            'sales_chart' => SalesData::orderBy('id')->get(),
            'category_sales' => CategorySale::all(),
            'top_selling_products' => TopSellingProduct::all(),
            'recent_updates' => RecentUpdate::latest()->take(5)->get(),
        ]);
    }
}
