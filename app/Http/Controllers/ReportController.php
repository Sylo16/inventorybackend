<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Sale;
use App\Models\CategorySale;
use App\Models\DamagedProduct;
use Illuminate\Support\Facades\DB;
class ReportController extends Controller
{

    public function topSellingProducts()
    {
        $topSelling = DB::table('sales')
            ->join('products', 'sales.product_id', '=', 'products.id')
            ->select('products.name', 'products.category', DB::raw('SUM(sales.quantity) as total_sold'))
            ->groupBy('sales.product_id', 'products.name', 'products.category')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        return response()->json($topSelling);
    }
}
