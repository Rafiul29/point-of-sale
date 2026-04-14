<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $settings['meta_title'] ?? 'SnapPOS Management Software | Best POS System' }}</title>
    <meta name="description" content="{{ $settings['meta_description'] ?? 'SnapPOS is the leading POS management software for stores and restaurants in Bangladesh. Efficient inventory, sales, and customer management in one powerful dashboard.' }}" />
    <meta name="keywords" content="{{ $settings['meta_keywords'] ?? 'SnapPOS, POS management software, point of sale software, retail POS, restaurant POS, Bangladesh' }}" />
    <meta name="robots" content="index, follow" />
    <link rel="canonical" href="{{ url()->current() }}" />
    @if(isset($settings['site_favicon']))
    <link rel="icon" href="{{ asset('storage/' . $settings['site_favicon']) }}">
    @else
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    @endif
    <meta property="og:title" content="{{ $settings['meta_title'] ?? 'SnapPOS Management Software | Best POS System' }}" />
    <meta property="og:description" content="{{ $settings['meta_description'] ?? 'SnapPOS is the leading POS management software for stores and restaurants in Bangladesh. Efficient inventory, sales, and customer management in one powerful dashboard.' }}" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:image" content="{{ isset($settings['og_image']) ? asset('storage/' . $settings['og_image']) : asset('images/zipsoftbd-pos-preview.png') }}" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="{{ $settings['meta_title'] ?? 'SnapPOS Management Software | Best POS System' }}" />
    <meta name="twitter:description" content="{{ $settings['meta_description'] ?? 'SnapPOS is the leading POS management software for stores and restaurants in Bangladesh.' }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700;800&family=Open+Sans:wght@300;400;500;600&display=swap"
        rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --lp-bg: #0B1120;
            --lp-surface: #111827;
            --lp-surface-2: #1A2436;
            --lp-border: rgba(255, 255, 255, 0.08);
            --lp-primary: #4F46E5;
            --lp-primary-h: #4338CA;
            --lp-accent: #818CF8;
            --lp-cyan: #06B6D4;
            --lp-violet: #7C3AED;
            --lp-success: #10B981;
            --lp-warning: #F59E0B;
            --lp-text: #F1F5F9;
            --lp-muted: #94A3B8;
            --lp-faint: rgba(241, 245, 249, 0.06);
            --font-head: 'Sora', sans-serif;
            --font-body: 'Open Sans', sans-serif;
        }


        body {
            font-family: var(--font-body);
            background-color: var(--lp-bg);
            color: var(--lp-text);
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        h1,
        h2,
        h3,
        h4,
        h5 {
            font-family: var(--font-head);
        }


        .aurora-wrap {
            position: relative;
            overflow: hidden;
        }

        .aurora-wrap::before,
        .aurora-wrap::after,
        .aurora-blob-3 {
            content: '';
            position: absolute;
            border-radius: 9999px;
            filter: blur(120px);
            pointer-events: none;
            z-index: 0;
            opacity: .35;
            animation: floatBlob 10s ease-in-out infinite alternate;
        }

        .aurora-wrap::before {
            width: 700px;
            height: 700px;
            background: var(--lp-primary);
            top: -200px;
            left: -200px;
            animation-duration: 12s;
        }

        .aurora-wrap::after {
            width: 600px;
            height: 600px;
            background: var(--lp-violet);
            top: 100px;
            right: -150px;
            animation-duration: 9s;
            animation-delay: -3s;
        }

        .aurora-blob-3 {
            width: 500px;
            height: 500px;
            background: var(--lp-cyan);
            bottom: -100px;
            left: 40%;
            animation-duration: 14s;
            animation-delay: -6s;
        }

        @keyframes floatBlob {
            0% {
                transform: translate(0, 0) scale(1);
            }

            100% {
                transform: translate(40px, 30px) scale(1.06);
            }
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid var(--lp-border);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }


        #navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 100;
            transition: background .3s, backdrop-filter .3s, border-color .3s;
        }

        #navbar.scrolled {
            background: rgba(11, 17, 32, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--lp-border);
        }

        .nav-link {
            font-family: var(--font-body);
            font-size: .875rem;
            font-weight: 500;
            color: var(--lp-muted);
            transition: color .2s;
            text-decoration: none;
        }

        .nav-link:hover {
            color: var(--lp-text);
        }


        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            background: var(--lp-primary);
            color: #fff;
            font-family: var(--font-head);
            font-weight: 600;
            font-size: .9rem;
            padding: .75rem 1.75rem;
            border-radius: .75rem;
            border: none;
            cursor: pointer;
            transition: background .2s, transform .15s, box-shadow .2s;
            text-decoration: none;
            box-shadow: 0 0 0 0 rgba(79, 70, 229, 0);
        }

        .btn-primary:hover {
            background: var(--lp-primary-h);
            transform: translateY(-1px);
            box-shadow: 0 8px 30px rgba(79, 70, 229, .45);
        }

        .btn-outline {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            background: transparent;
            color: var(--lp-text);
            font-family: var(--font-head);
            font-weight: 600;
            font-size: .9rem;
            padding: .73rem 1.75rem;
            border-radius: .75rem;
            border: 1px solid var(--lp-border);
            cursor: pointer;
            transition: border-color .2s, background .2s, transform .15s;
            text-decoration: none;
        }

        .btn-outline:hover {
            border-color: rgba(255, 255, 255, .25);
            background: var(--lp-faint);
            transform: translateY(-1px);
        }


        .section-chip {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            background: rgba(79, 70, 229, .15);
            border: 1px solid rgba(79, 70, 229, .3);
            color: var(--lp-accent);
            font-family: var(--font-head);
            font-size: .75rem;
            font-weight: 600;
            letter-spacing: .08em;
            text-transform: uppercase;
            padding: .35rem 1rem;
            border-radius: 999px;
        }


        .stat-card {
            position: absolute;
            border-radius: 1rem;
            padding: .85rem 1.1rem;
            font-family: var(--font-body);
            min-width: 160px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, .5);
        }


        .feature-card {
            background: var(--lp-surface);
            border: 1px solid var(--lp-border);
            border-radius: 1.25rem;
            padding: 1.75rem;
            transition: transform .2s, border-color .2s, box-shadow .2s;
        }

        .feature-card:hover {
            transform: translateY(-4px);
            border-color: rgba(79, 70, 229, .4);
            box-shadow: 0 0 40px rgba(79, 70, 229, .12);
        }

        .feature-icon {
            width: 3rem;
            height: 3rem;
            border-radius: .875rem;
            background: rgba(79, 70, 229, .15);
            border: 1px solid rgba(79, 70, 229, .25);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--lp-accent);
            font-size: 1.1rem;
            margin-bottom: 1rem;
            flex-shrink: 0;
        }


        .step-connector {
            flex: 1;
            height: 2px;
            background: linear-gradient(90deg, rgba(79, 70, 229, .4), rgba(79, 70, 229, .08));
            margin-top: -2rem;
        }


        .cta-section {
            background: linear-gradient(135deg, #3730A3 0%, #4F46E5 50%, #6D28D9 100%);
            position: relative;
            overflow: hidden;
        }

        .cta-section::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.06'/%3E%3C/svg%3E");
            opacity: .18;
        }


        .mockup-wrap {
            background: var(--lp-surface-2);
            border: 1px solid var(--lp-border);
            border-radius: 1.5rem;
            overflow: hidden;
            box-shadow: 0 40px 100px rgba(0, 0, 0, .6), 0 0 0 1px rgba(255, 255, 255, .05);
        }

        .mockup-topbar {
            background: var(--lp-surface);
            border-bottom: 1px solid var(--lp-border);
            padding: .75rem 1.25rem;
            display: flex;
            align-items: center;
            gap: .5rem;
        }

        .mockup-dot {
            width: .6rem;
            height: .6rem;
            border-radius: 50%;
        }


        .stats-wrap {
            border-top: 1px solid var(--lp-border);
            border-bottom: 1px solid var(--lp-border);
            background: var(--lp-surface);
        }

        .stat-item+.stat-item {
            border-left: 1px solid var(--lp-border);
        }


        footer {
            border-top: 1px solid var(--lp-border);
            background: var(--lp-surface);
        }


        .reveal {
            opacity: 0;
            transform: translateY(24px);
            transition: opacity .65s ease, transform .65s ease;
        }

        .reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }


        #mobile-menu {
            display: none;
        }

        #mobile-menu.open {
            display: block;
        }
    </style>
</head>

<body>


     <nav id="navbar" class="px-6 py-4">
        <div class="max-w-7xl mx-auto flex items-center justify-between">


            <a href="/" class="flex items-center gap-2.5" style="text-decoration:none">
                <div
                    style="width:2.2rem;height:2.2rem;border-radius:.625rem;background:var(--lp-primary);display:flex;align-items:center;justify-content:center;box-shadow:0 0 20px rgba(79,70,229,.5);overflow:hidden;">
                    @if(isset($settings['site_logo']))
                        <img src="{{ asset('storage/' . $settings['site_logo']) }}" alt="{{ $settings['shop_name'] ?? 'Logo' }}" style="width:100%;height:100%;object-fit:cover;">
                    @else
                        <i class="fas fa-bolt" style="color:#fff;font-size:.9rem"></i>
                    @endif
                </div>
                <span
                    style="font-family:var(--font-head);font-weight:700;font-size:1.15rem;color:var(--lp-text)">{{ $settings['shop_name'] ?? 'SnapPOS' }}</span>
            </a>

            <div class="hidden md:flex items-center gap-8">
                <a href="#features" class="nav-link">Features</a>
                <a href="#how-it-works" class="nav-link">How It Works</a>
                <a href="#stats" class="nav-link">Why Us</a>
            </div>

            <div class="hidden md:flex items-center gap-3">
                @if (!auth()->check())
                    <a href="{{ route('login') }}" class="btn-primary" style="padding:.6rem 1.3rem;font-size:.85rem">
                        <i class="fas fa-rocket" style="font-size:.8rem"></i>
                        Get Started
                    </a>
                @elseif(auth()->user()->role === 'Admin')
                    <a href="{{ route('dashboard') }}" class="btn-primary" style="padding:.6rem 1.3rem;font-size:.85rem">
                        <i class="fas fa-gauge" style="font-size:.8rem"></i>
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('cashier.dashboard') }}" class="btn-primary" style="padding:.6rem 1.3rem;font-size:.85rem">
                        <i class="fas fa-cash-register" style="font-size:.8rem"></i>
                        POS Terminal
                    </a>
                @endif
            </div>


            <button id="burger-btn" class="md:hidden flex flex-col gap-1.5 p-2"
                style="background:none;border:none;cursor:pointer">
                <span
                    style="display:block;width:22px;height:2px;background:var(--lp-muted);border-radius:2px;transition:.3s"></span>
                <span
                    style="display:block;width:22px;height:2px;background:var(--lp-muted);border-radius:2px;transition:.3s"></span>
                <span
                    style="display:block;width:22px;height:2px;background:var(--lp-muted);border-radius:2px;transition:.3s"></span>
            </button>
        </div>

        <div id="mobile-menu" class="md:hidden mt-4 pb-4 border-t" style="border-color:var(--lp-border)">
            <div class="flex flex-col gap-4 pt-4 ">
                <a href="#features" class="nav-link pl-2">Features</a>
                <a href="#how-it-works" class="nav-link pl-2">How It Works</a>
                <a href="#stats" class="nav-link pl-2">Why Us</a>

                @if (!auth()->check())
                    <div class="flex gap-3 pt-2 pl-2">
                        <a href="{{ route('login') }}" class="btn-outline"
                            style="padding:.6rem 1.2rem;font-size:.85rem">Log in</a>
                        <a href="{{ route('login') }}" class="btn-primary"
                            style="padding:.6rem 1.2rem;font-size:.85rem">Get Started</a>
                    </div>
                @elseif(auth()->user()->role === 'Admin')
                    <div class="flex gap-3 pt-2 pl-2">
                        <a href="{{ route('dashboard') }}" class="btn-primary"
                            style="padding:.6rem 1.2rem;font-size:.85rem">Go to Dashboard</a>
                    </div>
                @else
                    <div class="flex gap-3 pt-2 pl-2">
                        <a href="{{ route('cashier.dashboard') }}" class="btn-primary"
                            style="padding:.6rem 1.2rem;font-size:.85rem">Go to POS Terminal</a>
                    </div>
                @endif
            </div>
        </div>
    </nav>



    <section class="aurora-wrap"
        style="min-height:100vh;display:flex;align-items:center;padding-top:7rem;padding-bottom:6rem;position:relative;">
        <div class="aurora-blob-3"></div>
        <div class="max-w-7xl mx-auto px-6 w-full" style="position:relative;z-index:1">
            <div class="grid lg:grid-cols-2 gap-16 items-center">

                <div class="reveal">
                    <div class="section-chip mb-6">
                        <i class="fas fa-star" style="font-size:.65rem"></i>
                        Trusted by 1,000+ retailers
                    </div>
                    <h1
                        style="font-size:clamp(2.6rem,5vw,4rem);font-weight:800;line-height:1.12;letter-spacing:-.03em;color:var(--lp-text);margin-bottom:1.25rem">
                        Run Your Store.<br>
                        <span
                            style="background:linear-gradient(135deg,var(--lp-accent),var(--lp-cyan));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text">
                            Smarter. Faster.
                        </span>
                    </h1>
                    <p
                        style="font-size:1.1rem;line-height:1.7;color:var(--lp-muted);max-width:480px;margin-bottom:2.25rem;font-weight:300">
                        The all-in-one point of sale system built for modern retail &mdash; manage inventory, track
                        sales, and delight customers from one elegant dashboard.
                    </p>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('login') }}" class="btn-primary">
                            <i class="fas fa-rocket" style="font-size:.85rem"></i>
                            Get Started Free
                        </a>
                        <a href="#how-it-works" class="btn-outline">
                            <i class="fas fa-play-circle" style="font-size:.9rem;color:var(--lp-accent)"></i>
                            See It In Action
                        </a>
                    </div>


                    <div class="flex items-center gap-3 mt-8">
                        <div class="flex -space-x-2">
                            @foreach (['sarah', 'james', 'lisa', 'mike'] as $u)
                                <img src="https://i.pravatar.cc/36?u={{ $u }}" alt="{{ $u }}"
                                    style="width:2rem;height:2rem;border-radius:50%;border:2px solid var(--lp-bg);object-fit:cover">
                            @endforeach
                        </div>
                        <div>
                            <div style="display:flex;gap:2px">
                                @for ($i = 0; $i < 5; $i++)
                                    <i class="fas fa-star" style="color:var(--lp-warning);font-size:.7rem"></i>
                                @endfor
                            </div>
                            <p style="font-size:.78rem;color:var(--lp-muted);margin-top:.15rem">From <strong
                                    style="color:var(--lp-text)">1,000+</strong> happy store owners</p>
                        </div>
                    </div>
                </div>


                <div class="reveal" style="position:relative;padding:2rem 0">


                    <div class="stat-card glass-card stat-card-1"
                        style="z-index:10;animation:floatBlob 6s ease-in-out infinite alternate">
                        <div style="display:flex;align-items:center;gap:.6rem;margin-bottom:.3rem">
                            <div
                                style="width:1.8rem;height:1.8rem;border-radius:.5rem;background:rgba(16,185,129,.15);display:flex;align-items:center;justify-content:center">
                                <i class="fas fa-dollar-sign" style="color:var(--lp-success);font-size:.75rem"></i>
                            </div>
                            <span style="font-size:.72rem;color:var(--lp-muted);font-weight:500">Total Revenue</span>
                        </div>
                        <p
                            style="font-family:var(--font-head);font-size:1.35rem;font-weight:700;color:var(--lp-text);margin:0">
                            $24,890</p>
                        <p style="font-size:.68rem;color:var(--lp-success);margin-top:.2rem"><i
                                class="fas fa-arrow-up" style="margin-right:.2rem"></i>+12.5% from last month</p>
                    </div>


                    <div class="stat-card glass-card stat-card-2"
                        style="z-index:10;animation:floatBlob 8s ease-in-out infinite alternate-reverse">
                        <div style="display:flex;align-items:center;gap:.6rem;margin-bottom:.3rem">
                            <div
                                style="width:1.8rem;height:1.8rem;border-radius:.5rem;background:rgba(79,70,229,.15);display:flex;align-items:center;justify-content:center">
                                <i class="fas fa-shopping-bag" style="color:var(--lp-accent);font-size:.75rem"></i>
                            </div>
                            <span style="font-size:.72rem;color:var(--lp-muted);font-weight:500">Orders Today</span>
                        </div>
                        <p
                            style="font-family:var(--font-head);font-size:1.35rem;font-weight:700;color:var(--lp-text);margin:0">
                            148</p>
                        <p style="font-size:.68rem;color:var(--lp-accent);margin-top:.2rem"><i class="fas fa-arrow-up"
                                style="margin-right:.2rem"></i>+8 since yesterday</p>
                    </div>


                    <div class="mockup-wrap">
                        <div class="mockup-topbar">
                            <div class="mockup-dot" style="background:#FF5F57"></div>
                            <div class="mockup-dot" style="background:#FEBC2E"></div>
                            <div class="mockup-dot" style="background:#28C840"></div>
                            <div style="flex:1;margin-left:.75rem">
                                <div
                                    style="background:rgba(255,255,255,.05);border-radius:.375rem;height:1.2rem;width:55%;display:flex;align-items:center;padding:0 .5rem">
                                    <span style="font-size:.6rem;color:var(--lp-muted)">pos.snappos.io/dashboard</span>
                                </div>
                            </div>
                        </div>


                        <div style="display:grid;grid-template-columns:3rem 1fr;min-height:280px">

                            <div
                                style="background:rgba(0,0,0,.2);border-right:1px solid var(--lp-border);display:flex;flex-direction:column;align-items:center;gap:1rem;padding:1rem 0">
                                @foreach (['fa-gauge', 'fa-cash-register', 'fa-box', 'fa-chart-bar', 'fa-users', 'fa-gear'] as $icon)
                                    <div
                                        style="width:2rem;height:2rem;border-radius:.5rem;background:{{ $loop->first ? 'rgba(79,70,229,.3)' : 'transparent' }};display:flex;align-items:center;justify-content:center">
                                        <i class="fas {{ $icon }}"
                                            style="font-size:.75rem;color:{{ $loop->first ? 'var(--lp-accent)' : 'rgba(148,163,184,.4)' }}"></i>
                                    </div>
                                @endforeach
                            </div>


                            <div style="padding:1rem;overflow:hidden">

                                <div
                                    style="display:grid;grid-template-columns:repeat(3,1fr);gap:.5rem;margin-bottom:.8rem">
                                    @foreach ([['$24,890', 'Revenue', 'var(--lp-success)', 'fa-arrow-up'], ['148', 'Orders', 'var(--lp-accent)', 'fa-arrow-up'], ['37', 'Products', 'var(--lp-warning)', 'fa-minus']] as [$val, $label, $clr, $icon])
                                        <div
                                            style="background:rgba(255,255,255,.04);border:1px solid var(--lp-border);border-radius:.625rem;padding:.5rem">
                                            <div
                                                style="font-family:var(--font-head);font-size:.85rem;font-weight:700;color:var(--lp-text)">
                                                {{ $val }}</div>
                                            <div style="font-size:.6rem;color:var(--lp-muted)">{{ $label }}
                                            </div>
                                            <div style="font-size:.55rem;color:{{ $clr }};margin-top:.2rem">
                                                <i class="fas {{ $icon }}" style="font-size:.5rem"></i> Today
                                            </div>
                                        </div>
                                    @endforeach
                                </div>


                                <div
                                    style="background:rgba(255,255,255,.03);border:1px solid var(--lp-border);border-radius:.625rem;padding:.75rem;margin-bottom:.7rem">
                                    <div
                                        style="font-size:.6rem;color:var(--lp-muted);margin-bottom:.5rem;font-weight:500">
                                        Weekly Sales</div>
                                    <div style="display:flex;align-items:flex-end;gap:3px;height:3rem">
                                        @foreach ([40, 65, 50, 80, 60, 90, 75] as $h)
                                            <div
                                                style="flex:1;background:linear-gradient(180deg,var(--lp-primary),var(--lp-violet));border-radius:3px 3px 0 0;height:{{ $h }}%;opacity:{{ $loop->last ? '1' : '0.55' }}">
                                            </div>
                                        @endforeach
                                    </div>
                                    <div style="display:flex;justify-content:space-between;margin-top:.25rem">
                                        @foreach (['M', 'T', 'W', 'T', 'F', 'S', 'S'] as $d)
                                            <span
                                                style="font-size:.5rem;color:var(--lp-muted)">{{ $d }}</span>
                                        @endforeach
                                    </div>
                                </div>


                                <div
                                    style="font-size:.6rem;color:var(--lp-muted);margin-bottom:.35rem;font-weight:500">
                                    Recent Transactions</div>
                                @foreach ([['John D.', 'Groceries', '$42.50'], ['Maria L.', 'Electronics', '$189.00'], ['Sam K.', 'Clothing', '$67.20']] as [$name, $cat, $amt])
                                    <div
                                        style="display:flex;align-items:center;justify-content:space-between;padding:.3rem 0;border-bottom:1px solid rgba(255,255,255,.04)">
                                        <div style="display:flex;align-items:center;gap:.4rem">
                                            <div
                                                style="width:1.2rem;height:1.2rem;border-radius:50%;background:rgba(79,70,229,.2);display:flex;align-items:center;justify-content:center">
                                                <i class="fas fa-user"
                                                    style="font-size:.45rem;color:var(--lp-accent)"></i>
                                            </div>
                                            <div>
                                                <div style="font-size:.6rem;color:var(--lp-text);font-weight:500">
                                                    {{ $name }}</div>
                                                <div style="font-size:.52rem;color:var(--lp-muted)">
                                                    {{ $cat }}</div>
                                            </div>
                                        </div>
                                        <span
                                            style="font-size:.62rem;font-weight:600;color:var(--lp-success)">{{ $amt }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>



    <div id="stats" class="stats-wrap reveal" style="position:relative;z-index:1">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-2 md:grid-cols-4">
                @foreach ([['10,000+', 'Products Managed', 'fa-box'], ['50,000+', 'Transactions Processed', 'fa-receipt'], ['99.9%', 'System Uptime', 'fa-server'], ['24/7', 'Support Available', 'fa-headset']] as [$num, $label, $icon])
                    <div class="stat-item py-8 px-6 text-center">
                        <div style="display:flex;justify-content:center;margin-bottom:.5rem">
                            <div
                                style="width:2.5rem;height:2.5rem;border-radius:.75rem;background:rgba(79,70,229,.12);display:flex;align-items:center;justify-content:center">
                                <i class="fas {{ $icon }}" style="color:var(--lp-accent);font-size:1rem"></i>
                            </div>
                        </div>
                        <div
                            style="font-family:var(--font-head);font-size:1.75rem;font-weight:800;color:var(--lp-text)">
                            {{ $num }}</div>
                        <div style="font-size:.82rem;color:var(--lp-muted);margin-top:.25rem">{{ $label }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>



    <section id="features" style="padding:6rem 0;background:var(--lp-bg)">
        <div class="max-w-7xl mx-auto px-6">

            <div class="text-center mb-14 reveal">
                <div class="section-chip mb-4" style="display:inline-flex">
                    <i class="fas fa-layer-group" style="font-size:.65rem"></i>
                    Everything You Need
                </div>
                <h2
                    style="font-size:clamp(1.9rem,3.5vw,2.75rem);font-weight:700;color:var(--lp-text);margin-bottom:1rem">
                    Built to Sell More
                </h2>
                <p
                    style="color:var(--lp-muted);font-size:1.05rem;max-width:520px;margin:0 auto;line-height:1.7;font-weight:300">
                    Built for speed and simplicity, our POS system gives you the tools to run your store like a pro.
                </p>
            </div>


            <div class="grid-features reveal">


                <div class="feature-card feature-card-row-span-2"
                    style="display:flex;flex-direction:column;justify-content:space-between;background:linear-gradient(135deg,rgba(79,70,229,.08),rgba(109,40,217,.06))">
                    <div>
                        <div class="feature-icon">
                            <i class="fas fa-cash-register"></i>
                        </div>
                        <h3 style="font-size:1.2rem;font-weight:700;color:var(--lp-text);margin-bottom:.6rem">Lightning
                            Fast POS Terminal</h3>
                        <p style="font-size:.88rem;color:var(--lp-muted);line-height:1.65">Process sales in seconds
                            with our intuitive terminal interface. Barcode scanning, quick search, and one-click
                            checkout — built for speed.</p>
                    </div>

                    <div
                        style="margin-top:1.5rem;background:rgba(0,0,0,.25);border-radius:1rem;padding:1rem;border:1px solid var(--lp-border)">
                        <div
                            style="background:rgba(79,70,229,.15);border-radius:.625rem;padding:.75rem;display:flex;justify-content:space-between;align-items:center;margin-bottom:.5rem">
                            <div style="display:flex;align-items:center;gap:.5rem">
                                <div
                                    style="width:2rem;height:2rem;border-radius:.375rem;background:rgba(79,70,229,.2);display:flex;align-items:center;justify-content:center">
                                    <i class="fas fa-barcode" style="font-size:.7rem;color:var(--lp-accent)"></i>
                                </div>
                                <div>
                                    <div style="font-size:.65rem;font-weight:600;color:var(--lp-text)">Coffee Beans
                                        250g</div>
                                    <div style="font-size:.55rem;color:var(--lp-muted)">SKU: CB-250</div>
                                </div>
                            </div>
                            <div style="font-size:.75rem;font-weight:700;color:var(--lp-success)">$12.99</div>
                        </div>
                        <div style="display:flex;gap:.4rem">
                            @foreach (['1', '2', '3', '⌫', '0', '.'] as $k)
                                <div
                                    style="flex:1;background:rgba(255,255,255,.05);border:1px solid var(--lp-border);border-radius:.375rem;height:1.6rem;display:flex;align-items:center;justify-content:center;font-size:.65rem;color:var(--lp-muted)">
                                    {{ $k }}</div>
                            @endforeach
                        </div>
                        <div
                            style="margin-top:.5rem;background:var(--lp-primary);border-radius:.5rem;padding:.5rem;text-align:center;font-size:.65rem;font-weight:700;color:#fff">
                            <i class="fas fa-check" style="margin-right:.3rem"></i>Checkout — $12.99
                        </div>
                    </div>
                </div>


                <div class="feature-card">
                    <div class="feature-icon"
                        style="background:rgba(16,185,129,.1);border-color:rgba(16,185,129,.25);color:var(--lp-success)">
                        <i class="fas fa-boxes-stacked"></i>
                    </div>
                    <h3 style="font-size:1.05rem;font-weight:700;color:var(--lp-text);margin-bottom:.5rem">Smart
                        Inventory</h3>
                    <p style="font-size:.85rem;color:var(--lp-muted);line-height:1.6">Track stock levels in real-time.
                        Low-stock alerts, supplier management, and automated reordering.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon"
                        style="background:rgba(245,158,11,.1);border-color:rgba(245,158,11,.25);color:var(--lp-warning)">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3 style="font-size:1.05rem;font-weight:700;color:var(--lp-text);margin-bottom:.5rem">Powerful
                        Reports</h3>
                    <p style="font-size:.85rem;color:var(--lp-muted);line-height:1.6">Detailed analytics — daily,
                        weekly, and monthly reports with actionable insights at your fingertips.</p>
                </div>


                <div class="feature-card feature-card-col-span-2" style="display:flex;gap:2rem">
                    <div style="flex:1">
                        <div style="display:flex;gap:1.2rem;margin-bottom:1.25rem">
                            <div class="feature-icon"
                                style="background:rgba(6,182,212,.1);border-color:rgba(6,182,212,.25);color:var(--lp-cyan);margin-bottom:0">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="feature-icon"
                                style="background:rgba(124,58,237,.1);border-color:rgba(124,58,237,.25);color:#A78BFA;margin-bottom:0">
                                <i class="fas fa-user-shield"></i>
                            </div>
                        </div>
                        <h3 style="font-size:1.15rem;font-weight:700;color:var(--lp-text);margin-bottom:.6rem">Customer
                            &amp; Team Management</h3>
                        <p style="font-size:.87rem;color:var(--lp-muted);line-height:1.65">Build loyalty by tracking
                            purchase history. Set up Admin and Cashier roles with granular permissions for your entire
                            team — all from one place.</p>
                    </div>

                    <div style="display:flex;flex-direction:column;gap:.6rem;min-width:160px">
                        @foreach ([['Admin', 'fa-crown', 'rgba(245,158,11,.15)', 'var(--lp-warning)', 'Full Access'], ['Cashier', 'fa-cash-register', 'rgba(79,70,229,.15)', 'var(--lp-accent)', 'POS + Customers']] as [$role, $icon, $bg, $clr, $access])
                            <div
                                style="background:{{ $bg }};border-radius:.75rem;padding:.65rem .85rem;display:flex;align-items:center;gap:.65rem;border:1px solid rgba(255,255,255,.07)">
                                <i class="fas {{ $icon }}"
                                    style="color:{{ $clr }};font-size:.85rem;width:1rem;text-align:center"></i>
                                <div>
                                    <div style="font-size:.72rem;font-weight:700;color:var(--lp-text)">
                                        {{ $role }}</div>
                                    <div style="font-size:.62rem;color:var(--lp-muted)">{{ $access }}</div>
                                </div>
                            </div>
                        @endforeach
                        <div
                            style="background:rgba(16,185,129,.1);border-radius:.75rem;padding:.65rem .85rem;display:flex;align-items:center;gap:.65rem;border:1px solid rgba(16,185,129,.2)">
                            <i class="fas fa-wifi"
                                style="color:var(--lp-success);font-size:.85rem;width:1rem;text-align:center"></i>
                            <div>
                                <div style="font-size:.72rem;font-weight:700;color:var(--lp-text)">Real-Time Sync</div>
                                <div style="font-size:.62rem;color:var(--lp-muted)">All devices, instantly</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>



    <section id="how-it-works" style="padding:6rem 0;background:var(--lp-surface)">
        <div class="max-w-7xl mx-auto px-6">

            <div class="text-center mb-14 reveal">
                <div class="section-chip mb-4" style="display:inline-flex">
                    <i class="fas fa-map-signs" style="font-size:.65rem"></i>
                    How It Works
                </div>
                <h2
                    style="font-size:clamp(1.9rem,3.5vw,2.75rem);font-weight:700;color:var(--lp-text);margin-bottom:1rem">
                    Up and Running in Minutes
                </h2>
                <p
                    style="color:var(--lp-muted);font-size:1.05rem;max-width:480px;margin:0 auto;line-height:1.7;font-weight:300">
                    Get up and running in minutes, not days.
                </p>
            </div>

            <div class="reveal grid-how-it-works">

                @foreach ([['01', 'fa-user-plus', 'Create Your Account', 'Sign up and configure your store settings, logo, and business details in under 5 minutes.', 'var(--lp-primary)'], ['02', 'fa-box-open', 'Set Up Your Products', 'Import your product catalog, set prices, manage categories, and configure suppliers with ease.', 'var(--lp-cyan)'], ['03', 'fa-rocket', 'Start Selling', 'Launch your POS terminal and start processing sales immediately. It&apos;s that simple.', 'var(--lp-success)']] as $i => [$num, $icon, $title, $desc, $clr])
                    <div style="text-align:center;padding:0 1rem">

                        <div style="position:relative;display:inline-block;margin-bottom:1.5rem">
                            <div
                                style="width:4rem;height:4rem;border-radius:50%;background:rgba({{ $i === 0 ? '79,70,229' : ($i === 1 ? '6,182,212' : '16,185,129') }},.15);border:2px solid rgba({{ $i === 0 ? '79,70,229' : ($i === 1 ? '6,182,212' : '16,185,129') }},.35);display:flex;align-items:center;justify-content:center;margin:0 auto">
                                <i class="fas {{ $icon }}"
                                    style="font-size:1.25rem;color:{{ $clr }}"></i>
                            </div>
                            <div
                                style="position:absolute;top:-4px;right:-4px;width:1.4rem;height:1.4rem;border-radius:50%;background:{{ $clr }};display:flex;align-items:center;justify-content:center;font-family:var(--font-head);font-size:.6rem;font-weight:800;color:#fff">
                                {{ $num }}</div>
                        </div>
                        <h3 style="font-size:1.05rem;font-weight:700;color:var(--lp-text);margin-bottom:.6rem">
                            {{ $title }}</h3>
                        <p style="font-size:.85rem;color:var(--lp-muted);line-height:1.65">{!! $desc !!}</p>
                    </div>

                    @if (!$loop->last)
                        <div class="step-chevron">
                            <div
                                style="width:80px;height:2px;background:linear-gradient(90deg,rgba(79,70,229,.5),rgba(79,70,229,.1));border-top:2px dashed rgba(79,70,229,.3)">
                            </div>
                            <i class="fas fa-chevron-right"
                                style="color:rgba(79,70,229,.5);font-size:.75rem;margin:0 .25rem"></i>
                        </div>
                    @endif
                @endforeach

            </div>
        </div>
    </section>



    <section class="cta-section" style="padding:6rem 0;position:relative">
        <div class="max-w-4xl mx-auto px-6 text-center reveal" style="position:relative;z-index:1">
            <div class="section-chip mb-6"
                style="display:inline-flex;background:rgba(255,255,255,.12);border-color:rgba(255,255,255,.2);color:#fff">
                <i class="fas fa-bolt" style="font-size:.65rem"></i>
                Start Today
            </div>
            <h2
                style="font-size:clamp(2rem,4vw,3rem);font-weight:800;color:#fff;letter-spacing:-.02em;margin-bottom:1.25rem">
                Ready to Transform<br>Your Business?
            </h2>
            <p
                style="font-size:1.1rem;color:rgba(255,255,255,.75);max-width:440px;margin:0 auto 2.5rem;line-height:1.7;font-weight:300">
                Join thousands of retailers using our POS system to run smarter, faster stores.
            </p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="{{ route('login') }}"
                    style="display:inline-flex;align-items:center;gap:.6rem;background:#fff;color:var(--lp-primary);font-family:var(--font-head);font-weight:700;font-size:.95rem;padding:.85rem 2rem;border-radius:.875rem;text-decoration:none;transition:transform .2s,box-shadow .2s;box-shadow:0 8px 30px rgba(0,0,0,.25)"
                    onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 14px 40px rgba(0,0,0,.35)'"
                    onmouseout="this.style.transform='';this.style.boxShadow='0 8px 30px rgba(0,0,0,.25)'">
                    <i class="fas fa-rocket" style="font-size:.85rem"></i>
                    Start For Free
                </a>
                <a href="mailto:sales@snappos.io"
                    style="display:inline-flex;align-items:center;gap:.6rem;background:transparent;color:#fff;font-family:var(--font-head);font-weight:600;font-size:.95rem;padding:.83rem 2rem;border-radius:.875rem;border:2px solid rgba(255,255,255,.35);text-decoration:none;transition:border-color .2s,background .2s"
                    onmouseover="this.style.borderColor='rgba(255,255,255,.6)';this.style.background='rgba(255,255,255,.08)'"
                    onmouseout="this.style.borderColor='rgba(255,255,255,.35)';this.style.background='transparent'">
                    <i class="fas fa-envelope" style="font-size:.85rem"></i>
                    Contact Sales
                </a>
            </div>
        </div>
    </section>



    <footer style="padding:4rem 0 2rem">
        <div class="max-w-7xl mx-auto px-6">
            <div style="display:grid;grid-template-columns:2fr 1fr 1fr 1fr;gap:3rem;margin-bottom:3rem"
                class="grid-footer">


                <div>
                    <a href="/"
                        style="display:inline-flex;align-items:center;gap:.75rem;text-decoration:none;margin-bottom:1rem">
                        <div
                            style="width:2rem;height:2rem;border-radius:.5rem;background:var(--lp-primary);display:flex;align-items:center;justify-content:center;overflow:hidden;">
                            @if(isset($settings['site_logo']))
                                <img src="{{ asset('storage/' . $settings['site_logo']) }}" alt="Logo" style="width:100%;height:100%;object-fit:cover;">
                            @else
                                <i class="fas fa-bolt" style="color:#fff;font-size:.8rem"></i>
                            @endif
                        </div>
                        <span
                            style="font-family:var(--font-head);font-weight:700;font-size:1rem;color:var(--lp-text)">{{ $settings['shop_name'] ?? 'SnapPOS' }}</span>
                    </a>
                    
                    @if(isset($settings['shop_address']) || isset($settings['shop_phone']))
                    <div style="font-size:.85rem;color:var(--lp-muted);line-height:1.65;margin-bottom:1rem;">
                        @if(isset($settings['shop_address']))
                        <div style="display:flex;gap:0.5rem;align-items:flex-start;"><i class="fas fa-map-marker-alt" style="margin-top:0.2rem;width:1rem;"></i> <span>{{ $settings['shop_address'] }}</span></div>
                        @endif
                        @if(isset($settings['shop_phone']))
                        <div style="display:flex;gap:0.5rem;align-items:center;margin-top:0.4rem;"><i class="fas fa-phone-alt" style="width:1rem;"></i> <span>{{ $settings['shop_phone'] }}</span></div>
                        @endif
                    </div>
                    @else
                    <p style="font-size:.85rem;color:var(--lp-muted);line-height:1.65;max-width:240px">The modern POS
                        system for ambitious retailers.</p>
                    @endif

                    <div style="display:flex;gap:.75rem;margin-top:1.25rem">
                        @if(isset($settings['facebook_url']))
                            <a href="{{ $settings['facebook_url'] }}" target="_blank"
                                style="width:2rem;height:2rem;border-radius:.5rem;background:var(--lp-faint);border:1px solid var(--lp-border);display:flex;align-items:center;justify-content:center;color:var(--lp-muted);text-decoration:none;transition:color .2s,border-color .2s"
                                onmouseover="this.style.color='var(--lp-text)';this.style.borderColor='rgba(255,255,255,.2)'"
                                onmouseout="this.style.color='var(--lp-muted)';this.style.borderColor='var(--lp-border)'">
                                <i class="fab fa-facebook-f" style="font-size:.75rem"></i>
                            </a>
                        @endif
                        @foreach (['fa-twitter', 'fa-linkedin', 'fa-instagram'] as $si)
                            <a href="#"
                                style="width:2rem;height:2rem;border-radius:.5rem;background:var(--lp-faint);border:1px solid var(--lp-border);display:flex;align-items:center;justify-content:center;color:var(--lp-muted);text-decoration:none;transition:color .2s,border-color .2s"
                                onmouseover="this.style.color='var(--lp-text)';this.style.borderColor='rgba(255,255,255,.2)'"
                                onmouseout="this.style.color='var(--lp-muted)';this.style.borderColor='var(--lp-border)'">
                                <i class="fab {{ $si }}" style="font-size:.75rem"></i>
                            </a>
                        @endforeach
                    </div>
                </div>


                <div>
                    <h4
                        style="font-family:var(--font-head);font-size:.8rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--lp-text);margin-bottom:1rem">
                        Product</h4>
                    <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:.6rem">
                        @foreach (['Features', 'Pricing', 'Integrations', 'Changelog'] as $link)
                            <li><a href="#"
                                    style="font-size:.85rem;color:var(--lp-muted);text-decoration:none;transition:color .2s"
                                    onmouseover="this.style.color='var(--lp-text)'"
                                    onmouseout="this.style.color='var(--lp-muted)'">{{ $link }}</a></li>
                        @endforeach
                    </ul>
                </div>


                <div>
                    <h4
                        style="font-family:var(--font-head);font-size:.8rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--lp-text);margin-bottom:1rem">
                        Company</h4>
                    <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:.6rem">
                        @foreach (['About', 'Blog', 'Careers', 'Press'] as $link)
                            <li><a href="#"
                                    style="font-size:.85rem;color:var(--lp-muted);text-decoration:none;transition:color .2s"
                                    onmouseover="this.style.color='var(--lp-text)'"
                                    onmouseout="this.style.color='var(--lp-muted)'">{{ $link }}</a></li>
                        @endforeach
                    </ul>
                </div>

                <div>
                    <h4
                        style="font-family:var(--font-head);font-size:.8rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--lp-text);margin-bottom:1rem">
                        Support</h4>
                    <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:.6rem">
                        @foreach (['Documentation', 'Help Center', 'API Reference', 'Status'] as $link)
                            <li><a href="#"
                                    style="font-size:.85rem;color:var(--lp-muted);text-decoration:none;transition:color .2s"
                                    onmouseover="this.style.color='var(--lp-text)'"
                                    onmouseout="this.style.color='var(--lp-muted)'">{{ $link }}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div
                style=" border-top:1px solid var(--lp-border);padding-top:1.75rem;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:.75rem">
                <p class="order-2 md:order-1" style="font-size:.8rem;color:var(--lp-muted)">&copy; {{ date('Y') }} {{ $settings['shop_name'] ?? 'SnapPOS' }}. All rights reserved.</p>
                <div style="display:flex;gap:1.5rem order-1 md:order-2">
                    @foreach (['Privacy Policy', 'Terms of Service', 'Cookie Policy'] as $link)
                        <a href="#"
                            style="font-size:.8rem;color:var(--lp-muted);text-decoration:none;transition:color .2s"
                            onmouseover="this.style.color='var(--lp-text)'"
                            onmouseout="this.style.color='var(--lp-muted)'">{{ $link }}</a>
                    @endforeach
                </div>
            </div>
        </div>
        </section>



        <script>
            const navbar = document.getElementById('navbar');
            window.addEventListener('scroll', () => {
                navbar.classList.toggle('scrolled', window.scrollY > 30);
            });


            document.getElementById('burger-btn').addEventListener('click', () => {
                document.getElementById('mobile-menu').classList.toggle('open');
            });


            document.querySelectorAll('a[href^="#"]').forEach(a => {
                a.addEventListener('click', e => {
                    const target = document.querySelector(a.getAttribute('href'));
                    if (target) {
                        e.preventDefault();
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });


            const revealEls = document.querySelectorAll('.reveal');
            const revealObserver = new IntersectionObserver((entries) => {
                entries.forEach((entry, i) => {
                    if (entry.isIntersecting) {
                        setTimeout(() => entry.target.classList.add('visible'), i * 80);
                        revealObserver.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.1
            });
            revealEls.forEach(el => revealObserver.observe(el));
        </script>

        <style>
            .stat-card-1 {
                top: -1.5rem;
                left: -1rem;
            }

            .stat-card-2 {
                bottom: 0rem;
                right: -1rem;
            }

            .grid-features {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 1.25rem;
            }

            .feature-card-row-span-2 {
                grid-row: span 2;
            }

            .feature-card-col-span-2 {
                grid-column: span 2;
                align-items: center;
            }

            .grid-how-it-works {
                display: grid;
                grid-template-columns: 1fr auto 1fr auto 1fr;
                align-items: start;
                gap: 0;
            }

            .step-chevron {
                display: flex;
                align-items: center;
                padding-top: 2rem;
            }

            @media (max-width: 1024px) {
                .grid-features {
                    grid-template-columns: repeat(2, 1fr);
                }

                .feature-card-row-span-2 {
                    grid-row: auto;
                }

                .feature-card-col-span-2 {
                    grid-column: span 2;
                }
            }

            @media (max-width: 768px) {
                .grid-footer {
                    grid-template-columns: 1fr 1fr !important;
                    gap: 2rem !important;
                }

                .stat-card-1, .stat-card-2 {
                    display: none;
                }

                .grid-features {
                    grid-template-columns: 1fr;
                }

                .feature-card-col-span-2 {
                    grid-column: span 1;
                    flex-direction: column !important;
                    align-items: flex-start !important;
                }

                .grid-how-it-works {
                    grid-template-columns: 1fr;
                    gap: 2rem;
                }

                .step-chevron {
                    display: none !important;
                }
            }

            @media (max-width: 480px) {
                .grid-footer {
                    grid-template-columns: 1fr !important;
                }
            }
        </style>

</body>

</html>
