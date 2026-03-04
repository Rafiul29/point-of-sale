<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | POS</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-slate-100 antialiased">

    <div class="flex min-h-screen items-center justify-center">
        <div class="relative mx-6 w-full max-w-5xl rounded-3xl shadow-2xl overflow-hidden">
            <div class="grid grid-cols-1 lg:grid-cols-2">
                <!-- Left: Form Card -->
                <div class="flex items-center justify-center bg-white p-10 lg:p-14">
                    <div class="w-full max-w-md">
                        <div class="flex items-center gap-3">
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-600 shadow-md shadow-indigo-600/20">
                                <i class="fas fa-bolt text-white text-xl"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-extrabold text-slate-900">Welcome Back</h2>
                                <p class="text-sm text-slate-500">Sign in to your POS account to continue.</p>
                            </div>
                        </div>

                        @if (session('status'))
                            <div class="mt-6 rounded-md bg-emerald-50 p-3 text-emerald-700 text-sm">{{ session('status') }}</div>
                        @endif

                        <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-6" novalidate>
                            @csrf

                            <div>
                                <label for="email" class="block text-xs font-semibold text-slate-500">Email address</label>
                                <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus placeholder="name@company.com" class="mt-2 block w-full rounded-xl bg-slate-50 border border-slate-100 py-3 px-4 text-sm text-slate-700 placeholder:text-slate-350 focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('email') border-rose-500 @enderror">
                                @error('email') <p class="mt-2 text-xs text-rose-500">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="password" class="block text-xs font-semibold text-slate-500">Password</label>
                                <input id="password" name="password" type="password" required placeholder="••••••••" class="mt-2 block w-full rounded-xl bg-slate-50 border border-slate-100 py-3 px-4 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('password') border-rose-500 @enderror">
                                @error('password') <p class="mt-2 text-xs text-rose-500">{{ $message }}</p> @enderror
                            </div>

                            <div class="flex items-center justify-between">
                                <label class="inline-flex items-center text-sm text-slate-600">
                                    <input id="remember" name="remember" type="checkbox" class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                    <span class="ml-2">Remember me</span>
                                </label>

                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="text-sm font-semibold text-indigo-600 hover:underline">Forgot your password?</a>
                                @endif
                            </div>

                            <div>
                                <button type="submit" class="flex w-full items-center justify-center gap-3 rounded-xl bg-indigo-600 py-3 px-4 text-sm font-bold text-white shadow hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    <i class="fas fa-sign-in-alt opacity-90"></i>
                                    Sign in
                                </button>
                            </div>
                        </form>

                        <div class="mt-6 rounded-xl bg-slate-50 p-4 border border-slate-100">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-info-circle text-indigo-600"></i>
                                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Demo credentials</p>
                            </div>
                            <div class="mt-3 text-sm text-slate-600">
                                <p>Admin: <span class="text-indigo-600 font-semibold">admin@example.com</span></p>
                                <p>Password: <span class="text-indigo-600 font-semibold">password</span></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Visual / Marketing -->
                <div class="hidden lg:block relative bg-gray-800">
                    <img class="absolute inset-0 h-full w-full object-cover opacity-90" src="https://images.unsplash.com/photo-1556742049-2996d9333989?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80" alt="POS System">
                    <div class="absolute inset-0 bg-gradient-to-tr from-indigo-900/85 to-black/50"></div>
                    <div class="absolute inset-0 flex flex-col justify-center p-12 text-white">
                        <h3 class="text-4xl font-extrabold">Lightning fast <span class="text-indigo-300">Digital Retail</span></h3>
                        <p class="mt-4 max-w-md text-lg text-slate-200">Real-time inventory, easy checkout, and powerful reporting for modern stores.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
