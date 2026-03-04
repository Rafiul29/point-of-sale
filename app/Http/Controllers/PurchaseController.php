<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Product;
use App\Models\Supplier;
use App\Services\AuditLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function index()
    {
        $purchases = Purchase::with('supplier')->latest()->get();
        return view('purchases.index', compact('purchases'));
    }

    public function create()
    {
        $suppliers = Supplier::all();
        $products  = Product::all();
        return view('purchases.create', compact('suppliers', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id'              => 'required|exists:suppliers,id',
            'purchase_date'            => 'required|date',
            'items'                    => 'required|array|min:1',
            'items.*.product_id'       => 'required|exists:products,id',
            'items.*.quantity'         => 'required|integer|min:1',
            'items.*.unit_price'       => 'required|numeric|min:0',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $totalAmount = 0;
                foreach ($request->items as $item) {
                    $totalAmount += $item['quantity'] * $item['unit_price'];
                }

                $purchase = Purchase::create([
                    'supplier_id'   => $request->supplier_id,
                    'user_id'       => Auth::id(),
                    'purchase_date' => $request->purchase_date,
                    'total_amount'  => $totalAmount,
                    'invoice_no'    => 'PUR-' . strtoupper(uniqid()),
                    'status'        => 'Received',
                ]);

                $stockChanges = [];

                foreach ($request->items as $item) {
                    $purchase->items()->create([
                        'product_id' => $item['product_id'],
                        'quantity'   => $item['quantity'],
                        'unit_price' => $item['unit_price'],
                        'subtotal'   => $item['quantity'] * $item['unit_price'],
                    ]);

                    $product  = Product::find($item['product_id']);
                    $oldStock = $product->stock_quantity;

                    $product->increment('stock_quantity', $item['quantity']);
                    $product->update(['purchase_price' => $item['unit_price']]);

                    $stockChanges[] = [
                        'product_id'   => $product->id,
                        'product_name' => $product->name,
                        'qty_added'    => $item['quantity'],
                        'stock_before' => $oldStock,
                        'stock_after'  => $oldStock + $item['quantity'],
                        'unit_price'   => $item['unit_price'],
                    ];
                }

                AuditLogService::log('stock_in', $purchase, [], [
                    'purchase_id'  => $purchase->id,
                    'invoice_no'   => $purchase->invoice_no,
                    'total_amount' => $totalAmount,
                    'items'        => $stockChanges,
                ]);
            });

            return redirect()->route('purchases.index')->with('success', 'Stock purchase recorded and inventory updated.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error recording purchase: ' . $e->getMessage());
        }
    }

    public function show(Purchase $purchase)
    {
        $purchase->load(['supplier', 'items.product']);
        return view('purchases.show', compact('purchase'));
    }
}
