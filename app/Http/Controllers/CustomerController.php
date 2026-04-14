<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Setting;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::latest()->get();
        $settings = Setting::pluck('value', 'key');
        return view('customers.index', compact('customers', 'settings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:customers',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:1000',
            'balance' => 'nullable|numeric|min:0',
        ]);

        $requestData = $request->only(['name', 'email', 'phone', 'address', 'balance']);
        $requestData['balance'] = $requestData['balance'] ?? 0;

        Customer::create($requestData);

        return redirect()->route('customers.index')->with('success', 'Customer profile created.');
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:customers,email,' . $customer->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:1000',
            'balance' => 'nullable|numeric|min:0',
        ]);

        $requestData = $request->only(['name', 'email', 'phone', 'address', 'balance']);
        $requestData['balance'] = $requestData['balance'] ?? 0;

        $customer->update($requestData);

        return redirect()->route('customers.index')->with('success', 'Customer updated.');
    }

    public function show(Customer $customer)
    {
        return view('customers.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function destroy(Customer $customer)
    {
        if ($customer->sales()->count() > 0) {
            return redirect()->route('customers.index')->with('error', 'Cannot delete customer with transaction history.');
        }

        $customer->delete();
        return redirect()->route('customers.index')->with('success', 'Customer removed.');
    }
}
