<nav class="sticky top-0 z-50 border-b backdrop-blur-md border-zinc-200 bg-white/90">
    <div class="mx-auto max-w-[1600px] px-8 h-16 flex items-center justify-between">
        <div class="flex items-center gap-8">
            <a href="{{ route('shop.index') }}" class="text-2xl font-bold tracking-tighter text-zinc-900">d4ily.1</a>
            <div class="hidden md:flex items-center gap-6 text-base font-medium text-zinc-600">
                <a href="{{ route('shop.index') }}" class="transition-colors hover:text-zinc-900">Shop</a>
                @auth
                    <a href="{{ route('shop.recommendations') }}" class="transition-colors hover:text-zinc-900 flex items-center gap-1.5">
                        <i data-lucide="sparkles" class="w-4 h-4"></i>
                        For You
                    </a>
                @endauth
            </div>
        </div>
        <div class="flex items-center gap-4">
            <form action="{{ route('shop.index') }}" method="GET" class="relative hidden sm:block">
                <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-zinc-400"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search vintage items..."
                    class="h-10 w-80 rounded-md border pl-10 pr-4 text-sm focus:border-zinc-300 focus:bg-white focus:outline-none focus:ring-0 transition-all placeholder:text-zinc-400 border-zinc-200 bg-zinc-50">
            </form>
            <a href="{{ route('shop.index') }}" class="p-1 text-zinc-600 hover:text-zinc-900">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <a href="{{ route('cart.index') }}" class="relative p-1 text-zinc-600 hover:text-zinc-900">
                <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                <span id="cart-count" class="absolute -top-1 -right-1 h-4 w-4 bg-red-500 text-white text-[10px] rounded-full flex items-center justify-center font-bold">{{ function_exists('getCartCount') ? getCartCount() : (isset($cart) ? $cart->items->sum('quantity') : 0) }}</span>
            </a>
            @auth
                <div class="relative group">
                    <button class="flex items-center gap-2 px-3 py-2 rounded-md hover:bg-zinc-100 transition-colors">
                        <div class="h-8 w-8 rounded-full bg-zinc-900 flex items-center justify-center text-sm font-bold text-white">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <span class="text-sm font-medium text-zinc-900 hidden lg-block">{{ auth()->user()->name }}</span>
                        <i data-lucide="chevron-down" class="w-4 h-4 text-zinc-600"></i>
                    </button>
                    <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-zinc-200 py-1 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all">
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-zinc-700 hover:bg-zinc-50">
                                <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                                Admin Dashboard
                            </a>
                        @endif
                        <a href="{{ route('shop.recommendations') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-zinc-700 hover:bg-zinc-50">
                            <i data-lucide="sparkles" class="w-4 h-4"></i>
                            Recommendations
                        </a>
                        <hr class="my-1 border-zinc-200">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                <i data-lucide="log-out" class="w-4 h-4"></i>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-medium text-zinc-600 hover:text-zinc-900">Login</a>
                <a href="{{ route('register') }}" class="px-4 py-2 text-sm font-medium bg-zinc-900 text-white rounded-md hover:bg-zinc-800">Sign up</a>
            @endauth
        </div>
    </div>
</nav>