@extends('layouts.app')

@section('header', 'Category Management')

@section('content')
<div class="flex flex-col gap-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Add Category Form -->
        <div class="lg:col-span-1">
            <div class="rounded-[2rem] bg-white p-8 shadow-sm border border-slate-100 sticky top-24">
                <h3 class="text-xl font-extrabold text-slate-900 mb-6">Create New Category</h3>
                <form action="{{ route('categories.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <x-input label="Category Name" name="name" placeholder="e.g. Beverages" required />
                    <div class="space-y-2">
                        <label class="block text-xs font-extrabold uppercase tracking-widest text-slate-400 ms-1">Description</label>
                        <textarea name="description" rows="4" class="w-full border-0 bg-slate-100/50 py-4 px-5 text-sm font-semibold rounded-2xl focus:ring-4 focus:ring-indigo-600/10 transition-all placeholder:text-slate-300" placeholder="Optional category details..."></textarea>
                    </div>
                    <x-button class="w-full !py-4 shadow-indigo-500/20">
                        <i class="fas fa-plus mr-2 opacity-70"></i> Add Category
                    </x-button>
                </form>
            </div>
        </div>

        <!-- Categories List -->
        <div class="lg:col-span-2">
            <div class="rounded-[2rem] bg-white shadow-sm border border-slate-100 overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-50 flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-extrabold text-slate-900">Active Categories</h2>
                        <p class="text-xs font-medium text-slate-400">Manage your product groupings</p>
                    </div>
                    <span class="rounded-full bg-indigo-50 px-3 py-1 text-[10px] font-bold text-indigo-600">{{ $categories->count() }} Total</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-50/50">
                                <th class="px-8 py-4 text-[10px] font-bold uppercase tracking-widest text-slate-400">Category Name</th>
                                <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-slate-400">Total Products</th>
                                <th class="px-8 py-4 text-right text-[10px] font-bold uppercase tracking-widest text-slate-400">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($categories as $category)
                            <tr class="group hover:bg-slate-50/50 transition-colors">
                                <td class="px-8 py-5">
                                    <div>
                                        <p class="text-sm font-bold text-slate-900">{{ $category->name }}</p>
                                        <p class="text-xs font-medium text-slate-400 truncate max-w-xs">{{ $category->description ?? 'No description' }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <span class="inline-flex items-center rounded-lg bg-slate-100 px-2.5 py-1 text-[10px] font-bold text-slate-600">
                                        {{ $category->products_count }} Products
                                    </span>
                                </td>
                                <td class="px-8 py-5 text-right space-x-2">
                                    <button class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-slate-100 text-slate-400 hover:bg-slate-900 hover:text-white transition-all">
                                        <i class="fas fa-edit text-xs"></i>
                                    </button>
                                    <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-slate-100 text-slate-400 hover:bg-rose-500 hover:text-white transition-all" onclick="return confirm('Are you sure?')">
                                            <i class="fas fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="py-20 text-center text-slate-300">
                                    <i class="fas fa-folder-open text-5xl mb-4 opacity-10"></i>
                                    <p class="text-sm font-bold">No categories found.</p>
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
