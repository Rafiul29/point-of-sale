<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProductExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Product::with(['category', 'supplier'])->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Barcode',
            'Category',
            'Supplier',
            'Purchase Price',
            'Selling Price',
            'Stock Quantity',
            'Reorder Level',
        ];
    }

    public function map($product): array
    {
        return [
            $product->id,
            $product->name,
            $product->barcode,
            $product->category->name ?? 'N/A',
            $product->supplier->name ?? 'N/A',
            $product->purchase_price,
            $product->selling_price,
            $product->stock_quantity,
            $product->reorder_level,
        ];
    }
}
