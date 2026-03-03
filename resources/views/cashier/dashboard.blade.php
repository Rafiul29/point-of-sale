@extends('layouts.app')

@section('header', 'My Dashboard')

@section('content')
<div class="flex flex-col gap-10">

    <!-- Greeting Banner -->
    <div class="relative overflow-hidden rounded-[3rem] bg-gradient-to-br from-indigo-600 via-indigo-700 to-purple-700 p-10 text-white shadow-2xl shadow-indigo-600/30">
        <div class="relative z-10 flex items-center justify-between">
            <div>
                <p class="text-[10px] font-black uppercase tracking-[0.3em] opacity-60">Welcome back</p>
                <h2 class="mt-2 text-4xl font-black tracking-tight">{{ auth()->user()->name }}</h2>
                <p class="mt-2 text-sm font-semibold text-indigo-200">Cashier · {{ now()->format('l, F j Y') }}</p>
            </div>
            <a href="{{ route('pos.index') }}" class="flex items-center gap-3 rounded-2xl bg-white px-8 py-4 text-sm font-black text-indigo-700 shadow-xl shadow-indigo-900/30 transition-all hover:scale-105 hover:shadow-2xl">
                <i class="fas fa-cash-register text-lg"></i>
                Open Terminal
            </a>
        </div>
        <i class="fas fa-cash-register absolute -bottom-8 -right-8 text-[14rem] opacity-[0.07] rotate-12"></i>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Sales Today -->
        <div class="group relative overflow-hidden rounded-[2.5rem] bg-white p-8 shadow-sm border border-slate-100 transition-all hover:shadow-xl hover:shadow-slate-200/50 hover:scale-[1.02]">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-400">My Revenue Today</p>
                    <h3 class="mt-4 text-4xl font-black tracking-tighter text-slate-900">
                        {{ $settings['currency_symbol'] ?? '$' }}{{ number_format($myStats['sales_today'], 2) }}
                    </h3>
                </div>
                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-indigo-50 text-2xl text-indigo-600">
                    <i class="fas fa-coins"></i>
                </div>
            </div>
            <div class="mt-6 flex items-center gap-2 text-xs font-bold text-emerald-500">
                <div class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></div>
                Live · Resets at midnight
            </div>
        </div>

        <!-- Transactions Today -->
        <div class="group relative overflow-hidden rounded-[2.5rem] bg-slate-900 p-8 text-white shadow-sm transition-all hover:shadow-xl hover:scale-[1.02]">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-400">Transactions Today</p>
                    <h3 class="mt-4 text-4xl font-black tracking-tighter">{{ $myStats['transactions_today'] }}</h3>
                </div>
                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white/10 text-2xl text-indigo-400">
                    <i class="fas fa-receipt"></i>
                </div>
            </div>
            <div class="mt-6 text-[10px] font-bold uppercase tracking-widest text-slate-500">
                Sales processed by you
            </div>
        </div>

        <!-- Avg Sale -->
        <div class="group relative overflow-hidden rounded-[2.5rem] bg-white p-8 shadow-sm border border-slate-100 transition-all hover:shadow-xl hover:shadow-slate-200/50 hover:scale-[1.02]">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-400">Avg. Sale Value</p>
                    <h3 class="mt-4 text-4xl font-black tracking-tighter text-slate-900">
                        {{ $settings['currency_symbol'] ?? '$' }}{{ number_format($myStats['avg_sale_today'], 2) }}
                    </h3>
                </div>
                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-purple-50 text-2xl text-purple-600">
                    <i class="fas fa-chart-bar"></i>
                </div>
            </div>
            <div class="mt-6 text-[10px] font-bold uppercase tracking-widest text-slate-400">
                Per transaction · today
            </div>
        </div>
    </div>

    <!-- Recent Sales & Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        <!-- Recent Sales -->
        <div class="lg:col-span-8">
            <div class="rounded-[3rem] bg-white p-10 shadow-sm border border-slate-100">
                <div class="mb-8 flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-black tracking-tight text-slate-900">My Recent Sales</h2>
                        <p class="text-sm font-medium text-slate-400 mt-1">Latest transactions processed by you</p>
                    </div>
                    <a href="{{ route('pos.index') }}" class="flex items-center gap-2 rounded-2xl bg-indigo-600 px-5 py-2.5 text-xs font-black text-white shadow-lg shadow-indigo-500/30 transition-all hover:bg-indigo-700">
                        <i class="fas fa-plus"></i>
                        New Sale
                    </a>
                </div>

                @if($recentSales->isEmpty())
                <div class="flex flex-col items-center justify-center py-20 text-slate-300">
                    <div class="h-24 w-24 rounded-full bg-slate-50 flex items-center justify-center text-4xl mb-6">
                        <i class="fas fa-receipt"></i>
                    </div>
                    <p class="text-sm font-bold text-slate-400">No sales recorded yet. Head to the terminal!</p>
                </div>
                @else
                <div class="overflow-hidden rounded-2xl border border-slate-100">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-slate-50 text-left">
                                <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider text-slate-400">Invoice #</th>
                                <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider text-slate-400">Customer</th>
                                <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider text-slate-400">Amount</th>
                                <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider text-slate-400">Method</th>
                                <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider text-slate-400">Time</th>
                                <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider text-slate-400">Invoice</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($recentSales as $sale)
                            <tr class="transition-colors hover:bg-slate-50/50">
                                <td class="px-6 py-4">
                                    <span class="font-black text-indigo-600">#{{ str_pad($sale->id, 5, '0', STR_PAD_LEFT) }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-semibold text-slate-800">{{ $sale->customer->name ?? 'Walk-in' }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-black text-slate-900">{{ $settings['currency_symbol'] ?? '$' }}{{ number_format($sale->grand_total, 2) }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center gap-1 rounded-full px-3 py-1 text-[10px] font-black uppercase tracking-widest
                                        {{ $sale->payment_method === 'cash' ? 'bg-emerald-50 text-emerald-700' : 'bg-indigo-50 text-indigo-700' }}">
                                        <i class="fas {{ $sale->payment_method === 'cash' ? 'fa-money-bill-wave' : 'fa-credit-card' }} text-[8px]"></i>
                                        {{ ucfirst($sale->payment_method) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-xs text-slate-400 font-semibold">{{ $sale->created_at->diffForHumans() }}</td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('invoice.show', $sale->id) }}" target="_blank" class="flex h-8 w-8 items-center justify-center rounded-xl bg-slate-100 text-slate-500 transition-all hover:bg-indigo-100 hover:text-indigo-600">
                                        <i class="fas fa-external-link-alt text-xs"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>

        <!-- Quick Actions Panel -->
        <div class="lg:col-span-4 flex flex-col gap-6">
            <!-- Quick Actions -->
            <div class="rounded-[3rem] bg-slate-900 p-8 text-white">
                <h3 class="text-lg font-black mb-6">Quick Actions</h3>
                <div class="space-y-3">
                    <a href="{{ route('pos.index') }}" class="flex items-center gap-4 rounded-2xl bg-indigo-600 px-5 py-4 text-sm font-bold transition-all hover:bg-indigo-500 hover:scale-[1.02]">
                        <i class="fas fa-cash-register w-5 text-center text-indigo-200"></i>
                        Open POS Terminal
                    </a>
                    <a href="{{ route('customers.index') }}" class="flex items-center gap-4 rounded-2xl bg-white/5 px-5 py-4 text-sm font-bold transition-all hover:bg-white/10">
                        <i class="fas fa-users w-5 text-center text-slate-400"></i>
                        View Customers
                    </a>
                </div>
            </div>

            <!-- Role Info Card -->
            <div class="rounded-[3rem] bg-gradient-to-br from-purple-50 to-indigo-50 border border-indigo-100 p-8">
                <div class="flex items-center gap-4 mb-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-600 text-white text-lg shadow-lg shadow-indigo-500/30">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <div>
                        <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Your Role</p>
                        <p class="text-lg font-black text-slate-800">Cashier</p>
                    </div>
                </div>
                <p class="text-xs text-slate-500 font-medium leading-relaxed">
                    You have access to the POS terminal and customer records. Contact an Admin to manage inventory, reports, and system settings.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
