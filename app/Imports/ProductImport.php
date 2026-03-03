<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
class ProductImport implements ToModel, WithHeadingRow
{
    protected $processedBarcodes = [];

    public function model(array $row)
    {
        $barcode = $row['barcode'] ?? null;
        if (!$barcode) return null;

        // SKIP if barcode exists in DB or was already seen in this file
        if (in_array($barcode, $this->processedBarcodes) || Product::where('barcode', $barcode)->exists()) {
            return null;
        }

        $this->processedBarcodes[] = $barcode;

        // Try to find category and supplier by name to make import easier
        $category = Category::firstOrCreate(['name' => $row['category']]);
        $supplier = Supplier::firstOrCreate(['name' => $row['supplier']]);

        return new Product([
            'name'           => $row['name'],
            'barcode'        => $barcode,
            'category_id'    => $category->id,
            'supplier_id'    => $supplier->id,
            'purchase_price' => $row['purchase_price'],
            'selling_price'  => $row['selling_price'],
            'stock_quantity' => $row['stock_quantity'],
            'reorder_level'  => $row['reorder_level'],
        ]);
    }
}
