@extends('layouts.app')

@section('header', 'System Configuration')

@section('content')
<div class="max-w-5xl mx-auto pb-20">
    <div class="bg-white rounded-[3rem] shadow-sm border border-slate-100 overflow-hidden" x-data="{ activeTab: 'general' }">
        <!-- Header -->
        <div class="px-12 py-10 border-b border-slate-50 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex items-center gap-6">
                <div class="h-16 w-16 rounded-[1.5rem] bg-indigo-600 flex items-center justify-center text-white shadow-xl shadow-indigo-500/20">
                    <i class="fas fa-cog text-2xl animate-spin-slow"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-extrabold text-slate-900">Global Settings</h2>
                    <p class="text-sm font-medium text-slate-400 mt-1">Configure your shop identity, branding, and SEO</p>
                </div>
            </div>

            <!-- Tab Navigation -->
            <div class="flex bg-slate-100 p-1.5 rounded-2xl">
                <button @click="activeTab = 'general'" :class="activeTab === 'general' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-500 hover:text-slate-800'" class="px-6 py-2.5 rounded-xl text-xs font-bold transition-all">General</button>
                <button @click="activeTab = 'branding'" :class="activeTab === 'branding' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-500 hover:text-slate-800'" class="px-6 py-2.5 rounded-xl text-xs font-bold transition-all">Branding</button>
                <button @click="activeTab = 'seo'" :class="activeTab === 'seo' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-500 hover:text-slate-800'" class="px-6 py-2.5 rounded-xl text-xs font-bold transition-all">SEO</button>
                <button @click="activeTab = 'social'" :class="activeTab === 'social' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-500 hover:text-slate-800'" class="px-6 py-2.5 rounded-xl text-xs font-bold transition-all">Social</button>
            </div>
        </div>

        <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data" class="p-12">
            @csrf
            
            <!-- General Tab -->
            <div x-show="activeTab === 'general'" class="space-y-12 animate-in fade-in slide-in-from-bottom-4 duration-500">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                    <div class="space-y-8">
                        <h3 class="text-xs font-bold uppercase tracking-[0.2em] text-indigo-600 flex items-center gap-3">
                            <span class="h-2 w-2 rounded-full bg-indigo-600"></span>
                            Shop Identity
                        </h3>
                        <x-input label="Business Name" name="shop_name" value="{{ $settings['shop_name'] ?? '' }}" required />
                        <x-input label="Contact Email" name="contact_email" type="email" value="{{ $settings['contact_email'] ?? '' }}" />
                        <x-input label="Support Phone" name="shop_phone" value="{{ $settings['shop_phone'] ?? '' }}" />
                        <div class="space-y-2">
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-400 ms-1">Mailing Address</label>
                            <textarea name="shop_address" rows="3" class="w-full border-0 bg-slate-100/50 py-4 px-5 text-sm font-semibold rounded-2xl focus:ring-4 focus:ring-indigo-600/10 placeholder:text-slate-300">{{ $settings['shop_address'] ?? '' }}</textarea>
                        </div>
                    </div>

                    <div class="space-y-8">
                        <h3 class="text-xs font-bold uppercase tracking-[0.2em] text-emerald-500 flex items-center gap-3">
                            <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                            Financial Details
                        </h3>
                        <x-input label="Currency Symbol" name="currency_symbol" value="{{ $settings['currency_symbol'] ?? '' }}" placeholder="$" required />
                        <x-input label="Global Tax (%)" name="tax_percentage" type="number" step="0.01" value="{{ $settings['tax_percentage'] ?? '' }}" required />
                        
                        <div class="p-6 rounded-[2rem] bg-amber-50 border border-amber-100 flex gap-4">
                            <i class="fas fa-info-circle text-amber-500 mt-1"></i>
                            <p class="text-xs text-amber-800 leading-relaxed font-medium">Updating tax or currency settings will propagate across the POS terminal and all newly generated invoices immediately.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Branding Tab -->
            <div x-show="activeTab === 'branding'" class="space-y-10 animate-in fade-in slide-in-from-bottom-4 duration-500">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <!-- Logo Upload -->
                    <div class="space-y-4">
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-400 ms-1">Site Logo</label>
                        <div class="relative group h-40 rounded-[2rem] bg-slate-50 border-2 border-dashed border-slate-200 flex items-center justify-center overflow-hidden transition-all hover:border-indigo-400">
                            @if(isset($settings['site_logo']))
                                <img src="{{ asset('storage/' . $settings['site_logo']) }}" class="h-full w-full object-contain p-4">
                            @else
                                <div class="text-center">
                                    <i class="fas fa-cloud-upload-alt text-3xl text-slate-300 group-hover:text-indigo-400"></i>
                                    <p class="text-[10px] font-bold text-slate-400 mt-2">Upload PNG/SVG</p>
                                </div>
                            @endif
                            <input type="file" name="site_logo" accept="image/png,image/jpeg,image/webp,image/svg+xml" class="absolute inset-0 opacity-0 cursor-pointer" data-max-size="10485760">
                        </div>
                        <p class="mt-2 text-xs text-slate-400">Max size: 10MB. Supported: PNG, JPG, JPEG, SVG, WEBP.</p>
                    </div>

                    <!-- Favicon Upload -->
                    <div class="space-y-4">
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-400 ms-1">Site Favicon</label>
                        <div class="relative group h-40 rounded-[2rem] bg-slate-50 border-2 border-dashed border-slate-200 flex items-center justify-center overflow-hidden transition-all hover:border-indigo-400">
                            @if(isset($settings['site_favicon']))
                                <img src="{{ asset('storage/' . $settings['site_favicon']) }}" class="h-12 w-12 object-contain">
                            @else
                                <div class="text-center">
                                    <i class="fas fa-image text-3xl text-slate-300 group-hover:text-indigo-400"></i>
                                    <p class="text-[10px] font-bold text-slate-400 mt-2">Upload ICO/PNG</p>
                                </div>
                            @endif
                            <input type="file" name="site_favicon" accept="image/png,image/jpeg,image/webp,image/svg+xml" class="absolute inset-0 opacity-0 cursor-pointer" data-max-size="10485760">
                        </div>
                        <p class="mt-2 text-xs text-slate-400">Max size: 10MB. Supported: PNG, JPG, JPEG, SVG, WEBP.</p>
                    </div>

                    <!-- Banner Upload -->
                    <div class="md:col-span-2 space-y-4">
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-400 ms-1">Promo/Site Banner</label>
                        <div class="relative group h-48 rounded-[2rem] bg-slate-50 border-2 border-dashed border-slate-200 flex items-center justify-center overflow-hidden transition-all hover:border-indigo-400">
                            @if(isset($settings['site_banner']))
                                <img src="{{ asset('storage/' . $settings['site_banner']) }}" class="h-full w-full object-cover">
                            @else
                                <div class="text-center">
                                    <i class="fas fa-panorama text-3xl text-slate-300 group-hover:text-indigo-400"></i>
                                    <p class="text-[10px] font-bold text-slate-400 mt-2">Recommended size: 1200x400px</p>
                                </div>
                            @endif
                            <input type="file" name="site_banner" accept="image/png,image/jpeg,image/webp,image/svg+xml" class="absolute inset-0 opacity-0 cursor-pointer" data-max-size="10485760">
                        </div>
                        <p class="mt-2 text-xs text-slate-400">Max size: 10MB. Supported: PNG, JPG, JPEG, SVG, WEBP.</p>
                    </div>
                </div>
            </div>

            <!-- SEO Tab -->
            <div x-show="activeTab === 'seo'" class="space-y-10 animate-in fade-in slide-in-from-bottom-4 duration-500">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <div class="md:col-span-2">
                        <x-input label="Meta Title" name="meta_title" value="{{ $settings['meta_title'] ?? '' }}" />
                    </div>
                    
                    <div class="space-y-2">
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-400 ms-1">Meta Description</label>
                        <textarea name="meta_description" rows="4" class="w-full border-0 bg-slate-100/50 py-4 px-5 text-sm font-semibold rounded-2xl focus:ring-4 focus:ring-indigo-600/10 placeholder:text-slate-300">{{ $settings['meta_description'] ?? '' }}</textarea>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-400 ms-1">Meta Keywords (Comma separated)</label>
                        <textarea name="meta_keywords" rows="4" class="w-full border-0 bg-slate-100/50 py-4 px-5 text-sm font-semibold rounded-2xl focus:ring-4 focus:ring-indigo-600/10 placeholder:text-slate-300">{{ $settings['meta_keywords'] ?? '' }}</textarea>
                    </div>

                    <div class="md:col-span-2 space-y-4">
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-400 ms-1">OpenGraph Image (Sharing Image)</label>
                        <div class="relative group h-40 rounded-[2rem] bg-slate-50 border-2 border-dashed border-slate-200 flex items-center justify-center overflow-hidden transition-all hover:border-indigo-400">
                            @if(isset($settings['og_image']))
                                <img src="{{ asset('storage/' . $settings['og_image']) }}" class="h-full w-full object-cover">
                            @else
                                <div class="text-center">
                                    <i class="fas fa-share-alt text-3xl text-slate-300 group-hover:text-indigo-400"></i>
                                    <p class="text-[10px] font-bold text-slate-400 mt-2">Recommended: 1200x630px</p>
                                </div>
                            @endif
                            <input type="file" name="og_image" accept="image/png,image/jpeg,image/webp,image/svg+xml" class="absolute inset-0 opacity-0 cursor-pointer" data-max-size="10485760">
                        </div>
                        <p class="mt-2 text-xs text-slate-400">Max size: 10MB. Supported: PNG, JPG, JPEG, SVG, WEBP.</p>
                    </div>
                </div>
            </div>

            <!-- Social Tab -->
            <div x-show="activeTab === 'social'" class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500">
                <div class="max-w-xl">
                    <h3 class="text-xs font-bold uppercase tracking-[0.2em] text-blue-600 flex items-center gap-3 mb-8">
                        <i class="fab fa-facebook-f"></i>
                        Facebook Integration
                    </h3>
                    <x-input label="Facebook Page URL" name="facebook_url" value="{{ $settings['facebook_url'] ?? '' }}" placeholder="https://facebook.com/your-page" />
                </div>
            </div>

            <!-- Global Actions -->
            <div class="pt-12 mt-12 border-t border-slate-50 flex justify-end">
                <x-button class="px-12 !py-5 shadow-2xl shadow-indigo-600/20 text-lg">
                    <i class="fas fa-sync-alt mr-3 opacity-70"></i> Synchronize System
                </x-button>
            </div>
        </form>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const maxSize = 10 * 1024 * 1024;
                const fileInputs = document.querySelectorAll('input[type="file"][data-max-size]');

                fileInputs.forEach((input) => {
                    input.addEventListener('change', function () {
                        const file = this.files[0];
                        if (!file) return;

                        const max = Number(this.dataset.maxSize) || maxSize;

                        if (file.size > max) {
                            alert(`File too large. Maximum allowed size is ${(max / 1024 / 1024).toFixed(1)} MB.`);
                            this.value = '';
                        }
                    });
                });
            });
        </script>

    </div>
</div>
@endsection
