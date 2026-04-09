<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Purchase;
use App\Models\Category;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        if (auth()->user()->isCashier()) {
            return redirect()->route('cashier.dashboard');
        }

        $today = Carbon::today();
        $sevenDaysAgo = Carbon::now()->subDays(6);

        // Basic Stats
        $stats = [
            'revenue_today' => Sale::whereDate('created_at', $today)->sum('grand_total'),
            'transactions_today' => Sale::whereDate('created_at', $today)->count(),
            'total_sales_count' => Sale::count(),
            'total_sales_avg' => Sale::avg('grand_total') ?? 0,
            'total_products' => Product::count(),
            'total_stock' => Product::sum('stock_quantity'),
            'stock_value' => Product::selectRaw('SUM(stock_quantity * purchase_price) as total_value')->value('total_value') ?? 0,
            'total_categories' => Category::count(),
        ];

        // Chart Data (Last 7 Days)
        $dates = collect();
        for ($i = 6; $i >= 0; $i--) {
            $dates->push(Carbon::now()->subDays($i)->format('Y-m-d'));
        }

        $salesData = Sale::where('created_at', '>=', $sevenDaysAgo)
            ->selectRaw('DATE(created_at) as date, SUM(grand_total) as revenue, COUNT(*) as volume')
            ->groupBy('date')
            ->get()
            ->keyBy('date');

        $chartData = [
            'labels' => $dates->map(fn($date) => Carbon::parse($date)->format('D'))->toArray(),
            'revenue' => $dates->map(fn($date) => $salesData->get($date)->revenue ?? 0)->toArray(),
            'volume' => $dates->map(fn($date) => $salesData->get($date)->volume ?? 0)->toArray(),
        ];

        // Top Products by Revenue
        $topProductData = SaleItem::selectRaw('product_id, SUM(subtotal) as total_revenue')
            ->groupBy('product_id')
            ->orderByDesc('total_revenue')
            ->take(5)
            ->get();

        $topProducts = Product::with('category')
            ->whereIn('id', $topProductData->pluck('product_id'))
            ->get()
            ->map(function ($product) use ($topProductData) {
                $product->total_revenue = $topProductData->where('product_id', $product->id)->first()->total_revenue;
                return $product;
            })
            ->sortByDesc('total_revenue');

        $lowStockProducts = Product::lowStock()->with('category')->get();
        $recentSales = Sale::with('customer')->latest()->take(5)->get();

        return view('dashboard', compact('stats', 'chartData', 'topProducts', 'lowStockProducts', 'recentSales'));
    }
}
