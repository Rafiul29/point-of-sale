@extends('layouts.app')

@section('header', 'Supplier Relations')

@section('content')
    <div class="flex flex-col gap-8 p-4 sm:p-6 lg:p-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 lg:gap-8">
            <!-- Add/Edit Supplier Form -->
            <div class="lg:col-span-1">
                <div
                    class="rounded-4xl bg-linear-to-br from-indigo-900 via-indigo-800 to-blue-900 border border-indigo-700 p-5 sm:p-7 lg:p-8 shadow-2xl text-white sticky top-20 lg:top-24">
                    <h3 class="text-xl sm:text-2xl font-extrabold mb-5 text-white tracking-tight" id="supplier-form-title">
                        Onboard Supplier</h3>
                    <form id="supplier-form" action="{{ route('suppliers.store') }}" method="POST" class="space-y-5">
                        @csrf
                        <input type="hidden" name="_method" value="POST" id="supplier-form-method" />
                        <input type="hidden" name="supplier_id" id="supplier-id-input" />
                        <div class="space-y-4">
                            <div class="space-y-1">
                                <label
                                    class="block text-[10px] font-bold uppercase tracking-widest text-indigo-400 ms-1">Company
                                    Name</label>
                                <input id="supplier-name-input" type="text" name="name"
                                    class="w-full border border-white/30 bg-white/10 py-3.5 px-4 text-sm font-semibold rounded-2xl text-white placeholder:text-indigo-200 transition-all focus:border-blue-300 focus:bg-white/20 focus:outline-none focus:ring-4 focus:ring-blue-300/40"
                                    required placeholder="Enter company name">
                            </div>
                            <div class="space-y-1">
                                <label
                                    class="block text-[10px] font-bold uppercase tracking-widest text-indigo-400 ms-1">Email
                                    Connection</label>
                                <input id="supplier-email-input" type="email" name="email"
                                    class="w-full border border-white/30 bg-white/10 py-3.5 px-4 text-sm font-semibold rounded-2xl text-white placeholder:text-indigo-200 transition-all focus:border-blue-300 focus:bg-white/20 focus:outline-none focus:ring-4 focus:ring-blue-300/40"
                                    placeholder="Enter email address">
                            </div>
                            <div class="space-y-1">
                                <label
                                    class="block text-[10px] font-bold uppercase tracking-widest text-indigo-400 ms-1">Contact
                                    Phone</label>
                                <input id="supplier-phone-input" type="text" name="phone"
                                    class="w-full border border-white/30 bg-white/10 py-3.5 px-4 text-sm font-semibold rounded-2xl text-white placeholder:text-indigo-200 transition-all focus:border-blue-300 focus:bg-white/20 focus:outline-none focus:ring-4 focus:ring-blue-300/40"
                                    placeholder="Enter phone number">
                            </div>
                            <div class="space-y-1">
                                <label
                                    class="block text-[10px] font-bold uppercase tracking-widest text-indigo-400 ms-1">Physical
                                    HQ</label>
                                <textarea id="supplier-address-input" name="address" rows="3"
                                    class="w-full border border-white/30 bg-white/10 py-3.5 px-4 text-sm font-semibold rounded-2xl text-white placeholder:text-indigo-200 transition-all focus:border-blue-300 focus:bg-white/20 focus:outline-none focus:ring-4 focus:ring-blue-300/40"
                                    placeholder="Street, City, Country..."></textarea>
                            </div>
                        </div>
                        <div class="flex gap-2">`
                            <button id="supplier-form-submit"
                                class="w-full py-4 rounded-2xl bg-indigo-500 font-extrabold text-white transition-all hover:bg-indigo-400 hover:shadow-xl hover:shadow-indigo-500/20 active:scale-95"
                                type="submit">
                                <i class="fas fa-handshake mr-2 opacity-70"></i> Register Supplier
                            </button>
                            <button id="supplier-form-cancel" type="button"
                                class="hidden w-full py-4 rounded-2xl border border-white text-white hover:bg-white/10"
                                aria-label="Cancel edit">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Suppliers List -->
            <div class="lg:col-span-3">
                <div class="rounded-[2.5rem] bg-white shadow-sm border border-slate-100 overflow-hidden">
                    <div class="px-10 py-8 border-b border-slate-50 flex items-center justify-between">
                        <div>
                            <h2 class="text-2xl font-extrabold tracking-tight text-slate-900">Partner Networks</h2>
                            <p class="mt-1 text-sm font-medium text-slate-400">List of verified suppliers for inventory
                                replenishment</p>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="bg-slate-50/10">
                                    <th class="px-10 py-5 text-[10px] font-bold uppercase tracking-widest text-slate-400">
                                        Identity</th>
                                    <th class="px-8 py-5 text-[10px] font-bold uppercase tracking-widest text-slate-400">
                                        Network Info</th>
                                    <th class="px-8 py-5 text-[10px] font-bold uppercase tracking-widest text-slate-400">
                                        Location</th>
                                    <th
                                        class="px-10 py-5 text-right text-[10px] font-bold uppercase tracking-widest text-slate-400">
                                        Management</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50/50">
                                @forelse($suppliers as $supplier)
                                    <tr class="group hover:bg-slate-50/50 transition-all duration-300">
                                        <td class="px-10 py-6">
                                            <div class="flex items-center gap-4">
                                                <div
                                                    class="flex h-12 w-12 items-center justify-center rounded-[1rem] bg-indigo-50 text-indigo-600 transition-transform group-hover:scale-110">
                                                    <i class="fas fa-building text-lg"></i>
                                                </div>
                                                <div>
                                                    <p class="text-base font-bold text-slate-900">{{ $supplier->name }}</p>
                                                    <p
                                                        class="text-[10px] font-extrabold tracking-wider text-slate-400 uppercase">
                                                        Partner ID: SP-{{ str_pad($supplier->id, 4, '0', STR_PAD_LEFT) }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-8 py-6">
                                            <div class="space-y-1">
                                                <p class="text-sm font-semibold text-slate-600 flex items-center gap-2">
                                                    <i class="fas fa-envelope text-indigo-400 text-xs"></i>
                                                    {{ $supplier->email ?? 'N/A' }}
                                                </p>
                                                <p class="text-sm font-semibold text-slate-600 flex items-center gap-2">
                                                    <i class="fas fa-phone text-indigo-400 text-xs"></i>
                                                    {{ $supplier->phone ?? 'N/A' }}
                                                </p>
                                            </div>
                                        </td>
                                        <td class="px-8 py-6">
                                            <p class="text-sm font-medium text-slate-500 max-w-[200px] truncate">
                                                {{ $supplier->address ?? 'Not specified' }}
                                            </p>
                                        </td>
                                        <td class="px-10 py-6 text-right">
                                            <div class="flex items-center justify-end gap-2 opacity-100">
                                                <button type="button"
                                                    class="edit-supplier flex h-10 w-10 items-center justify-center rounded-xl bg-slate-900 text-slate-100 hover:bg-indigo-600 hover:text-white transition-all shadow-lg hover:shadow-slate-900/20"
                                                    data-id="{{ $supplier->id }}" data-name="{{ $supplier->name }}"
                                                    data-email="{{ $supplier->email }}" data-phone="{{ $supplier->phone }}"
                                                    data-address="{{ $supplier->address }}" aria-label="Edit supplier">
                                                    <i class="fas fa-edit text-xs"></i>
                                                </button>
                                                <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST">
                                                    @csrf @method('DELETE')
                                                    <button type="submit"
                                                        class="flex h-10 w-10 items-center justify-center rounded-xl bg-rose-50 text-rose-400 hover:bg-rose-500 hover:text-white transition-all hover:shadow-lg hover:shadow-rose-500/20"
                                                        onclick="return confirm('Disconnect partner?')">
                                                        <i class="fas fa-trash-alt text-xs"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-24 text-center">
                                            <div class="flex flex-col items-center gap-4 opacity-20">
                                                <i class="fas fa-users-slash text-6xl"></i>
                                                <p class="text-lg font-extrabold tracking-tight">No supply partners registered
                                                </p>
                                            </div>
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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('supplier-form');
            const formTitle = document.getElementById('supplier-form-title');
            const methodInput = document.getElementById('supplier-form-method');
            const supplierIdInput = document.getElementById('supplier-id-input');
            const nameInput = document.getElementById('supplier-name-input');
            const emailInput = document.getElementById('supplier-email-input');
            const phoneInput = document.getElementById('supplier-phone-input');
            const addressInput = document.getElementById('supplier-address-input');
            const submitButton = document.getElementById('supplier-form-submit');
            const cancelButton = document.getElementById('supplier-form-cancel');

            const resetForm = () => {
                form.action = "{{ route('suppliers.store') }}";
                methodInput.value = 'POST';
                supplierIdInput.value = '';
                nameInput.value = '';
                emailInput.value = '';
                phoneInput.value = '';
                addressInput.value = '';
                formTitle.textContent = 'Onboard Supplier';
                submitButton.innerHTML = '<i class="fas fa-handshake mr-2 opacity-70"></i> Register Supplier';
                cancelButton.classList.add('hidden');
            };

            document.querySelectorAll('.edit-supplier').forEach((button) => {
                button.addEventListener('click', function () {
                    const id = this.dataset.id;
                    const name = this.dataset.name || '';
                    const email = this.dataset.email || '';
                    const phone = this.dataset.phone || '';
                    const address = this.dataset.address || '';

                    form.action = `/suppliers/${id}`;
                    methodInput.value = 'PUT';
                    supplierIdInput.value = id;
                    nameInput.value = name;
                    emailInput.value = email;
                    phoneInput.value = phone;
                    addressInput.value = address;
                    formTitle.textContent = 'Edit Supplier';
                    submitButton.innerHTML = '<i class="fas fa-save mr-2 opacity-70"></i> Update';
                    cancelButton.classList.remove('hidden');
                });
            });

            cancelButton.addEventListener('click', resetForm);
        });
    </script>

@endsection