<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Exports\ProductExport;
use App\Imports\ProductImport;
use App\Exports\StockExport;
use App\Imports\StockImport;
use App\Services\BarcodeService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ProductActionController extends Controller
{
    protected $barcodeService;

    public function __construct(BarcodeService $barcodeService)
    {
        $this->barcodeService = $barcodeService;
    }

    // --- Excel Exports ---
    public function exportProducts()
    {
        return Excel::download(new ProductExport, 'products_list.xlsx');
    }

    public function exportStock()
    {
        return Excel::download(new StockExport, 'inventory_status.xlsx');
    }

    // --- Excel Imports ---
    public function importProducts(Request $request)
    {
        $request->validate(['file' => 'required|mimes:xlsx,xls,csv']);
        Excel::import(new ProductImport, $request->file('file'));
        return back()->with('success', 'Products imported successfully.');
    }

    public function importStock(Request $request)
    {
        $request->validate(['file' => 'required|mimes:xlsx,xls,csv']);
        Excel::import(new StockImport, $request->file('file'));
        return back()->with('success', 'Stock levels updated successfully.');
    }

    // --- Barcode PDf ---
    public function generateBarcodes(Request $request)
    {
        $products = Product::whereIn('id', $request->product_ids ?? [])->get();
        if ($products->isEmpty()) {
             $products = Product::all(); // Print all if no selection
        }

        if ($products->isEmpty()) {
            return back()->with('error', 'Inventory is empty. No barcodes to generate.');
        }

        $barcodeData = [];
        foreach ($products as $product) {
            if ($product->barcode) {
                $barcodeData[] = [
                    'name'    => $product->name,
                    'barcode' => $product->barcode,
                    'price'   => $product->selling_price,
                    'image'   => $this->barcodeService->generatePNG($product->barcode)
                ];
            }
        }

        $pdf = Pdf::loadView('reports.barcodes', compact('barcodeData'))
                  ->setPaper('a4', 'portrait');
                  
        return $pdf->stream('inventory_barcodes.pdf');
    }
}
