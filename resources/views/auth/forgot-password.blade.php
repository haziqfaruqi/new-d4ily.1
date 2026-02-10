<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - D4ily.1</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-gradient-to-br from-zinc-50 to-zinc-100 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md">
        <!-- Logo -->
        <div class="text-center mb-8">
            <a href="{{ route('shop.index') }}" class="inline-block">
                <img src="{{ asset('logo/logo.png') }}" alt="d4ily.1" class="h-24 w-auto mx-auto mb-4">
                <p class="text-sm font-semibold text-zinc-600 mt-2">Vintage Thrift Shop</p>
            </a>
        </div>

        <!-- Forgot Password Card -->
        <div class="bg-white rounded-2xl shadow-xl border border-zinc-200 p-8">
            <div class="mb-6 text-center">
                <div class="w-12 h-12 bg-zinc-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="key" class="w-6 h-6 text-zinc-700"></i>
                </div>
                <h2 class="text-xl font-bold text-zinc-900">Forgot your password?</h2>
                <p class="text-sm text-zinc-600 mt-2">No problem. Enter your email address and we'll send you a link to reset your password.</p>
            </div>

            @if(session('success'))
                <div class="mb-6 p-4 rounded-lg bg-emerald-50 border border-emerald-200">
                    <div class="flex items-center gap-2">
                        <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600"></i>
                        <p class="text-sm text-emerald-800">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 p-4 rounded-lg bg-red-50 border border-red-200">
                    <div class="flex items-center gap-2">
                        <i data-lucide="alert-circle" class="w-4 h-4 text-red-600"></i>
                        <p class="text-sm text-red-800">{{ $errors->first() }}</p>
                    </div>
                </div>
            @endif

            <form action="{{ route('password.email') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-zinc-700 mb-2">Email address</label>
                    <div class="relative">
                        <i data-lucide="mail"
                            class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-zinc-400"></i>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required
                            class="w-full pl-10 pr-4 py-2.5 border border-zinc-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-zinc-900 focus:border-transparent transition-all"
                            placeholder="you@example.com">
                    </div>
                    @error('email')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                    class="w-full py-3 bg-zinc-900 text-white rounded-lg font-medium hover:bg-zinc-800 transition-colors flex items-center justify-center gap-2">
                    <span>Send Password Reset Link</span>
                    <i data-lucide="mail" class="w-4 h-4"></i>
                </button>
            </form>

            <div class="mt-6 text-center">
                <a href="{{ route('login') }}" class="text-sm font-medium text-zinc-900 hover:text-zinc-700 inline-flex items-center gap-1">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    Back to login
                </a>
            </div>
        </div>

        <p class="text-center text-xs text-zinc-500 mt-6">
            © 2025 d4ily.1. All rights reserved.
        </p>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>

</html>
