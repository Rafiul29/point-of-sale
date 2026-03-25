@extends('layouts.app')

@section('header', 'Customer Profile')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900">{{ $customer->name }}</h1>
                <p class="text-sm font-medium text-slate-500">Customer details and transaction summary</p>
            </div>
            <div class="space-x-2">
                <a href="{{ route('customers.index') }}" class="rounded-xl px-4 py-2 text-sm font-bold border border-slate-200 text-slate-600 hover:bg-slate-50">Back to list</a>
                <a href="{{ route('customers.edit', $customer) }}" class="rounded-xl px-4 py-2 text-sm font-bold bg-indigo-600 text-white hover:bg-indigo-700">Edit</a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="space-y-3">
                <p class="text-xs font-bold uppercase tracking-wide text-slate-400">Email</p>
                <p class="text-sm font-semibold text-slate-700">{{ $customer->email ?? 'N/A' }}</p>
            </div>
            <div class="space-y-3">
                <p class="text-xs font-bold uppercase tracking-wide text-slate-400">Phone</p>
                <p class="text-sm font-semibold text-slate-700">{{ $customer->phone ?? 'N/A' }}</p>
            </div>
            <div class="md:col-span-2 space-y-3">
                <p class="text-xs font-bold uppercase tracking-wide text-slate-400">Address</p>
                <p class="text-sm font-semibold text-slate-700">{{ $customer->address ?? 'N/A' }}</p>
            </div>
            <div class="space-y-3">
                <p class="text-xs font-bold uppercase tracking-wide text-slate-400">Wallet Balance</p>
                <p class="text-2xl font-extrabold {{ $customer->balance < 0 ? 'text-rose-500' : 'text-emerald-500' }}">${{ number_format($customer->balance, 2) }}</p>
            </div>
            <div class="space-y-3">
                <p class="text-xs font-bold uppercase tracking-wide text-slate-400">Joined</p>
                <p class="text-sm font-semibold text-slate-700">{{ $customer->created_at->format('F j, Y') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection