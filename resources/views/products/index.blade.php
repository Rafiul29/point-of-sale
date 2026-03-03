@extends('layouts.app')

@section('header', 'Product Catalog')

@section('content')
<div class="flex flex-col gap-8">
    <!-- Header with Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-5 gap-6">
        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm">
            <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-slate-400 mb-1">Total SKU</p>
            <h3 class="text-2xl font-extrabold text-slate-900">{{ $products->count() }}</h3>
        </div>
        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm">
            <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-slate-400 mb-1">Active Categories</p>
            <h3 class="text-2xl font-extrabold text-indigo-600">{{ $categories->count() }}</h3>
        </div>
        <div class="bg-indigo-600 p-6 rounded-[2rem] shadow-xl shadow-indigo-500/20 text-white relative overflow-hidden">
            <div class="relative z-10">
                <p class="text-[10px] font-bold uppercase tracking-[0.2em] opacity-60 mb-1">Inventory Value</p>
                <h3 class="text-2xl font-extrabold text-white">{{ $settings['currency_symbol'] ?? '$' }}{{ number_format($products->sum(fn($p) => $p->purchase_price * $p->stock_quantity), 2) }}</h3>
            </div>
            <i class="fas fa-coins absolute bottom-[-10px] right-[-10px] text-6xl opacity-10 rotate-12"></i>
        </div>
        <div class="flex flex-col gap-2">
             <button onclick="document.getElementById('add-product-modal').classList.remove('hidden')" class="bg-slate-900 text-white px-6 py-3.5 rounded-2xl font-extrabold shadow-xl hover:bg-slate-800 transition-all flex items-center gap-3 active:scale-95 text-sm">
                <i class="fas fa-plus"></i> New Product
             </button>
             <button type="button" id="bulk-print-btn" class="bg-white text-slate-700 border border-slate-200 px-6 py-3.5 rounded-2xl font-bold hover:bg-slate-50 transition-all flex items-center gap-3 text-xs">
                <i class="fas fa-barcode"></i> Print Barcodes
             </button>
             <form id="bulk-print-form" action="{{ route('products.barcodes.print') }}" method="GET" target="_blank" class="hidden"></form>
        </div>
        <div class="flex flex-col gap-2">
             <div class="flex gap-2">
                <a href="{{ route('products.export') }}" class="flex-1 bg-emerald-50 text-emerald-600 px-4 py-3 rounded-2xl font-bold text-[10px] uppercase tracking-widest text-center hover:bg-emerald-100 transition-all flex items-center justify-center gap-2">
                    <i class="fas fa-file-export"></i> Export
                </a>
                <button onclick="document.getElementById('import-file').click()" class="flex-1 bg-amber-50 text-amber-600 px-4 py-3 rounded-2xl font-bold text-[10px] uppercase tracking-widest text-center hover:bg-amber-100 transition-all flex items-center justify-center gap-2">
                    <i class="fas fa-file-import"></i> Import
                </button>
             </div>
             <form id="import-form" action="{{ route('products.import') }}" method="POST" enctype="multipart/form-data" class="hidden">
                 @csrf
                 <input type="file" id="import-file" name="file" onchange="document.getElementById('import-form').submit()">
             </form>
             <div class="flex gap-2">
                <a href="{{ route('stock.export') }}" class="flex-1 bg-blue-50 text-blue-600 px-4 py-3 rounded-2xl font-bold text-[10px] uppercase tracking-widest text-center hover:bg-blue-100 transition-all">Stock Export</a>
                <button onclick="document.getElementById('stock-import-file').click()" class="flex-1 bg-slate-50 text-slate-500 px-4 py-3 rounded-2xl font-bold text-[10px] uppercase tracking-widest text-center hover:bg-slate-100 transition-all">Stock Sync</button>
             </div>
             <form id="stock-import-form" action="{{ route('stock.import') }}" method="POST" enctype="multipart/form-data" class="hidden">
                 @csrf
                 <input type="file" id="stock-import-file" name="file" onchange="document.getElementById('stock-import-form').submit()">
             </form>
        </div>
    </div>

    <!-- Product Table -->
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
        <div class="px-10 py-8 border-b border-slate-50">
            <h2 class="text-xl font-extrabold text-slate-900">Registered Inventory</h2>
            <p class="text-sm font-medium text-slate-400 mt-1">Manage pricing and stock levels globally</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-6 py-5 w-10">
                            <input type="checkbox" id="select-all" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-600">
                        </th>
                        <th class="px-4 py-5 text-[10px] font-bold uppercase tracking-widest text-slate-400">Product Identity</th>
                        <th class="px-6 py-5 text-[10px] font-bold uppercase tracking-widest text-slate-400">Category</th>
                        <th class="px-6 py-5 text-[10px] font-bold uppercase tracking-widest text-slate-400">Pricing Cost/Sell</th>
                        <th class="px-6 py-5 text-[10px] font-bold uppercase tracking-widest text-slate-400">Inventory Status</th>
                        <th class="px-10 py-5 text-right text-[10px] font-bold uppercase tracking-widest text-slate-400">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($products as $product)
                    <tr class="group hover:bg-slate-50/30 transition-colors">
                        <td class="px-6 py-6">
                            <input type="checkbox" name="product_ids[]" value="{{ $product->id }}" class="product-checkbox rounded border-slate-300 text-indigo-600 focus:ring-indigo-600">
                        </td>
                        <td class="px-4 py-6">
                            <div class="flex items-center gap-4">
                                <div class="h-12 w-12 rounded-xl bg-slate-100 flex items-center justify-center text-slate-400">
                                    <i class="fas fa-tag"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-900">{{ $product->name }}</p>
                                    <p class="text-[10px] font-extrabold tracking-wider text-slate-400">{{ $product->barcode }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-6">
                            <span class="inline-flex items-center rounded-lg bg-indigo-50 px-2.5 py-1 text-[10px] font-bold text-indigo-600">
                                {{ $product->category->name }}
                            </span>
                        </td>
                        <td class="px-6 py-6 font-bold text-sm">
                            <span class="text-slate-400">{{ $settings['currency_symbol'] ?? '$' }}{{ $product->purchase_price }}</span>
                            <span class="text-slate-300 mx-2">/</span>
                            <span class="text-emerald-500">{{ $settings['currency_symbol'] ?? '$' }}{{ $product->selling_price }}</span>
                        </td>
                        <td class="px-6 py-6">
                            <div class="flex items-center gap-4">
                                @if($product->stock_quantity <= $product->reorder_level)
                                    <span class="flex h-2 w-2 rounded-full bg-rose-500 animate-pulse"></span>
                                @else
                                    <span class="flex h-2 w-2 rounded-full bg-emerald-500"></span>
                                @endif
                                <span class="text-sm font-bold text-slate-700">{{ $product->stock_quantity }} units</span>
                            </div>
                        </td>
                        <td class="px-10 py-6 text-right space-x-2 whitespace-nowrap">
                             <a href="{{ route('products.barcodes.print') }}?product_ids[]={{ $product->id }}" target="_blank" class="h-10 w-10 inline-flex items-center justify-center rounded-xl bg-indigo-50 text-indigo-500 hover:bg-indigo-600 hover:text-white transition-all" title="Print Barcode">
                                <i class="fas fa-barcode text-xs"></i>
                             </a>
                             <button 
                                onclick="editProduct({{ json_encode($product) }})"
                                class="h-10 w-10 inline-flex items-center justify-center rounded-xl bg-slate-100 text-slate-400 hover:bg-slate-900 hover:text-white transition-all"
                                title="Edit Product">
                                <i class="fas fa-edit text-xs"></i>
                             </button>
                             <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="h-10 w-10 inline-flex items-center justify-center rounded-xl bg-rose-50 text-rose-400 hover:bg-rose-500 hover:text-white transition-all" onclick="return confirm('Delete item?')">
                                    <i class="fas fa-trash-alt text-xs"></i>
                                </button>
                             </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-24 text-center">
                            <div class="opacity-10 mb-4">
                                <i class="fas fa-boxes text-6xl"></i>
                            </div>
                            <p class="text-sm font-bold text-slate-400">Inventory catalog is empty</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Product Modal (Simple Version for Demo) -->
<div id="add-product-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm hidden">
    <div class="bg-white rounded-[3rem] shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="p-10">
            <div class="flex justify-between items-center mb-10">
                <h2 class="text-2xl font-extrabold text-slate-900">Catalog Registry</h2>
                <button onclick="document.getElementById('add-product-modal').classList.add('hidden')" class="h-10 w-10 flex items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form action="{{ route('products.store') }}" method="POST" class="grid grid-cols-2 gap-6">
                @csrf
                <div class="col-span-2">
                    <x-input label="Product Name" name="name" required />
                </div>
                <div>
                    <x-input label="Barcode / SKU" name="barcode" required />
                </div>
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2 ms-1">Category</label>
                    <select name="category_id" class="w-full border-0 bg-slate-100 py-4 px-5 text-sm font-semibold rounded-2xl focus:ring-4 focus:ring-indigo-600/10">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                     <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2 ms-1">Supplier</label>
                    <select name="supplier_id" class="w-full border-0 bg-slate-100 py-4 px-5 text-sm font-semibold rounded-2xl focus:ring-4 focus:ring-indigo-600/10">
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <x-input label="Cost Price" name="purchase_price" type="number" step="0.01" required />
                </div>
                <div>
                    <x-input label="Retail Price" name="selling_price" type="number" step="0.01" required />
                </div>
                <div>
                    <x-input label="In-Stock Quantity" name="stock_quantity" type="number" required />
                </div>
                <div>
                    <x-input label="Low Stock Level" name="reorder_level" type="number" required />
                </div>
                
                <div class="col-span-2 mt-4">
                    <x-button class="w-full !py-5 shadow-indigo-600/20 text-lg">
                        Register to Catalog
                    </x-button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Edit Product Modal -->
<div id="edit-product-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm hidden">
    <div class="bg-white rounded-[3rem] shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="p-10">
            <div class="flex justify-between items-center mb-10">
                <h2 class="text-2xl font-extrabold text-slate-900">Update Catalog Item</h2>
                <button onclick="document.getElementById('edit-product-modal').classList.add('hidden')" class="h-10 w-10 flex items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="edit-product-form" action="" method="POST" class="grid grid-cols-2 gap-6">
                @csrf
                @method('PUT')
                <div class="col-span-2">
                    <x-input label="Product Name" name="name" id="edit_name" required />
                </div>
                <div>
                    <x-input label="Barcode / SKU" name="barcode" id="edit_barcode" required />
                </div>
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2 ms-1">Category</label>
                    <select name="category_id" id="edit_category_id" class="w-full border-0 bg-slate-100 py-4 px-5 text-sm font-semibold rounded-2xl focus:ring-4 focus:ring-indigo-600/10">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                     <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2 ms-1">Supplier</label>
                    <select name="supplier_id" id="edit_supplier_id" class="w-full border-0 bg-slate-100 py-4 px-5 text-sm font-semibold rounded-2xl focus:ring-4 focus:ring-indigo-600/10">
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <x-input label="Cost Price" name="purchase_price" id="edit_purchase_price" type="number" step="0.01" required />
                </div>
                <div>
                    <x-input label="Retail Price" name="selling_price" id="edit_selling_price" type="number" step="0.01" required />
                </div>
                <div>
                    <x-input label="In-Stock Quantity" name="stock_quantity" id="edit_stock_quantity" type="number" required />
                </div>
                <div>
                    <x-input label="Low Stock Level" name="reorder_level" id="edit_reorder_level" type="number" required />
                </div>
                
                <div class="col-span-2 mt-4">
                    <x-button class="w-full !py-5 shadow-emerald-600/20 text-lg !bg-emerald-600 hover:!bg-emerald-700">
                        Update Inventory
                    </x-button>
                </div>
            </form>
        </div>
    </div>
</div>
@push('js')
<script>
    document.getElementById('select-all').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.product-checkbox');
        checkboxes.forEach(cb => cb.checked = this.checked);
    });

    document.getElementById('bulk-print-btn').addEventListener('click', function() {
        const selected = document.querySelectorAll('.product-checkbox:checked');
        const form = document.getElementById('bulk-print-form');
        form.innerHTML = ''; // clear previous inputs

        if (selected.length === 0) {
            if (!confirm('No products selected. Print barcodes for all items?')) return;
        }

        selected.forEach(cb => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'product_ids[]';
            input.value = cb.value;
            form.appendChild(input);
        });

        form.submit();
    });

    function editProduct(product) {
        const form = document.getElementById('edit-product-form');
        form.action = `/products/${product.id}`;
        
        document.getElementById('edit_name').value = product.name;
        document.getElementById('edit_barcode').value = product.barcode;
        document.getElementById('edit_category_id').value = product.category_id;
        document.getElementById('edit_supplier_id').value = product.supplier_id;
        document.getElementById('edit_purchase_price').value = product.purchase_price;
        document.getElementById('edit_selling_price').value = product.selling_price;
        document.getElementById('edit_stock_quantity').value = product.stock_quantity;
        document.getElementById('edit_reorder_level').value = product.reorder_level;
        
        document.getElementById('edit-product-modal').classList.remove('hidden');
    }
</script>
@endpush
@endsection

