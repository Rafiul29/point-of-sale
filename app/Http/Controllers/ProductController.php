<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use App\Services\AuditLogService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products   = Product::with(['category', 'supplier'])->latest()->get();
        $categories = Category::all();
        $suppliers  = Supplier::all();
        return view('products.index', compact('products', 'categories', 'suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'barcode'        => 'required|string|unique:products',
            'category_id'    => 'required|exists:categories,id',
            'supplier_id'    => 'required|exists:suppliers,id',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price'  => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'reorder_level'  => 'required|integer|min:0',
        ]);

        $product = Product::create($request->all());

        AuditLogService::log('product_created', $product, [], $product->only([
            'name', 'barcode', 'purchase_price', 'selling_price', 'stock_quantity', 'reorder_level',
        ]));

        return redirect()->route('products.index')->with('success', 'Product registered successfully.');
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'barcode'        => 'required|string|unique:products,barcode,' . $product->id,
            'category_id'    => 'required|exists:categories,id',
            'supplier_id'    => 'required|exists:suppliers,id',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price'  => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'reorder_level'  => 'required|integer|min:0',
        ]);

        $oldValues = $product->only(['name', 'purchase_price', 'selling_price', 'stock_quantity', 'reorder_level']);

        $product->update($request->all());

        $newValues = $product->fresh()->only(['name', 'purchase_price', 'selling_price', 'stock_quantity', 'reorder_level']);

        if ($oldValues !== $newValues) {
            $action = ($oldValues['selling_price'] != $newValues['selling_price'] ||
                       $oldValues['purchase_price'] != $newValues['purchase_price'])
                ? 'price_changed'
                : 'product_updated';

            AuditLogService::log($action, $product, $oldValues, $newValues);
        }

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        if ($product->saleItems()->count() > 0) {
            return redirect()->route('products.index')->with('error', 'Cannot delete product with existing sales history.');
        }

        AuditLogService::log('product_deleted', $product, $product->only([
            'name', 'barcode', 'purchase_price', 'selling_price', 'stock_quantity',
        ]), []);

        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product removed from catalog.');
    }
}
