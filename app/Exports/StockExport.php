<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StockExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Product::select('id', 'name', 'barcode', 'stock_quantity', 'reorder_level')->get();
    }

    public function headings(): array
    {
        return ['ID', 'Product Name', 'Barcode', 'Current Stock', 'Reorder Level'];
    }
}
