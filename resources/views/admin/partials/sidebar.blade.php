<aside class="w-64 bg-white border-r border-zinc-200 flex flex-col">
    <div class="h-16 flex items-center px-6 border-b border-zinc-100">
        <div class="h-6 w-6 rounded-md bg-zinc-900 flex items-center justify-center mr-3">
            <span class="text-xs font-bold text-white">d1</span>
        </div>
        <span class="text-base font-semibold">Admin Console</span>
    </div>

    <nav class="flex-1 p-4 space-y-1">
        <p class="px-2 text-xs font-medium uppercase tracking-wider text-zinc-400 mb-2">Platform</p>
        <a href="{{ route('admin.dashboard') }}"
            class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-md {{ $active === 'dashboard' ? 'bg-zinc-100 text-zinc-900' : 'text-zinc-600 hover:bg-zinc-50' }}">
            <i data-lucide="bar-chart-3" class="w-4 h-4"></i>
            Dashboard
        </a>
        <a href="{{ route('admin.inventory') }}"
            class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-md {{ $active === 'inventory' ? 'bg-zinc-100 text-zinc-900' : 'text-zinc-600 hover:bg-zinc-50' }}">
            <i data-lucide="package" class="w-4 h-4"></i>
            Inventory
        </a>
        <a href="{{ route('admin.orders') }}"
            class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-md {{ $active === 'orders' ? 'bg-zinc-100 text-zinc-900' : 'text-zinc-600 hover:bg-zinc-50' }}">
            <i data-lucide="shopping-cart" class="w-4 h-4"></i>
            Orders
        </a>
        <a href="{{ route('admin.customers') }}"
            class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-md {{ $active === 'customers' ? 'bg-zinc-100 text-zinc-900' : 'text-zinc-600 hover:bg-zinc-50' }}">
            <i data-lucide="users" class="w-4 h-4"></i>
            Customers
        </a>

        <p class="px-2 text-xs font-medium uppercase tracking-wider text-zinc-400 mb-2 mt-6">System</p>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit"
                class="w-full flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-md text-zinc-600 hover:bg-zinc-50">
                <i data-lucide="log-out" class="w-4 h-4"></i>
                Logout
            </button>
        </form>
    </nav>

    <div class="p-4 border-t border-zinc-100">
        <div class="flex items-center gap-3">
            <div
                class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center text-xs font-bold text-indigo-700">
                {{ substr(auth()->user()->name, 0, 1) }}
            </div>
            <div>
                <p class="text-sm font-medium text-zinc-900">{{ auth()->user()->name }}</p>
                <p class="text-xs text-zinc-500">Administrator</p>
            </div>
        </div>
    </div>
</aside>