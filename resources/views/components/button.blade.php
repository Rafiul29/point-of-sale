@props(['type' => 'submit', 'variant' => 'primary', 'size' => ''])

@php
    $baseClasses = "inline-flex items-center justify-center font-bold transition-all focus:outline-none disabled:opacity-50 disabled:cursor-not-allowed";
    
    $variants = [
        'primary' => 'bg-indigo-600 text-white hover:bg-indigo-700 shadow-lg shadow-indigo-600/20 active:scale-95',
        'success' => 'bg-emerald-600 text-white hover:bg-emerald-700 shadow-lg shadow-emerald-600/20 active:scale-95',
        'danger' => 'bg-rose-600 text-white hover:bg-rose-700 shadow-lg shadow-rose-600/20 active:scale-95',
        'secondary' => 'bg-slate-100 text-slate-700 hover:bg-slate-200 active:scale-95',
    ];

    $sizes = [
        'sm' => 'px-3 py-1.5 text-xs rounded-lg',
        'md' => 'px-5 py-2.5 text-sm rounded-xl',
        'lg' => 'px-8 py-4 text-base rounded-2xl',
    ];

    $variantClass = $variants[$variant] ?? $variants['primary'];
    $sizeClass = $sizes[$size] ?? $sizes['md'];
@endphp

<button 
    type="{{ $type }}" 
    {{ $attributes->merge(['class' => "{$baseClasses} {$variantClass} {$sizeClass}"]) }}
>
    {{ $slot }}
</button>
