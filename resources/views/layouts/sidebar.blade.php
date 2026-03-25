<div id="sidebar" class="sidebar fixed inset-y-0 left-0 z-40 bg-slate-900 text-slate-300 shadow-2xl lg:translate-x-0 overflow-hidden">
    <div class="flex h-full flex-col px-4 py-6">
        <!-- Close button for mobile -->
        <button onclick="closeSidebar()" class="absolute top-4 right-4 lg:hidden text-slate-400 hover:text-white transition-colors">
            <i class="fas fa-times text-xl"></i>
        </button>

        <!-- Logo -->
        <div class="px-2 mb-10">
            <a href="{{ auth()->user()->isAdmin() ? route('dashboard') : route('cashier.dashboard') }}" class="flex items-center gap-4 text-white decoration-0 transition-all hover:opacity-90">
                @if(isset($settings['site_logo']))
                    <img src="{{ asset('storage/' . $settings['site_logo']) }}" class="sidebar-icon h-10 w-10 min-w-10 rounded-xl object-contain shadow-lg" alt="Logo">
                @else
                    <div class="sidebar-icon flex h-10 w-10 min-w-10 items-center justify-center rounded-xl bg-linear-to-br from-indigo-500 to-purple-600 shadow-lg shadow-indigo-500/20">
                        <i class="fas fa-bolt text-lg"></i>
                    </div>
                @endif
                <div class="sidebar-text">
                    <span class="block text-xl font-extrabold tracking-tight truncate">{{ $settings['shop_name'] ?? 'POS' }}</span>
                    <span class="block text-[10px] font-medium text-slate-500 uppercase tracking-widest mt-0.5">Management Node</span>
                </div>
            </a>
        </div>

        <!-- Main Navigation -->
        <nav class="flex-1 space-y-8 overflow-y-auto px-2 custom-scrollbar">

            <!-- ── General (All Roles) ── -->
            <div>
                <span class="sidebar-category-label sidebar-text px-4 text-[10px] font-bold uppercase tracking-[0.2em] text-slate-500 block mb-4">General</span>
                <ul class="space-y-1.5">
                    @if(auth()->user()->isAdmin())
                    <li>
                        <a href="{{ route('dashboard') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold transition-all {{ request()->routeIs('dashboard') ? 'nav-link-active' : 'hover:bg-white/5 hover:text-white' }}">
                            <i class="sidebar-icon fas fa-grid-2 text-lg opacity-80 shrink-0"></i>
                            <span class="sidebar-text">Dashboard</span>
                        </a>
                    </li>
                    @else
                    <li>
                        <a href="{{ route('cashier.dashboard') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold transition-all {{ request()->routeIs('cashier.dashboard') ? 'nav-link-active' : 'hover:bg-white/5 hover:text-white' }}">
                            <i class="sidebar-icon fas fa-grid-2 text-lg opacity-80 shrink-0"></i>
                            <span class="sidebar-text">My Dashboard</span>
                        </a>
                    </li>
                    @endif
                    <li>
                        <a href="{{ route('pos.index') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold transition-all {{ request()->routeIs('pos.*') ? 'nav-link-active' : 'hover:bg-white/5 hover:text-white' }}">
                            <i class="sidebar-icon fas fa-cash-register text-lg opacity-80 shrink-0"></i>
                            <span class="sidebar-text">Terminal POS</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('customers.index') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold transition-all {{ request()->routeIs('customers.*') ? 'nav-link-active' : 'hover:bg-white/5 hover:text-white' }}">
                            <i class="sidebar-icon fas fa-users text-lg opacity-80 shrink-0"></i>
                            <span class="sidebar-text">Customers</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- ── Admin Only Sections ── -->
            @if(auth()->user()->isAdmin())
            <div>
                <span class="sidebar-category-label sidebar-text px-4 text-[10px] font-bold uppercase tracking-[0.2em] text-slate-500 block mb-4">Inventory</span>
                <ul class="space-y-1.5">
                    <li>
                        <a href="{{ route('categories.index') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold transition-all {{ request()->routeIs('categories.*') ? 'nav-link-active' : 'hover:bg-white/5 hover:text-white' }}">
                            <i class="sidebar-icon fas fa-folder text-lg opacity-80 shrink-0"></i>
                            <span class="sidebar-text">Categories</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('suppliers.index') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold transition-all {{ request()->routeIs('suppliers.*') ? 'nav-link-active' : 'hover:bg-white/5 hover:text-white' }}">
                            <i class="sidebar-icon fas fa-truck-loading text-lg opacity-80 shrink-0"></i>
                            <span class="sidebar-text">Suppliers</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('products.index') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold transition-all {{ request()->routeIs('products.*') ? 'nav-link-active' : 'hover:bg-white/5 hover:text-white' }}">
                            <i class="sidebar-icon fas fa-boxes text-lg opacity-80 shrink-0"></i>
                            <span class="sidebar-text">Products</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('purchases.index') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold transition-all {{ request()->routeIs('purchases.*') ? 'nav-link-active' : 'hover:bg-white/5 hover:text-white' }}">
                            <i class="sidebar-icon fas fa-file-invoice text-lg opacity-80 shrink-0"></i>
                            <span class="sidebar-text">Stock Arrivals</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div>
                <span class="sidebar-category-label sidebar-text px-4 text-[10px] font-bold uppercase tracking-[0.2em] text-slate-500 block mb-4">Analytics</span>
                <ul class="space-y-1.5">
                    <li>
                        <a href="{{ route('reports.sales') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold transition-all {{ request()->routeIs('reports.sales') ? 'nav-link-active' : 'hover:bg-white/5 hover:text-white' }}">
                            <i class="sidebar-icon fas fa-chart-line text-lg opacity-80 shrink-0"></i>
                            <span class="sidebar-text">Sales Report</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('reports.top-products') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold transition-all {{ request()->routeIs('reports.top-products') ? 'nav-link-active' : 'hover:bg-white/5 hover:text-white' }}">
                            <i class="sidebar-icon fas fa-trophy text-lg opacity-80 shrink-0"></i>
                            <span class="sidebar-text">Top Products</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('reports.inventory') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold transition-all {{ request()->routeIs('reports.inventory') ? 'nav-link-active' : 'hover:bg-white/5 hover:text-white' }}">
                            <i class="sidebar-icon fas fa-warehouse text-lg opacity-80 shrink-0"></i>
                            <span class="sidebar-text">Inventory</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div>
                <span class="sidebar-category-label sidebar-text px-4 text-[10px] font-bold uppercase tracking-[0.2em] text-slate-500 block mb-4">System</span>
                <ul class="space-y-1.5">
                    <li>
                        <a href="{{ route('audit-logs.index') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold transition-all {{ request()->routeIs('audit-logs.*') ? 'nav-link-active' : 'hover:bg-white/5 hover:text-white' }}">
                            <i class="sidebar-icon fas fa-shield-halved text-lg opacity-80 shrink-0"></i>
                            <span class="sidebar-text">Audit Log</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('users.index') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold transition-all {{ request()->routeIs('users.*') ? 'nav-link-active' : 'hover:bg-white/5 hover:text-white' }}">
                            <i class="sidebar-icon fas fa-user-cog text-lg opacity-80 shrink-0"></i>
                            <span class="sidebar-text">User Management</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('settings.index') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold transition-all {{ request()->routeIs('settings.*') ? 'nav-link-active' : 'hover:bg-white/5 hover:text-white' }}">
                            <i class="sidebar-icon fas fa-sliders text-lg opacity-80 shrink-0"></i>
                            <span class="sidebar-text">General Config</span>
                        </a>
                    </li>
                </ul>
            </div>
            @endif

        </nav>

        <!-- User Profile & Footer -->
        <div class="mt-auto px-2 pt-6 border-t border-white/5">
            <div class="mb-4 sidebar-text">
                <div class="flex items-center gap-2 rounded-2xl px-4 py-2 {{ auth()->user()->isAdmin() ? 'bg-indigo-600/10' : 'bg-purple-600/10' }}">
                    <i class="sidebar-icon {{ auth()->user()->isAdmin() ? 'fas fa-user-shield text-indigo-400' : 'fas fa-cash-register text-purple-400' }} text-sm shrink-0"></i>
                    <span class="sidebar-text text-[10px] font-black uppercase tracking-widest {{ auth()->user()->isAdmin() ? 'text-indigo-400' : 'text-purple-400' }}">
                        {{ auth()->user()->role }}
                    </span>
                </div>
            </div>

            <div class="group relative">
                <button id="user-menu-btn" class="flex w-full items-center gap-3 rounded-2xl bg-white/5 p-2 text-left transition-all hover:bg-white/10 ring-1 ring-white/5">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'User') }}&background=6366f1&color=fff" class="sidebar-icon h-9 w-9 min-w-9 rounded-xl object-cover shadow-sm shrink-0" alt="Profile">
                    <div class="flex-1 overflow-hidden sidebar-text">
                        <p class="truncate text-sm font-bold text-white">{{ auth()->user()->name ?? 'User' }}</p>
                        <p class="truncate text-[10px] font-medium text-slate-500">{{ auth()->user()->email }}</p>
                    </div>
                </button>
                
                <!-- Logout - simplified for the sidebar -->
                <div class="mt-2 sidebar-text">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="flex w-full items-center gap-3 rounded-xl px-4 py-2.5 text-left text-xs font-bold text-rose-400 hover:bg-rose-500/10 transition-colors">
                            <i class="sidebar-icon fas fa-sign-out-alt opacity-70"></i>
                            <span class="sidebar-text">Sign Out</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

