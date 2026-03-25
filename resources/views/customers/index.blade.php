@extends('layouts.app')

@section('header', 'CRM Gateway')

@section('content')
<div class="flex flex-col gap-8">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Dashboard Stats -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-slate-900 rounded-[2.5rem] p-8 text-white shadow-2xl relative overflow-hidden group">
                <div class="relative z-10">
                    <p class="text-[10px] font-bold uppercase tracking-widest opacity-40 mb-2">Active Database</p>
                    <h2 class="text-4xl font-extrabold">{{ $customers->count() }}</h2>
                    <p class="text-xs font-medium text-slate-400 mt-2">Verified Customers</p>
                </div>
                <div class="absolute right-[-20px] bottom-[-20px] text-white opacity-5 group-hover:rotate-12 transition-transform duration-500">
                    <i class="fas fa-users text-9xl"></i>
                </div>
            </div>

            <div class="bg-white rounded-[2rem] p-8 border border-slate-100 shadow-sm">
                <h3 class="text-lg font-extrabold text-slate-900 mb-6">Quick Register</h3>
                <form action="{{ route('customers.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <x-input name="name" label="Full Identity" required placeholder="John Doe" />
                    <x-input name="email" label="Digital Mail" placeholder="john@example.com" />
                    <x-input name="phone" label="Mobile Connection" placeholder="+1..." />
                    <x-input name="address" label="Physical Address" placeholder="Street, City, Country" />
                    <x-input name="balance" label="Initial Balance" type="number" step="0.01" min="0" value="0" />
                    <div class="pt-2">
                        <x-button class="w-full !py-4 shadow-indigo-600/20">
                            Register Client
                        </x-button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Customer Ledger List -->
        <div class="lg:col-span-3">
             <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
                <div class="px-10 py-8 border-b border-slate-50 flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-extrabold text-slate-900">Customer Base</h2>
                        <p class="text-sm font-medium text-slate-400 mt-1">Direct management of your client portfolio</p>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-50/50">
                                <th class="px-10 py-5 text-[10px] font-bold uppercase tracking-widest text-slate-400">Profile</th>
                                <th class="px-8 py-5 text-[10px] font-bold uppercase tracking-widest text-slate-400">Communication</th>
                                <th class="px-8 py-5 text-[10px] font-bold uppercase tracking-widest text-slate-400">Wallet Balance</th>
                                <th class="px-10 py-5 text-right text-[10px] font-bold uppercase tracking-widest text-slate-400">Manage</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($customers as $customer)
                            <tr class="group hover:bg-slate-50/30 transition-colors">
                                <td class="px-10 py-6">
                                    <div class="flex items-center gap-4">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($customer->name) }}&background=6366f1&color=fff" class="h-10 w-10 rounded-xl" alt="">
                                        <div>
                                            <p class="text-sm font-bold text-slate-900">{{ $customer->name }}</p>
                                            <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-tighter">Gold Tier Member</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="space-y-0.5">
                                        <p class="text-xs font-bold text-slate-600">{{ $customer->email ?? 'no-email@system' }}</p>
                                        <p class="text-[10px] font-bold text-slate-400">{{ $customer->phone ?? 'N/A' }}</p>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <span class="text-sm font-extrabold {{ $customer->balance < 0 ? 'text-rose-500' : 'text-emerald-500' }}">
                                        ${{ number_format($customer->balance, 2) }}
                                    </span>
                                </td>
                                <td class="px-10 py-6 text-right space-x-2">
                                     <a href="{{ route('customers.show', $customer) }}" class="h-9 w-9 inline-flex items-center justify-center rounded-xl bg-slate-100 text-slate-400 hover:bg-slate-900 hover:text-white transition-all shadow-sm" title="View customer">
                                         <i class="fas fa-eye text-xs"></i>
                                     </a>
                                     <a href="{{ route('customers.edit', $customer) }}" class="h-9 w-9 inline-flex items-center justify-center rounded-xl bg-slate-100 text-slate-400 hover:bg-indigo-600 hover:text-white transition-all shadow-sm" title="Edit customer">
                                         <i class="fas fa-edit text-xs"></i>
                                     </a>
                                     <form action="{{ route('customers.destroy', $customer) }}" method="POST" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="h-9 w-9 inline-flex items-center justify-center rounded-xl bg-rose-50 text-rose-400 hover:bg-rose-500 hover:text-white transition-all shadow-sm" onclick="return confirm('Archive client?')" title="Archive customer">
                                            <i class="fas fa-trash-alt text-xs"></i>
                                        </button>
                                     </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="py-24 text-center">
                                    <i class="fas fa-user-friends text-6xl opacity-10 mb-4"></i>
                                    <p class="text-sm font-bold text-slate-300">No customers registered in database</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
