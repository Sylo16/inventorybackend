<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function analytics(Request $request)
    {
        $period = $request->query('period', 'today');
        $dateRange = $this->getDateRange($period);

        // --- Sales Analytics (group by day) ---
        $salesAnalytics = DB::table('sales')
            ->selectRaw('DATE(created_at) as label, SUM(total_amount) as sales')
            ->whereBetween('created_at', [$dateRange['from'], $dateRange['to']])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('label')
            ->get();

        // --- Sales by Category ---
        $categorySales = DB::table('sales')
            ->join('sale_items', 'sales.id', '=', 'sale_items.sale_id')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->selectRaw('categories.name as category, SUM(sale_items.quantity * sale_items.unit_price) as value')
            ->whereBetween('sales.created_at', [$dateRange['from'], $dateRange['to']])
            ->groupBy('categories.name')
            ->orderBy('value', 'desc')
            ->get();

        // --- Sales Summary Cards ---
        $salesSummary = [
            'yesterday' => DB::table('sales')
                ->whereBetween('created_at', [
                    Carbon::yesterday()->startOfDay(),
                    Carbon::yesterday()->endOfDay()
                ])->sum('total_amount'),
            'lastWeek' => DB::table('sales')
                ->whereBetween('created_at', [
                    Carbon::now()->subWeek()->startOfWeek(),
                    Carbon::now()->subWeek()->endOfWeek()
                ])->sum('total_amount'),
            'lastMonth' => DB::table('sales')
                ->whereBetween('created_at', [
                    Carbon::now()->subMonth()->startOfMonth(),
                    Carbon::now()->subMonth()->endOfMonth()
                ])->sum('total_amount'),
        ];

        // --- Damage Summary Cards ---
        $damageSummary = [
            'today' => DB::table('damaged_products')->whereBetween('created_at', [
                Carbon::today()->startOfDay(),
                Carbon::today()->endOfDay()
            ])->sum('quantity'),
            'lastWeek' => DB::table('damaged_products')->whereBetween('created_at', [
                Carbon::now()->subWeek()->startOfWeek(),
                Carbon::now()->subWeek()->endOfWeek()
            ])->sum('quantity'),
        ];

        // --- Example summary cards (customize as needed) ---
        $summary = [
            [
                'label' => 'Total Sales',
                'value' => '₱' . number_format($salesSummary['lastMonth']),
                'icon' => 'trending-up',
                'bgClass' => 'bg-blue-100',
                'textColor' => 'text-blue-600',
            ],
            [
                'label' => 'Total Damaged',
                'value' => $damageSummary['today'] . ' Items',
                'icon' => 'alert-triangle',
                'bgClass' => 'bg-red-100',
                'textColor' => 'text-red-600',
            ],
        ];

        return response()->json([
            'summary' => $summary,
            'salesSummary' => $salesSummary,
            'damageSummary' => $damageSummary,
            'salesAnalytics' => $salesAnalytics,
            'categorySales' => $categorySales,
        ]);
        
    }

    private function getDateRange($period)
    {
        $now = Carbon::now();
        switch ($period) {
            case 'today':
                return [
                    'from' => $now->copy()->startOfDay(),
                    'to' => $now->copy()->endOfDay(),
                ];
            case 'last_week':
                return [
                    'from' => $now->copy()->subWeek()->startOfWeek(),
                    'to' => $now->copy()->subWeek()->endOfWeek(),
                ];
            case 'last_month':
                return [
                    'from' => $now->copy()->subMonth()->startOfMonth(),
                    'to' => $now->copy()->subMonth()->endOfMonth(),
                ];
            default:
                return [
                    'from' => $now->copy()->startOfDay(),
                    'to' => $now->copy()->endOfDay(),
                ];
        }
    }
    
}
