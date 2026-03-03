<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Dynamic SEO -->
    <title>{{ $settings['shop_name'] ?? config('app.name', 'POS') }}</title>
    <meta name="description" content="{{ $settings['meta_description'] ?? 'Modern Point of Sale solution for retail and restaurants.' }}">
    <meta name="keywords" content="{{ $settings['meta_keywords'] ?? 'pos, retail, inventory' }}">
    <link rel="icon" type="image/x-icon" href="{{ isset($settings['site_favicon']) ? asset('storage/' . $settings['site_favicon']) : asset('favicon.ico') }}">

    <!-- OpenGraph -->
    <meta property="og:title" content="{{ $settings['meta_title'] ?? config('app.name', 'POS') }}">
    <meta property="og:description" content="{{ $settings['meta_description'] ?? 'Modern Point of Sale solution for retail and restaurants.' }}">
    <meta property="og:image" content="{{ isset($settings['og_image']) ? asset('storage/' . $settings['og_image']) : '' }}">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
    @stack('css')
</head>
<body class="h-full text-slate-900 antialiased">

    <div class="flex min-h-screen">
        <div class="print:hidden">
            @include('layouts.sidebar')
        </div>

        <div class="flex flex-1 flex-col transition-all duration-300 ml-72 print:ml-0">
            <header class="sticky top-0 z-30 flex h-16 shrink-0 items-center justify-between border-b border-slate-200 bg-white/80 px-8 backdrop-blur-md print:hidden">
                <h1 class="text-xl font-bold tracking-tight text-slate-800">@yield('header', 'Dashboard')</h1>
                <div class="flex items-center gap-4">
                    <div class="flex flex-col items-end">
                        <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Status</span>
                        <span class="text-sm font-bold text-indigo-600">{{ auth()->user()->role ?? 'Admin' }}</span>
                    </div>
                </div>
            </header>

            <main class="flex-1 p-8">
                @if(session('success'))
                    <div class="mb-6 rounded-xl border border-emerald-100 bg-emerald-50 p-4 text-emerald-800 shadow-sm animate-in fade-in slide-in-from-top-4 duration-300">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-check-circle text-emerald-500"></i>
                            <p class="text-sm font-medium">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 rounded-xl border border-rose-100 bg-rose-50 p-4 text-rose-800 shadow-sm animate-in fade-in slide-in-from-top-4 duration-300">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-exclamation-circle text-rose-500"></i>
                            <p class="text-sm font-medium">{{ session('error') }}</p>
                        </div>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <!-- JQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    @stack('js')
</body>
</html>
