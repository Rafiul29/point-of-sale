<?php

namespace App\Exports;

use App\Models\Sale;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SalesReportExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate->copy()->startOfDay();
        $this->endDate = $endDate->copy()->endOfDay();
    }

    public function collection()
    {
        $sales = Sale::with(['customer', 'items.product', 'user'])
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->orderBy('created_at', 'asc')
            ->get();

        $rows = collect();

        foreach ($sales as $sale) {
            foreach ($sale->items as $item) {
                $rows->push((object) [
                    'transaction_ref' => '#TXN-' . str_pad($sale->id, 6, '0', STR_PAD_LEFT),
                    'date' => $sale->created_at->format('Y-m-d H:i:s'),
                    'customer_name' => $sale->customer->name ?? 'Walk-in',
                    'customer_phone' => $sale->customer->phone ?? 'N/A',
                    'product_name' => $item->product->name ?? 'Unknown',
                    'product_sku' => $item->product->barcode ?? 'N/A',
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'line_total' => $item->subtotal,
                    'payment_method' => $sale->payment_method,
                    'status' => $sale->status,
                    'grand_total' => $sale->grand_total,
                    'tax_amount' => $sale->tax_amount,
                    'paid_amount' => $sale->paid_amount,
                    'cash_price' => $item->unit_price,
                    'authorized_by' => $sale->user->name ?? 'System',
                ]);
            }
        }

        return $rows;
    }

    public function headings(): array
    {
        return [
            'Transaction Ref',
            'Date',
            'Customer Name',
            'Customer Phone',
            'Product',
            'SKU',
            'Quantity',
            'Unit Price',
            'Line Total',
            'Payment Method',
            'Status',
            'Grand Total',
            'Tax Amount',
            'Paid Amount',
            'Cash Price',
            'Authorized By',
        ];
    }

    public function map($row): array
    {
        return [
            $row->transaction_ref,
            $row->date,
            $row->customer_name,
            $row->customer_phone,
            $row->product_name,
            $row->product_sku,
            $row->quantity,
            number_format($row->unit_price, 2),
            number_format($row->line_total, 2),
            $row->payment_method,
            $row->status,
            number_format($row->grand_total, 2),
            number_format($row->tax_amount, 2),
            number_format($row->paid_amount, 2),
            number_format($row->cash_price, 2),
            $row->authorized_by,
        ];
    }
}
