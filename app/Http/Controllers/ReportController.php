<?php

namespace App\Http\Controllers;

use App\Exports\SalesReportExport;
use App\Models\Sale;
use App\Models\Purchase;
use App\Models\Product;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{

    public function salesSummary(Request $request)
    {
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->startOfMonth();
        $endDate   = $request->end_date   ? Carbon::parse($request->end_date)   : Carbon::now()->endOfMonth();

        $start = $startDate->copy()->startOfDay();
        $end   = $endDate->copy()->endOfDay();

        $query = Sale::with('customer')
            ->whereBetween('created_at', [$start, $end]);

        $stats = [
            'total_revenue' => (clone $query)->sum('grand_total'),
            'total_tax'     => (clone $query)->sum('tax_amount'),
            'total_profit'  => $this->calculateProfit($start, $end),
            'sales_count'   => (clone $query)->count(),
        ];

        $sales = $query->latest()->paginate(15)->withQueryString();

        return view('reports.sales', compact('sales', 'stats', 'startDate', 'endDate'));
    }

    public function exportSales(Request $request)
    {
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->startOfMonth();
        $endDate   = $request->end_date   ? Carbon::parse($request->end_date)   : Carbon::now()->endOfMonth();

        $fileName = 'sales_report_' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new SalesReportExport($startDate, $endDate), $fileName);
    }

    public function inventoryReport()
    {
        $products = Product::with(['category', 'supplier'])->get();
        return view('reports.inventory', compact('products'));
    }

    public function salesByPeriod(Request $request)
    {
        $period = $request->get('period', 'daily');

        if ($period === 'weekly') {
            $from = Carbon::now()->subWeeks(11)->startOfWeek();
            $to   = Carbon::now()->endOfWeek();

            $rows = DB::table('sales')
                ->whereBetween('created_at', [$from, $to])
                ->selectRaw("YEARWEEK(created_at, 1) as period_key,
                             MIN(DATE(created_at))   as week_start,
                             COUNT(*)                as sales_count,
                             SUM(grand_total)        as revenue,
                             SUM(tax_amount)         as tax")
                ->groupByRaw('YEARWEEK(created_at, 1)')
                ->orderByRaw('YEARWEEK(created_at, 1) ASC')
                ->get()
                ->map(function ($r) {
                    $r->label = 'Wk ' . Carbon::parse($r->week_start)->format('M d');
                    return $r;
                });
        } else {
            $from = Carbon::now()->subDays(29)->startOfDay();
            $to   = Carbon::now()->endOfDay();

            $rows = DB::table('sales')
                ->whereBetween('created_at', [$from, $to])
                ->selectRaw("DATE(created_at)  as period_key,
                             DATE(created_at)  as week_start,
                             COUNT(*)          as sales_count,
                             SUM(grand_total)  as revenue,
                             SUM(tax_amount)   as tax")
                ->groupByRaw('DATE(created_at)')
                ->orderByRaw('DATE(created_at) ASC')
                ->get()
                ->map(function ($r) {
                    $r->label = Carbon::parse($r->period_key)->format('M d');
                    return $r;
                });
        }

        $totals = [
            'revenue'     => $rows->sum('revenue'),
            'tax'         => $rows->sum('tax'),
            'sales_count' => $rows->sum('sales_count'),
        ];

        $todayStats = [
            'revenue'     => Sale::whereDate('created_at', today())->sum('grand_total'),
            'sales_count' => Sale::whereDate('created_at', today())->count(),
        ];

        $weekStats = [
            'revenue'     => Sale::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->sum('grand_total'),
            'sales_count' => Sale::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count(),
        ];

        return view('reports.daily-weekly', compact('rows', 'totals', 'period', 'todayStats', 'weekStats'));
    }

    public function topProducts(Request $request)
    {
        $limit     = (int) $request->get('limit', 10);
        $sortBy    = $request->get('sort', 'revenue');
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->startOfMonth();
        $endDate   = $request->end_date   ? Carbon::parse($request->end_date)   : Carbon::now()->endOfMonth();

        $orderCol = $sortBy === 'qty' ? 'total_qty' : 'total_revenue';

        $topProducts = DB::table('sale_items')
            ->join('sales',    'sales.id',    '=', 'sale_items.sale_id')
            ->join('products', 'products.id', '=', 'sale_items.product_id')
            ->whereBetween('sales.created_at', [$startDate->copy()->startOfDay(), $endDate->copy()->endOfDay()])
            ->selectRaw("products.id,
                         products.name,
                         products.barcode,
                         products.selling_price,
                         products.stock_quantity,
                         SUM(sale_items.quantity)  as total_qty,
                         SUM(sale_items.subtotal)  as total_revenue")
            ->groupBy('products.id', 'products.name', 'products.barcode', 'products.selling_price', 'products.stock_quantity')
            ->orderByDesc($orderCol)
            ->limit($limit)
            ->get();

        $grandRevenue = $topProducts->sum('total_revenue');
        $grandQty     = $topProducts->sum('total_qty');

        return view('reports.top-products', compact(
            'topProducts',
            'grandRevenue',
            'grandQty',
            'startDate',
            'endDate',
            'sortBy',
            'limit'
        ));
    }

    public function lowStockAlert()
    {
        $lowStockProducts = Product::lowStock()
            ->with(['category', 'supplier'])
            ->orderByRaw('stock_quantity - reorder_level ASC')
            ->get();

        $outOfStock = $lowStockProducts->where('stock_quantity', 0)->count();
        $critical   = $lowStockProducts->where('stock_quantity', '>', 0)->count();

        return view('reports.low-stock', compact('lowStockProducts', 'outOfStock', 'critical'));
    }

    private function calculateProfit($start, $end)
    {
        return DB::table('sale_items')
            ->join('sales', 'sales.id', '=', 'sale_items.sale_id')
            ->join('products', 'products.id', '=', 'sale_items.product_id')
            ->whereBetween('sales.created_at', [$start, $end])
            ->selectRaw('SUM((sale_items.unit_price - products.purchase_price) * sale_items.quantity) as total_profit')
            ->value('total_profit') ?? 0;
    }
}
