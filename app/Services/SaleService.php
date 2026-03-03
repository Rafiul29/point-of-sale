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
     * Handle the sale logic inside a DB transaction.
     *
     * @param array $data
     * @return Sale
     * @throws \Exception
     */
    public function createSale(array $data)
    {
        return DB::transaction(function () use ($data) {
            // 1. Create the Sale record
            $sale = Sale::create([
                'customer_id' => $data['customer_id'] ?? null,
                'user_id' => Auth::id() ?? 1, // Fallback to 1 for dev/testing if not auth
                'total_amount' => $data['total_amount'],
                'tax_amount' => $data['tax_amount'],
                'grand_total' => $data['grand_total'],
                'paid_amount' => $data['paid_amount'],
                'due_amount' => $data['due_amount'],
                'payment_method' => $data['payment_method'],
                'status' => $data['due_amount'] > 0 ? 'Due' : 'Paid',
            ]);

            // 2. Process items
            foreach ($data['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);

                // Create Sale Item
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'subtotal' => $item['subtotal'],
                ]);

                // Decrement stock_quantity
                $product->decrement('stock_quantity', $item['quantity']);
            }

            // 3. Handle Customer Ledger if there's a due amount
            if ($sale->due_amount > 0 && $sale->customer_id) {
                $customer = Customer::findOrFail($sale->customer_id);
                
                // Track current balance before update
                $previousBalance = $customer->balance;
                $newBalance = $previousBalance + $sale->due_amount;

                // Create ledger entry
                CustomerLedger::create([
                    'customer_id' => $sale->customer_id,
                    'sale_id' => $sale->id,
                    'debit' => $sale->due_amount,
                    'credit' => 0,
                    'balance' => $newBalance,
                    'description' => "Credit sale ID: " . $sale->id,
                ]);

                // Update customer total balance
                $customer->update(['balance' => $newBalance]);
            }

            return $sale;
        });
    }
}
