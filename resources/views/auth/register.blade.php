<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - D4ily.1</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .hero-pattern {
            background-color: #CBBFA2;
            background-image: repeating-linear-gradient(
                90deg,
                #a6af89 0px,
                #a6af89 40px,
                #d5fdff 40px,
                #d5fdff 100px
            );
        }

        .floating {
            animation: floating 3s ease-in-out infinite;
        }

        @keyframes floating {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        .gradient-border {
            position: relative;
            background: white;
            border-radius: 1.5rem;
        }

        .gradient-border::before {
            content: '';
            position: absolute;
            inset: -2px;
            background: linear-gradient(135deg, #a6af89 0%, #d5fdff 50%, #c53131 100%);
            border-radius: 1.6rem;
            z-index: -1;
        }

        .btn-primary {
            background-color: #292524;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #D65A48;
        }
    </style>
</head>

<body class="min-h-screen flex flex-col">
    <!-- Navigation -->
    <nav class="sticky top-0 z-50 border-b backdrop-blur-md border-stone-200 bg-white/90">
        <div class="mx-auto max-w-[1600px] px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-8">
                <a href="{{ route('landing') }}" class="flex items-center gap-2">
                    <img src="{{ asset('logo/logo.png') }}" alt="d4ily.1" class="h-16 w-auto">
                </a>
                <div class="hidden md:flex items-center gap-6 text-base font-medium text-stone-600">
                    <a href="{{ route('landing') }}" class="transition-colors hover:text-stone-900">Home</a>
                    <a href="{{ route('shop.index') }}" class="transition-colors hover:text-stone-900">Shop</a>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('shop.index') }}" class="p-1 text-stone-600 hover:text-stone-900">
                    <i data-lucide="arrow-left" class="w-5 h-5"></i>
                </a>
                <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-medium bg-stone-100 text-stone-700 rounded-md hover:bg-stone-200 transition-colors">
                    Sign in
                </a>
            </div>
        </div>
    </nav>

    <div class="flex-1 flex">
    <!-- Left Side - Landing Page Pattern -->
    <div class="hidden lg:flex lg:w-1/2 hero-pattern relative overflow-hidden border-t-[12px] border-dashed border-[#c53131]">
        <!-- Decorative Elements -->
        <div class="absolute top-20 left-20 w-32 h-32 bg-[#a6af89]/40 rounded-full blur-3xl floating"></div>
        <div class="absolute bottom-32 right-32 w-40 h-40 bg-[#d5fdff]/40 rounded-full blur-3xl floating" style="animation-delay: 1s;"></div>
        <div class="absolute top-1/2 left-1/3 w-24 h-24 bg-[#c53131]/30 rounded-full blur-3xl floating" style="animation-delay: 2s;"></div>
        <div class="absolute bottom-20 left-1/4 w-28 h-28 bg-[#CBBFA2]/50 rounded-full blur-3xl floating" style="animation-delay: 0.5s;"></div>

        <!-- Noise overlay -->
        <div class="absolute inset-0 opacity-20 mix-blend-multiply"
            style="background-image: url('https://grainy-gradients.vercel.app/noise.svg');">
        </div>

        <!-- Content -->
        <div class="relative z-10 flex flex-col justify-center items-center w-full h-full p-12 text-stone-800">
            <img src="{{ asset('logo/logo.png') }}" alt="D4ily.1" class="h-32 w-auto mb-8 floating">
            <h1 class="text-5xl font-extrabold mb-4">Join Us Today</h1>
            <p class="text-xl text-stone-700 max-w-md text-center">Create an account and start your sustainable fashion journey with D4ily.1 Thrift Shop</p>

            <div class="mt-12 grid grid-cols-3 gap-6">
                <div class="text-center">
                    <div class="w-16 h-16 bg-white/30 backdrop-blur rounded-xl flex items-center justify-center mx-auto mb-3 border-2 border-[#a6af89]">
                        <i data-lucide="shopping-bag" class="w-8 h-8 text-stone-800"></i>
                    </div>
                    <p class="text-sm font-semibold text-stone-800">Shop Unique</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-white/30 backdrop-blur rounded-xl flex items-center justify-center mx-auto mb-3 border-2 border-[#d5fdff]">
                        <i data-lucide="leaf" class="w-8 h-8 text-stone-800"></i>
                    </div>
                    <p class="text-sm font-semibold text-stone-800">Eco-Friendly</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-white/30 backdrop-blur rounded-xl flex items-center justify-center mx-auto mb-3 border-2 border-[#c53131]">
                        <i data-lucide="users" class="w-8 h-8 text-stone-800"></i>
                    </div>
                    <p class="text-sm font-semibold text-stone-800">Community</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Side - Register Form -->
    <div class="w-full lg:w-1/2 flex items-center justify-center p-8" style="background-color: #fafaf9;">
        <div class="w-full max-w-md">
            <!-- Mobile Logo -->
            <div class="lg:hidden text-center mb-8">
                <img src="{{ asset('logo/logo.png') }}" alt="D4ily.1" class="h-16 w-auto mx-auto mb-4">
                <h1 class="text-3xl font-bold" style="color: #292524;">D4ily.1</h1>
                <p class="text-sm text-zinc-600 mt-1">Thrift Shop</p>
            </div>

            <!-- Register Card -->
            <div class="gradient-border shadow-2xl p-8">
                <div class="mb-8">
                    <h2 class="text-2xl font-bold" style="color: #292524;">Create Account</h2>
                    <p class="text-sm text-zinc-600 mt-2">Join us to discover unique vintage finds</p>
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
                            <i data-lucide="user" class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-zinc-400"></i>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required
                                class="w-full pl-11 pr-4 py-3 border-2 border-zinc-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-stone-800 focus:border-stone-800 transition-all"
                                placeholder="Charles Leclerc">
                        </div>
                        @error('name')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-zinc-700 mb-2">Email address</label>
                        <div class="relative">
                            <i data-lucide="mail" class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-zinc-400"></i>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                class="w-full pl-11 pr-4 py-3 border-2 border-zinc-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-stone-800 focus:border-stone-800 transition-all"
                                placeholder="you@example.com">
                        </div>
                        @error('email')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-zinc-700 mb-2">Password</label>
                        <div class="relative">
                            <i data-lucide="lock" class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-zinc-400"></i>
                            <input type="password" id="password" name="password" required
                                class="w-full pl-11 pr-12 py-3 border-2 border-zinc-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-stone-800 focus:border-stone-800 transition-all"
                                placeholder="••••••••">
                            <button type="button" onclick="togglePassword('password', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-zinc-400 hover:text-zinc-600 transition-colors">
                                <i data-lucide="eye" class="w-5 h-5"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-zinc-500">Must be at least 8 characters</p>
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-zinc-700 mb-2">Confirm password</label>
                        <div class="relative">
                            <i data-lucide="lock" class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-zinc-400"></i>
                            <input type="password" id="password_confirmation" name="password_confirmation" required
                                class="w-full pl-11 pr-12 py-3 border-2 border-zinc-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-stone-800 focus:border-stone-800 transition-all"
                                placeholder="••••••••">
                            <button type="button" onclick="togglePassword('password_confirmation', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-zinc-400 hover:text-zinc-600 transition-colors">
                                <i data-lucide="eye" class="w-5 h-5"></i>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-start gap-2">
                        <input type="checkbox" id="terms" name="terms" required
                            class="mt-1 w-4 h-4 rounded border-zinc-300 text-stone-800 focus:ring-stone-800">
                        <label for="terms" class="text-sm text-zinc-600">
                            I agree to the <a href="#" class="font-medium hover:opacity-80" style="color: #c53131;">Terms of Service</a> and <a href="#" class="font-medium hover:opacity-80" style="color: #c53131;">Privacy Policy</a>
                        </label>
                    </div>

                    <button type="submit"
                        class="w-full py-3.5 btn-primary text-white rounded-xl font-semibold flex items-center justify-center gap-2 shadow-lg">
                        <span>Create account</span>
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </button>
                </form>

                <div class="mt-6 pt-6 border-t border-zinc-200">
                    <p class="text-center text-sm text-zinc-600">
                        Already have an account?
                        <a href="{{ route('login') }}" class="font-semibold hover:opacity-80" style="color: #c53131;">Sign in</a>
                    </p>
                </div>
            </div>

            <p class="text-center text-xs text-zinc-500 mt-8">
                © 2025 D4ily.1 Thrift Shop. All rights reserved.
            </p>
        </div>
    </div>
    </div>

    <script>
        lucide.createIcons();

        function togglePassword(inputId, button) {
            const input = document.getElementById(inputId);
            const icon = button.querySelector('i');

            if (input.type === 'password') {
                input.type = 'text';
                icon.setAttribute('data-lucide', 'eye-off');
            } else {
                input.type = 'password';
                icon.setAttribute('data-lucide', 'eye');
            }

            // Re-create the icon
            lucide.createIcons();
        }
    </script>
</body>

</html>
