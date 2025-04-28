<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReportController extends Controller
{
    public function index()
    {
        try {
            // Total Sales (quantity of items sold)
            $totalSales = Sale::sum('quantity');

            // Low Stock Products (threshold: quantity < 10)
            $lowStockCount = Product::where('quantity', '<', 10)->count();

            // Total Products in inventory
            $totalProducts = Product::count();

            // Total Revenue (sum of quantity * price)
            $totalRevenue = Sale::select(DB::raw('SUM(quantity * price) as total'))->value('total');

            // Format revenue with currency
            $formattedRevenue = $totalRevenue ? '₱' . number_format($totalRevenue, 2) : '₱0.00';

            return response()->json([
                'summary' => [
                    [
                        'label' => 'Total Sales',
                        'value' => $totalSales,
                        'icon' => 'trending-up',
                        'bgClass' => 'bg-green-100',
                        'textColor' => 'text-green-600'
                    ],
                    [
                        'label' => 'Low Stock',
                        'value' => $lowStockCount,
                        'icon' => 'alert-triangle',
                        'bgClass' => 'bg-yellow-100',
                        'textColor' => 'text-yellow-600'
                    ],
                    [
                        'label' => 'Total Products',
                        'value' => $totalProducts,
                        'icon' => 'package',
                        'bgClass' => 'bg-indigo-100',
                        'textColor' => 'text-indigo-600'
                    ],
                    [
                        'label' => 'Total Revenue',
                        'value' => $formattedRevenue,
                        'icon' => 'peso-sign',
                        'bgClass' => 'bg-blue-100',
                        'textColor' => 'text-blue-600'
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            // Log the error for debugging purposes
            Log::error('Error generating report: ' . $e->getMessage());

            // Return a response indicating the error
            return response()->json([
                'error' => 'An error occurred while generating the report.',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
