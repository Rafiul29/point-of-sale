<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Customer;
use App\Services\SaleService;

class POSController extends Controller
{
    protected $saleService;

    public function __construct(SaleService $saleService)
    {
        $this->saleService = $saleService;
    }

    public function index()
    {
        $customers = Customer::orderBy('name')->get();
        return view('pos.index', compact('customers'));
    }

    public function searchProduct(Request $request)
    {
        $query = $request->get('query');
        
        $products = Product::where('name', 'LIKE', "%{$query}%")
            ->orWhere('barcode', 'LIKE', "%{$query}%")
            ->orWhereHas('category', function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%");
            })
            ->orWhereHas('subcategory', function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%");
            })
            ->with(['category', 'subcategory'])
            ->get();

        return response()->json([
            'success' => true,
            'products' => $products
        ]);
    }

    public function searchCustomer(Request $request)
    {
        $query = $request->get('query');
        
        $customers = Customer::where('name', 'LIKE', "%{$query}%")
            ->orWhere('email', 'LIKE', "%{$query}%")
            ->orWhere('phone', 'LIKE', "%{$query}%")
            ->orWhere('address', 'LIKE', "%{$query}%")
            ->get();

        return response()->json([
            'success' => true,
            'customers' => $customers
        ]);
    }

    public function store(Request $request)
    {
        try {
            $data = $request->all();

            // Handle customer sync/creation
            if (!empty($data['customer_data'])) {
                $customerData = $data['customer_data'];
                if (!empty($customerData['id'])) {
                    $customer = Customer::find($customerData['id']);
                    if ($customer) {
                        $customer->update([
                            'name' => $customerData['name'],
                            'email' => $customerData['email'] ?? null,
                            'phone' => $customerData['phone'] ?? null,
                            'address' => $customerData['address'] ?? null,
                        ]);
                        $data['customer_id'] = $customer->id;
                    }
                } else if (!empty($customerData['name'])) {
                    $customer = Customer::create([
                        'name' => $customerData['name'],
                        'email' => $customerData['email'] ?? null,
                        'phone' => $customerData['phone'] ?? null,
                        'address' => $customerData['address'] ?? null,
                    ]);
                    $data['customer_id'] = $customer->id;
                }
            }

            $sale = $this->saleService->createSale($data);
            return response()->json([
                'success' => true,
                'message' => 'Sale finalized successfully',
                'sale_id' => $sale->id
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
