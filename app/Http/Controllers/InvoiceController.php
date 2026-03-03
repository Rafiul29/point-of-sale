<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function show($id)
    {
        $sale = Sale::with(['customer', 'items.product', 'user'])->findOrFail($id);
        return view('invoices.show', compact('sale'));
    }

    public function download($id)
    {
        $sale = Sale::with(['customer', 'items.product', 'user'])->findOrFail($id);
        $pdf = Pdf::loadView('reports.invoice', compact('sale'))
                  ->setPaper('a4', 'portrait');
                  
        return $pdf->stream('invoice_'.str_pad($sale->id, 6, '0', STR_PAD_LEFT).'.pdf');
    }
}
