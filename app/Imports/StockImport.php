<?php

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Row;

class StockImport implements OnEachRow, WithHeadingRow
{
    public function onRow(Row $row)
    {
        $data = $row->toArray();
        
        $product = Product::find($data['id']);
        if (!$product) {
            $product = Product::where('barcode', $data['barcode'])->first();
        }

        if ($product) {
            $product->update([
                'stock_quantity' => $data['current_stock'] ?? $data['stock_quantity'],
                'reorder_level'  => $data['reorder_level'],
            ]);
        }
    }
}
