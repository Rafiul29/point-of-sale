<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Purchase;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        if (auth()->user()->isCashier()) {
            return redirect()->route('cashier.dashboard');
        }

        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();

        $stats = [
            'revenue_today' => Sale::whereDate('created_at', $today)->sum('grand_total'),
            'revenue_month' => Sale::where('created_at', '>=', $thisMonth)->sum('grand_total'),
            'purchase_month' => Purchase::where('created_at', '>=', $thisMonth)->sum('total_amount'),
            'total_products' => Product::count(),
            'total_customers' => Customer::count(),
        ];
        
        $lowStockProducts = Product::lowStock()->with('category')->get();
        $recentSales = Sale::with('customer')->latest()->take(5)->get();

        return view('dashboard', compact('stats', 'lowStockProducts', 'recentSales'));
    }
}
