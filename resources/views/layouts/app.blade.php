<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Dynamic SEO -->
    <title>{{ $settings['shop_name'] ?? config('app.name', 'POS') }}</title>
    <meta name="description"
        content="{{ $settings['meta_description'] ?? 'Modern Point of Sale solution for retail and restaurants.' }}">
    <meta name="keywords" content="{{ $settings['meta_keywords'] ?? 'pos, retail, inventory' }}">
    <link rel="icon" type="image/x-icon"
        href="{{ isset($settings['site_favicon']) ? asset('storage/' . $settings['site_favicon']) : asset('favicon.ico') }}">

    <!-- OpenGraph -->
    <meta property="og:title" content="{{ $settings['meta_title'] ?? config('app.name', 'POS') }}">
    <meta property="og:description"
        content="{{ $settings['meta_description'] ?? 'Modern Point of Sale solution for retail and restaurants.' }}">
    <meta property="og:image"
        content="{{ isset($settings['og_image']) ? asset('storage/' . $settings['og_image']) : '' }}">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', sans-serif;
            scroll-behavior: smooth;
        }

        [x-cloak] {
            display: none !important;
        }

        /* Sidebar styles */
        .sidebar {
            transform: translateX(-100%);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            width: 18rem;
            box-shadow: 10px 0 25px -5px rgba(0, 0, 0, 0.1), 5px 0 10px -5px rgba(0, 0, 0, 0.04);
            overflow-x: hidden;
            white-space: nowrap;
        }

        .sidebar.open {
            transform: translateX(0);
        }

        .sidebar.collapsed {
            width: 5rem;
        }

        /* Hide text when collapsed */
        .sidebar.collapsed .sidebar-text {
            opacity: 0;
            visibility: hidden;
            width: 0;
            margin: 0;
            display: none;
            transition: opacity 0.2s ease;
        }

        .sidebar.collapsed .sidebar-icon {
            margin-right: 0;
            justify-content: center;
            width: 100%;
            transition: all 0.3s ease;
        }

        .sidebar.collapsed .sidebar-category-label {
            display: none;
        }

        /* Center navigation items when collapsed */
        .sidebar.collapsed nav a {
            justify-content: center;
            padding-left: 0;
            padding-right: 0;
        }

        .sidebar.collapsed nav li {
            padding: 0 0.75rem;
        }

        /* Main content margin - default for desktop to prevent shift */
        .main-content {
            margin-left: 0;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Mobile backdrop - premium look */
        .mobile-backdrop {
            display: none;
            opacity: 0;
            transition: opacity 0.4s ease;
            backdrop-filter: blur(4px);
            background-color: rgba(15, 23, 42, 0.5);
            /* slate-900 with opacity */
        }

        .mobile-backdrop.show {
            display: block;
            opacity: 1;
        }

        /* Desktop specific layout */
        @media (min-width: 1024px) {
            .sidebar {
                transform: translateX(0);
                position: fixed;
                z-index: 50;
            }

            .main-content {
                margin-left: 18rem;
                /* Default margin to prevent shift */
            }

            .main-content.sidebar-open {
                margin-left: 18rem;
            }

            .main-content.sidebar-collapsed {
                margin-left: 5rem;
            }

            .sidebar.collapsed {
                transform: translateX(0);
            }

            .sidebar.collapsed:hover {
                width: 18rem;
                z-index: 60;
                box-shadow: 20px 0 25px -5px rgba(0, 0, 0, 0.2);
            }

            .sidebar.collapsed:hover .sidebar-text {
                opacity: 1;
                visibility: visible;
                display: inline-block;
                width: auto;
                margin-left: 0.75rem;
            }

            .sidebar.collapsed:hover .sidebar-icon {
                margin-right: 0.75rem;
                width: auto;
            }

            .sidebar.collapsed:hover .sidebar-category-label {
                display: block;
            }

            .sidebar.collapsed:hover nav a {
                justify-content: flex-start;
                padding-left: 1rem;
                padding-right: 1rem;
            }

            .sidebar.collapsed:hover nav li {
                padding: 0;
            }
        }

        /* Mobile specific layout */
        @media (max-width: 1023px) {
            .sidebar {
                position: fixed;
                z-index: 60;
                transform: translateX(-100%);
                height: 100vh;
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0 !important;
                width: 100%;
            }
        }


        /* Improved Sidebar Active State */
        .nav-link-active {
            background: linear-gradient(to right, rgb(79, 70, 229), rgb(124, 58, 237));
            color: white !important;
            box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.4);
        }

        /* Custom Scrollbar for Sidebar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.2);
        }
    </style>
    @stack('css')
</head>

<body class="h-full text-slate-900 antialiased">

    <div class="flex min-h-screen">
        <!-- Mobile backdrop -->
        <div id="mobile-backdrop" class="mobile-backdrop fixed inset-0 z-30 lg:hidden" onclick="closeSidebar()"></div>

        <div class="print:hidden">
            @include('layouts.sidebar')
        </div>

        <div id="main-content"
            class="main-content flex flex-1 flex-col transition-all duration-400 print:ml-0 overflow-x-hidden">
            <header
                class="fixed w-full top-0 z-30 flex h-16 shrink-0 items-center justify-between border-b border-slate-200 bg-white/70 px-4 sm:px-6 lg:px-8 backdrop-blur-xl print:hidden">
                <!-- Left Section: Toggle & Title -->
                <div class="flex items-center gap-4">
                    <!-- Mobile menu button -->
                    <button onclick="toggleSidebar()"
                        class="lg:hidden p-2 rounded-xl text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition-colors">
                        <i class="fas fa-bars-staggered text-xl"></i>
                    </button>

                    <!-- Desktop collapse button -->
                    <button id="desktop-toggle-btn" onclick="toggleSidebar()"
                        class="hidden lg:flex p-2 rounded-xl text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition-all active:scale-95">
                        <i class="fas fa-indent text-xl"></i>
                    </button>

                    <h1 class="text-lg font-bold tracking-tight text-slate-800">
                        @yield('header', 'Dashboard')
                    </h1>
                </div>

                <!-- Right Section: User & Actions -->
                <div class="flex items-center gap-3 sm:gap-6">
                    <div class="hidden sm:flex flex-col items-end">
                        <span
                            class="text-[10px] font-bold uppercase tracking-wider text-slate-400 leading-none mb-1">Authenticated
                            As</span>
                        <span
                            class="text-xs font-extrabold text-indigo-600">{{ auth()->user()->role ?? 'Admin' }}</span>
                    </div>

                    <div class="h-8 w-px bg-slate-200 hidden sm:block"></div>

                    <a href="{{ route('settings.index') }}"
                        class="p-2 rounded-xl text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition-all">
                        <i class="fas fa-cog text-lg"></i>
                    </a>
                </div>
            </header>

            <main class="flex-1 p-4 sm:p-6 lg:p-10 mt-10">
                @if (session('success'))
                    <div
                        class="mb-6 rounded-xl border border-emerald-100 bg-emerald-50 p-4 text-emerald-800 shadow-sm animate-in fade-in slide-in-from-top-4 duration-300">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-check-circle text-emerald-500"></i>
                            <p class="text-sm font-medium">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div
                        class="mb-6 rounded-xl border border-rose-100 bg-rose-50 p-4 text-rose-800 shadow-sm animate-in fade-in slide-in-from-top-4 duration-300">
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

    <script>
        // Sidebar functionality
        let sidebarOpen = false;
        let sidebarCollapsed = false;

        function initSidebar() {
            // Check if we're on mobile or desktop
            const isMobile = window.innerWidth < 1024;

            if (isMobile) {
                // Mobile: sidebar starts closed
                sidebarOpen = false;
                sidebarCollapsed = false;
                updateSidebarState();
            } else {
                // Desktop: sidebar starts expanded (not collapsed)
                sidebarOpen = true;
                sidebarCollapsed = false;
                updateSidebarState();
            }
        }

        function toggleSidebar() {
            const isMobile = window.innerWidth < 1024;

            if (isMobile) {
                // Mobile: toggle open/close
                sidebarOpen = !sidebarOpen;
                sidebarCollapsed = false;
            } else {
                // Desktop: toggle collapsed/expanded
                sidebarCollapsed = !sidebarCollapsed;
                sidebarOpen = !sidebarCollapsed;
            }

            updateSidebarState();
        }

        function closeSidebar() {
            sidebarOpen = false;
            updateSidebarState();
        }

        function updateSidebarState() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            const backdrop = document.getElementById('mobile-backdrop');
            const desktopBtn = document.getElementById('desktop-toggle-btn');

            if (!sidebar || !mainContent) return;

            const isMobile = window.innerWidth < 1024;

            if (isMobile) {
                // Mobile behavior
                if (sidebarOpen) {
                    sidebar.classList.add('open');
                    backdrop.classList.add('show');
                    document.body.style.overflow = 'hidden'; // Prevent scrolling when menu is open
                } else {
                    sidebar.classList.remove('open');
                    backdrop.classList.remove('show');
                    document.body.style.overflow = '';
                }
                mainContent.classList.remove('sidebar-open', 'sidebar-collapsed');
            } else {
                // Desktop behavior
                backdrop.classList.remove('show');
                document.body.style.overflow = '';

                if (sidebarCollapsed) {
                    sidebar.classList.add('collapsed');
                    mainContent.classList.remove('sidebar-open');
                    mainContent.classList.add('sidebar-collapsed');
                    if (desktopBtn) {
                        desktopBtn.innerHTML = '<i class="fas fa-outdent text-xl"></i>';
                    }
                } else {
                    sidebar.classList.remove('collapsed');
                    mainContent.classList.add('sidebar-open');
                    mainContent.classList.remove('sidebar-collapsed');
                    if (desktopBtn) {
                        desktopBtn.innerHTML = '<i class="fas fa-indent text-xl"></i>';
                    }
                }
            }
        }

        // Handle window resize
        window.addEventListener('resize', function () {
            const wasMobile = window.innerWidth < 1024;
            setTimeout(() => {
                const isMobile = window.innerWidth < 1024;

                // If switching from mobile to desktop or vice versa, reinitialize
                if (wasMobile !== isMobile) {
                    initSidebar();
                } else {
                    updateSidebarState();
                }
            }, 100);
        });

        // Close sidebar when clicking navigation links on mobile
        document.addEventListener('click', function (e) {
            const link = e.target.closest('a[href]');
            if (link && window.innerWidth < 1024 && sidebarOpen) {
                const href = link.getAttribute('href');
                if (href && href !== '#' && !href.startsWith('javascript:')) {
                    closeSidebar();
                }
            }
        });

        // Toggle sidebar when clicking on sidebar area (desktop only)
        document.addEventListener('click', function (e) {
            const sidebar = document.getElementById('sidebar');
            const desktopBtn = document.getElementById('desktop-toggle-btn');

            // Only on desktop and when clicking directly on sidebar (not on links or buttons)
            if (window.innerWidth >= 1024 && sidebar && sidebar.contains(e.target)) {
                // Don't toggle if clicking on navigation links, buttons, or form elements
                const clickableElements = e.target.closest('a, button, input, select, textarea, [role="button"]');
                if (!clickableElements) {
                    toggleSidebar();
                }
            }
        });

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', initSidebar);
    </script>

    @stack('js')
</body>

</html>