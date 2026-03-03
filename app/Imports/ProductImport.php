<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Try to find category and supplier by name to make import easier
        $category = Category::firstOrCreate(['name' => $row['category']]);
        $supplier = Supplier::firstOrCreate(['name' => $row['supplier']]);

        return new Product([
            'name'           => $row['name'],
            'barcode'        => $row['barcode'],
            'category_id'    => $category->id,
            'supplier_id'    => $supplier->id,
            'purchase_price' => $row['purchase_price'],
            'selling_price'  => $row['selling_price'],
            'stock_quantity' => $row['stock_quantity'],
            'reorder_level'  => $row['reorder_level'],
        ]);
    }
}
