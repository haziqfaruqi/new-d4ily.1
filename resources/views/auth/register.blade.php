<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - D4ily.1</title>
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
                <div class="h-12 w-12 rounded-xl bg-zinc-900 flex items-center justify-center mx-auto mb-3">
                    <span class="text-xl font-bold text-white">d1</span>
                </div>
                <h1 class="text-2xl font-bold text-zinc-900">d4ily.1</h1>
                <p class="text-sm text-zinc-600 mt-1">Vintage Thrift Shop</p>
            </a>
        </div>

        <!-- Register Card -->
        <div class="bg-white rounded-2xl shadow-xl border border-zinc-200 p-8">
            <div class="mb-6">
                <h2 class="text-xl font-bold text-zinc-900">Create an account</h2>
                <p class="text-sm text-zinc-600 mt-1">Join us to discover unique vintage finds</p>
            </div>

            @if(session('error'))
                <div class="mb-6 p-4 rounded-lg bg-red-50 border border-red-200">
                    <div class="flex items-center gap-2">
                        <i data-lucide="alert-circle" class="w-4 h-4 text-red-600"></i>
                        <p class="text-sm text-red-800">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            <form action="{{ route('register.submit') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-medium text-zinc-700 mb-2">Full name</label>
                    <div class="relative">
                        <i data-lucide="user"
                            class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-zinc-400"></i>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required
                            class="w-full pl-10 pr-4 py-2.5 border border-zinc-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-zinc-900 focus:border-transparent transition-all"
                            placeholder="John Doe">
                    </div>
                    @error('name')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

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

                <div>
                    <label for="password" class="block text-sm font-medium text-zinc-700 mb-2">Password</label>
                    <div class="relative">
                        <i data-lucide="lock"
                            class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-zinc-400"></i>
                        <input type="password" id="password" name="password" required
                            class="w-full pl-10 pr-4 py-2.5 border border-zinc-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-zinc-900 focus:border-transparent transition-all"
                            placeholder="••••••••">
                    </div>
                    @error('password')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-zinc-500">Must be at least 8 characters</p>
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-zinc-700 mb-2">Confirm
                        password</label>
                    <div class="relative">
                        <i data-lucide="lock"
                            class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-zinc-400"></i>
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                            class="w-full pl-10 pr-4 py-2.5 border border-zinc-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-zinc-900 focus:border-transparent transition-all"
                            placeholder="••••••••">
                    </div>
                </div>

                <div class="flex items-start gap-2">
                    <input type="checkbox" id="terms" name="terms" required
                        class="mt-1 rounded border-zinc-300 text-zinc-900 focus:ring-zinc-900">
                    <label for="terms" class="text-sm text-zinc-600">
                        I agree to the <a href="#" class="font-medium text-zinc-900 hover:text-zinc-700">Terms of
                            Service</a> and <a href="#" class="font-medium text-zinc-900 hover:text-zinc-700">Privacy
                            Policy</a>
                    </label>
                </div>

                <button type="submit"
                    class="w-full py-3 bg-zinc-900 text-white rounded-lg font-medium hover:bg-zinc-800 transition-colors flex items-center justify-center gap-2">
                    <span>Create account</span>
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </button>
            </form>

            <div class="mt-6 pt-6 border-t border-zinc-200">
                <p class="text-center text-sm text-zinc-600">
                    Already have an account?
                    <a href="{{ route('login') }}" class="font-medium text-zinc-900 hover:text-zinc-700">Sign in</a>
                </p>
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