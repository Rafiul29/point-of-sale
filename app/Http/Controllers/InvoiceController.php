<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;

class InvoiceController extends Controller
{
    public function show($id)
    {
        $sale = Sale::with(['customer', 'items.product', 'user'])->findOrFail($id);
        return view('invoices.show', compact('sale'));
    }
}
