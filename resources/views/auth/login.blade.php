<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Antigravity POS</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="h-full antialiased">

    <div class="flex min-h-full">
        <!-- Left Side: Login Form -->
        <div class="flex flex-1 flex-col justify-center px-4 py-12 sm:px-6 lg:flex-none lg:px-20 xl:px-24 bg-white shadow-2xl z-10 transition-all duration-700">
            <div class="mx-auto w-full max-w-sm lg:w-96">
                <div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-600 shadow-xl shadow-indigo-600/30">
                        <i class="fas fa-bolt text-white text-xl"></i>
                    </div>
                    <h2 class="mt-8 text-3xl font-extrabold tracking-tight text-slate-900">Welcome Back</h2>
                    <p class="mt-2 text-sm font-medium text-slate-400">Please enter your credentials to access the POS terminal.</p>
                </div>

                <div class="mt-10">
                    <form method="POST" action="{{ route('login') }}" class="space-y-6">
                        @csrf
                        
                        <div>
                            <label for="email" class="block text-xs font-extrabold uppercase tracking-widest text-slate-400">Email Address</label>
                            <div class="mt-2">
                                <input type="email" id="email" name="email" class="block w-full border-0 bg-slate-100 py-4 px-5 text-sm font-semibold rounded-2xl focus:ring-2 focus:ring-indigo-600 placeholder:text-slate-300 @error('email') ring-2 ring-rose-500 @enderror" value="{{ old('email') }}" required autofocus placeholder="name@company.com">
                            </div>
                            @error('email')
                                <p class="mt-2 text-xs font-bold text-rose-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password" class="block text-xs font-extrabold uppercase tracking-widest text-slate-400">Password Access</label>
                            <div class="mt-2">
                                <input type="password" id="password" name="password" class="block w-full border-0 bg-slate-100 py-4 px-5 text-sm font-semibold rounded-2xl focus:ring-2 focus:ring-indigo-600 placeholder:text-slate-300 @error('password') ring-2 ring-rose-500 @enderror" required placeholder="••••••••">
                            </div>
                            @error('password')
                                <p class="mt-2 text-xs font-bold text-rose-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input type="checkbox" id="remember" name="remember" class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-600">
                                <label for="remember" class="ml-3 block text-sm font-semibold text-slate-500">Remember session</label>
                            </div>
                        </div>

                        <div>
                            <button type="submit" class="flex w-full justify-center items-center gap-3 rounded-2xl bg-indigo-600 py-4 px-4 text-sm font-extrabold text-white shadow-xl shadow-indigo-600/30 transition-all hover:bg-indigo-700 hover:scale-[1.02] active:scale-[0.98]">
                                <i class="fas fa-sign-in-alt opacity-70"></i>
                                Open Workstation
                            </button>
                        </div>
                    </form>

                    <div class="mt-10 rounded-2xl bg-slate-50 p-6 border border-slate-100">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-info-circle text-indigo-600"></i>
                            <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Demo Environment</p>
                        </div>
                        <div class="mt-3 space-y-1">
                            <p class="text-xs font-bold text-slate-600">Admin: <span class="text-indigo-600">admin@example.com</span></p>
                            <p class="text-xs font-bold text-slate-600">Access: <span class="text-indigo-600">password</span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side: Decorative -->
        <div class="relative hidden w-0 flex-1 lg:block">
            <img class="absolute inset-0 h-full w-full object-cover" src="https://images.unsplash.com/photo-1556742049-2996d9333989?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80" alt="POS System">
            <div class="absolute inset-0 bg-gradient-to-tr from-indigo-900/90 to-black/40 backdrop-blur-[2px]"></div>
            <div class="absolute inset-0 flex flex-col justify-end p-20 text-white">
                <div class="max-w-xl">
                    <h1 class="text-6xl font-extrabold tracking-tight leading-tight">Lightning fast <br><span class="text-indigo-400">Digital Retail</span></h1>
                    <p class="mt-6 text-xl font-medium text-slate-300">Experience the future of point-of-sale management with Antigravity's real-time inventory and lightning terminal.</p>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
