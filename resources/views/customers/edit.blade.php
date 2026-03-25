@extends('layouts.app')

@section('header', 'Edit Customer')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8">
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-2xl font-extrabold text-slate-900">Edit customer profile</h1>
            <a href="{{ route('customers.index') }}" class="rounded-xl px-4 py-2 text-sm font-bold border border-slate-200 text-slate-600 hover:bg-slate-50">Cancel</a>
        </div>

        <form action="{{ route('customers.update', $customer) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <x-input name="name" label="Full Identity" value="{{ old('name', $customer->name) }}" required />
            <x-input name="email" label="Digital Mail" type="email" value="{{ old('email', $customer->email) }}" />
            <x-input name="phone" label="Mobile Connection" value="{{ old('phone', $customer->phone) }}" />
            <div class="space-y-2">
                <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-400 ms-1">Address</label>
                <textarea name="address" rows="3" class="w-full border-0 bg-slate-100/50 py-4 px-5 text-sm font-semibold rounded-2xl focus:ring-4 focus:ring-indigo-600/10 placeholder:text-slate-300">{{ old('address', $customer->address) }}</textarea>
            </div>

            <x-input name="balance" label="Current Balance" type="number" step="0.01" min="0" value="{{ old('balance', $customer->balance) }}" />

            <div class="pt-2 flex justify-end gap-2">
                <button type="submit" class="rounded-xl bg-indigo-600 px-6 py-3 text-sm font-bold text-white hover:bg-indigo-700">Save Changes</button>
                <a href="{{ route('customers.show', $customer) }}" class="rounded-xl border border-slate-200 px-6 py-3 text-sm font-bold text-slate-700 hover:bg-slate-50">View Profile</a>
            </div>
        </form>
    </div>
</div>
@endsection