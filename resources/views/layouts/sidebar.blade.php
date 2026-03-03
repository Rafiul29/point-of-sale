<div class="fixed inset-y-0 left-0 z-40 w-72 bg-slate-900 text-slate-300 shadow-2xl transition-transform duration-300">
    <div class="flex h-full flex-col p-6">
        <!-- Logo -->
        <a href="/" class="mb-8 flex items-center gap-4 text-white decoration-0 transition-opacity hover:opacity-80">
            @if(isset($settings['site_logo']))
                <img src="{{ asset('storage/' . $settings['site_logo']) }}" class="h-11 w-11 rounded-xl object-contain shadow-lg" alt="">
            @else
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 shadow-lg shadow-indigo-500/20">
                    <i class="fas fa-bolt text-lg"></i>
                </div>
            @endif
            <span class="text-xl font-extrabold tracking-tight truncate max-w-[150px]">{{ $settings['shop_name'] ?? 'POS' }}</span>
        </a>

        <!-- Main Navigation -->
        <nav class="flex-1 space-y-8">
            <div>
                <span class="px-2 text-[10px] font-bold uppercase tracking-[0.2em] text-slate-500">General</span>
                <ul class="mt-4 space-y-1">
                    <li>
                        <a href="{{ route('dashboard') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold transition-all {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30' : 'hover:bg-slate-800 hover:text-white' }}">
                            <i class="fas fa-grid-2 text-lg opacity-70 transition-transform group-hover:scale-110"></i>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('pos.index') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold transition-all {{ request()->routeIs('pos.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30' : 'hover:bg-slate-800 hover:text-white' }}">
                            <i class="fas fa-shopping-cart text-lg opacity-70 transition-transform group-hover:scale-110"></i>
                            Terminal POS
                        </a>
                    </li>
                </ul>
            </div>

            <div>
                <span class="px-2 text-[10px] font-bold uppercase tracking-[0.2em] text-slate-500">Inventory</span>
                <ul class="mt-4 space-y-1">
                    <li>
                        <a href="{{ route('categories.index') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold transition-all {{ request()->routeIs('categories.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30' : 'hover:bg-slate-800 hover:text-white' }}">
                            <i class="fas fa-folder text-lg opacity-70"></i>
                            Categories
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('suppliers.index') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold transition-all {{ request()->routeIs('suppliers.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30' : 'hover:bg-slate-800 hover:text-white' }}">
                            <i class="fas fa-truck-loading text-lg opacity-70"></i>
                            Suppliers
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('products.index') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold transition-all {{ request()->routeIs('products.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30' : 'hover:bg-slate-800 hover:text-white' }}">
                            <i class="fas fa-boxes text-lg opacity-70"></i>
                            Products
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('purchases.index') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold transition-all {{ request()->routeIs('purchases.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30' : 'hover:bg-slate-800 hover:text-white' }}">
                            <i class="fas fa-file-invoice text-lg opacity-70"></i>
                            Stock Arrivals
                        </a>
                    </li>
                </ul>
            </div>

            <div>
                <span class="px-2 text-[10px] font-bold uppercase tracking-[0.2em] text-slate-500">Relationships</span>
                <ul class="mt-4 space-y-1">
                    <li>
                        <a href="{{ route('customers.index') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold transition-all {{ request()->routeIs('customers.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30' : 'hover:bg-slate-800 hover:text-white' }}">
                            <i class="fas fa-users text-lg opacity-70"></i>
                            Customers
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('reports.sales') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold transition-all {{ request()->routeIs('reports.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30' : 'hover:bg-slate-800 hover:text-white' }}">
                            <i class="fas fa-chart-pie text-lg opacity-70"></i>
                            Reports
                        </a>
                    </li>
                </ul>
            <div>
                <span class="px-2 text-[10px] font-bold uppercase tracking-[0.2em] text-slate-500">System</span>
                <ul class="mt-4 space-y-1">
                    <li>
                        <a href="{{ route('settings.index') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold transition-all {{ request()->routeIs('settings.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30' : 'hover:bg-slate-800 hover:text-white' }}">
                            <i class="fas fa-sliders text-lg opacity-70"></i>
                            General Config
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- User Profile -->
        <div class="mt-auto pt-6 border-t border-slate-800">
            <div class="group relative">
                <button id="user-menu-btn" class="flex w-full items-center gap-3 rounded-2xl bg-slate-800/50 p-3 text-left transition-all hover:bg-slate-800">
                    <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name ?? 'User' }}&background=6366f1&color=fff" class="h-10 w-10 rounded-xl object-cover shadow-sm" alt="Profile">
                    <div class="flex-1 overflow-hidden">
                        <p class="truncate text-sm font-bold text-white">{{ auth()->user()->name ?? 'User' }}</p>
                        <p class="truncate text-[10px] font-medium text-slate-500">{{ auth()->user()->role ?? 'Admin' }}</p>
                    </div>
                    <i class="fas fa-chevron-up text-xs text-slate-600 transition-transform group-focus-within:rotate-180"></i>
                </button>
                
                <!-- Dropdown -->
                <div id="user-menu" class="absolute bottom-full left-0 mb-2 w-full origin-bottom scale-95 opacity-0 transition-all duration-200 pointer-events-none group-focus-within:scale-100 group-focus-within:opacity-100 group-focus-within:pointer-events-auto">
                    <div class="rounded-2xl border border-slate-800 bg-slate-900 p-2 shadow-2xl">
                        <a href="#" class="flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm font-semibold hover:bg-slate-800 hover:text-white">
                            <i class="fas fa-user-circle opacity-70"></i>
                            Profile Settings
                        </a>
                        <div class="my-1 border-t border-slate-800"></div>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="flex w-full items-center gap-3 rounded-xl px-4 py-2.5 text-left text-sm font-bold text-rose-400 hover:bg-rose-500/10">
                                <i class="fas fa-sign-out-alt opacity-70"></i>
                                Sign Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
