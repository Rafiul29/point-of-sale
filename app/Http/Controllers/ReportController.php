<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Purchase;
use App\Models\Product;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function salesSummary(Request $request)
    {
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now()->endOfMonth();

        $query = Sale::with('customer')
            ->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()]);

        // Calculate stats on the query before pagination
        $stats = [
            'total_revenue' => (clone $query)->sum('grand_total'),
            'total_tax' => (clone $query)->sum('tax_amount'),
            'total_profit' => $this->calculateProfit($startDate, $endDate),
            'sales_count' => (clone $query)->count(),
        ];

        // Paginate results
        $sales = $query->latest()->paginate(15)->withQueryString();

        return view('reports.sales', compact('sales', 'stats', 'startDate', 'endDate'));
    }

    public function inventoryReport()
    {
        $products = Product::with(['category', 'supplier'])->get();
        return view('reports.inventory', compact('products'));
    }

    private function calculateProfit($start, $end)
    {
        // Simple profit calculation: Sale Subtotal - Purchase Cost of sold items
        $saleItems = DB::table('sale_items')
            ->join('sales', 'sales.id', '=', 'sale_items.sale_id')
            ->whereBetween('sales.created_at', [$start->startOfDay(), $end->endOfDay()])
            ->select('sale_items.quantity', 'sale_items.unit_price', 'sale_items.product_id')
            ->get();

        $profit = 0;
        foreach ($saleItems as $item) {
            $product = Product::find($item->product_id);
            $costPrice = $product ? $product->purchase_price : 0;
            $profit += ($item->unit_price - $costPrice) * $item->quantity;
        }

        return $profit;
    }
}
