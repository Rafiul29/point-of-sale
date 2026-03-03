<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use Carbon\Carbon;

class CashierDashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $user = auth()->user();

        $myStats = [
            'sales_today'        => Sale::where('user_id', $user->id)->whereDate('created_at', $today)->sum('grand_total'),
            'transactions_today' => Sale::where('user_id', $user->id)->whereDate('created_at', $today)->count(),
            'avg_sale_today'     => Sale::where('user_id', $user->id)->whereDate('created_at', $today)->avg('grand_total') ?? 0,
        ];

        $recentSales = Sale::with('customer')
            ->where('user_id', $user->id)
            ->latest()
            ->take(8)
            ->get();

        return view('cashier.dashboard', compact('myStats', 'recentSales'));
    }
}
