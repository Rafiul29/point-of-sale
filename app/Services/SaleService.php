<?php

namespace App\Services;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use App\Models\Customer;
use App\Models\CustomerLedger;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SaleService
{
    /**
     *
     * @param array $data
     * @return Sale
     * @throws \Exception
     */
    public function createSale(array $data)
    {
        return DB::transaction(function () use ($data) {
            $sale = Sale::create([
                'customer_id'    => $data['customer_id'] ?? null,
                'user_id'        => Auth::id() ?? 1,
                'total_amount'   => $data['total_amount'],
                'tax_amount'     => $data['tax_amount'],
                'grand_total'    => $data['grand_total'],
                'paid_amount'    => $data['paid_amount'],
                'due_amount'     => $data['due_amount'],
                'payment_method' => $data['payment_method'],
                'status'         => $data['due_amount'] > 0 ? 'Due' : 'Paid',
            ]);


            $itemsSummary = [];
            foreach ($data['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);

                $oldQty = $product->stock_quantity;

                SaleItem::create([
                    'sale_id'    => $sale->id,
                    'product_id' => $product->id,
                    'quantity'   => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'subtotal'   => $item['subtotal'],
                ]);

                $product->decrement('stock_quantity', $item['quantity']);

                $itemsSummary[] = [
                    'product_id'   => $product->id,
                    'product_name' => $product->name,
                    'qty_sold'     => $item['quantity'],
                    'stock_before' => $oldQty,
                    'stock_after'  => $oldQty - $item['quantity'],
                    'unit_price'   => $item['unit_price'],
                ];
            }


            AuditLogService::log('sale_created', $sale, [], [
                'sale_id'        => $sale->id,
                'grand_total'    => $sale->grand_total,
                'payment_method' => $sale->payment_method,
                'status'         => $sale->status,
                'items'          => $itemsSummary,
            ]);


            if ($sale->due_amount > 0 && $sale->customer_id) {
                $customer = Customer::findOrFail($sale->customer_id);

                $previousBalance = $customer->balance;
                $newBalance      = $previousBalance + $sale->due_amount;

                CustomerLedger::create([
                    'customer_id' => $sale->customer_id,
                    'sale_id'     => $sale->id,
                    'debit'       => $sale->due_amount,
                    'credit'      => 0,
                    'balance'     => $newBalance,
                    'description' => "Credit sale ID: " . $sale->id,
                ]);

                $customer->update(['balance' => $newBalance]);
            }

            return $sale;
        });
    }
}
