<aside class="w-64 flex flex-col" style="background: linear-gradient(180deg, #a6af89 0%, #d5fdff 100%);">
    <!-- Logo Section -->
    <div class="h-20 flex items-center px-6 border-b border-white/20">
        <img src="{{ asset('logo/logo.png') }}" alt="d4ily.1" class="h-10 w-auto mr-3">
        <div>
            <span class="text-base font-bold text-stone-800">Admin Console</span>
            <p class="text-xs text-stone-600">D4ily.1 Thrift Shop</p>
        </div>
    </div>

    <nav class="flex-1 p-4 space-y-2">
        <p class="px-3 text-xs font-bold uppercase tracking-wider text-stone-700/70 mb-3 flex items-center gap-2">
            <i data-lucide="layout-grid" class="w-3 h-3"></i>
            Platform
        </p>
        <a href="{{ route('admin.dashboard') }}"
            class="flex items-center gap-3 px-4 py-3 text-sm font-semibold rounded-xl transition-all {{ $active === 'dashboard' ? 'bg-white shadow-lg text-stone-800' : 'text-stone-800/80 hover:bg-white/40 hover:shadow-md' }}">
            <i data-lucide="bar-chart-3" class="w-5 h-5 {{ $active === 'dashboard' ? 'text-[#c53131]' : '' }}"></i>
            Dashboard
            @if($active === 'dashboard')
                <div class="ml-auto w-2 h-2 rounded-full" style="background: #c53131;"></div>
            @endif
        </a>
        <a href="{{ route('admin.inventory') }}"
            class="flex items-center gap-3 px-4 py-3 text-sm font-semibold rounded-xl transition-all {{ $active === 'inventory' ? 'bg-white shadow-lg text-stone-800' : 'text-stone-800/80 hover:bg-white/40 hover:shadow-md' }}">
            <i data-lucide="package" class="w-5 h-5 {{ $active === 'inventory' ? 'text-[#c53131]' : '' }}"></i>
            Inventory
            @if($active === 'inventory')
                <div class="ml-auto w-2 h-2 rounded-full" style="background: #c53131;"></div>
            @endif
        </a>
        <a href="{{ route('admin.orders') }}"
            class="flex items-center gap-3 px-4 py-3 text-sm font-semibold rounded-xl transition-all {{ $active === 'orders' ? 'bg-white shadow-lg text-stone-800' : 'text-stone-800/80 hover:bg-white/40 hover:shadow-md' }}">
            <i data-lucide="shopping-bag" class="w-5 h-5 {{ $active === 'orders' ? 'text-[#c53131]' : '' }}"></i>
            Orders
            @if($active === 'orders')
                <div class="ml-auto w-2 h-2 rounded-full" style="background: #c53131;"></div>
            @endif
        </a>
        <a href="{{ route('admin.customers') }}"
            class="flex items-center gap-3 px-4 py-3 text-sm font-semibold rounded-xl transition-all {{ $active === 'customers' ? 'bg-white shadow-lg text-stone-800' : 'text-stone-800/80 hover:bg-white/40 hover:shadow-md' }}">
            <i data-lucide="users" class="w-5 h-5 {{ $active === 'customers' ? 'text-[#c53131]' : '' }}"></i>
            Customers
            @if($active === 'customers')
                <div class="ml-auto w-2 h-2 rounded-full" style="background: #c53131;"></div>
            @endif
        </a>

        <p class="px-3 text-xs font-bold uppercase tracking-wider text-stone-700/70 mb-3 mt-8 flex items-center gap-2">
            <i data-lucide="settings" class="w-3 h-3"></i>
            System
        </p>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit"
                class="w-full flex items-center gap-3 px-4 py-3 text-sm font-semibold rounded-xl text-stone-800/80 hover:bg-white/40 hover:shadow-md transition-all">
                <i data-lucide="log-out" class="w-5 h-5"></i>
                Logout
            </button>
        </form>
    </nav>

    <!-- User Profile -->
    <div class="p-4 border-t border-white/20">
        <div class="bg-white/40 backdrop-blur rounded-2xl p-4 shadow-lg">
            <div class="flex items-center gap-3">
                <div
                    class="h-12 w-12 rounded-xl flex items-center justify-center text-sm font-bold text-white shadow-lg"
                    style="background: linear-gradient(135deg, #c53131 0%, #D65A48 100%);">
                    {{ substr(auth()->user()?->name, 0, 1) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-stone-800 truncate">{{ auth()->user()?->name }}</p>
                    <p class="text-xs text-stone-600">Administrator</p>
                </div>
                <i data-lucide="chevron-right" class="w-4 h-4 text-stone-600"></i>
            </div>
        </div>
    </div>
</aside>