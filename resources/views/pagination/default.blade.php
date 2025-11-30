@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}">
        <ul class="flex items-center gap-1 sm:gap-2 flex-nowrap overflow-x-auto overflow-y-hidden no-scrollbar py-1 px-2 min-w-max">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li>
                    <span class="px-3 py-2 text-sm text-zinc-400 bg-white border border-zinc-200 rounded-lg cursor-not-allowed">
                        <i data-lucide="chevron-left" class="w-4 h-4"></i>
                    </span>
                </li>
            @else
                <li>
                    <a href="{{ $paginator->previousPageUrl() }}" class="px-3 py-2 text-sm text-zinc-700 bg-white border border-zinc-200 rounded-lg hover:bg-zinc-50 transition-colors" rel="prev">
                        <i data-lucide="chevron-left" class="w-4 h-4"></i>
                    </a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li>
                        <span class="px-3 py-2 text-sm text-zinc-400">{{ $element }}</span>
                    </li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li>
                                <span class="px-3 py-2 text-sm font-semibold text-white bg-indigo-600 border border-indigo-600 rounded-lg">
                                    {{ $page }}
                                </span>
                            </li>
                        @else
                            <li>
                                <a href="{{ $url }}" class="px-3 py-2 text-sm text-zinc-700 bg-white border border-zinc-200 rounded-lg hover:bg-zinc-50 transition-colors">
                                    {{ $page }}
                                </a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li>
                    <a href="{{ $paginator->nextPageUrl() }}" class="px-3 py-2 text-sm text-zinc-700 bg-white border border-zinc-200 rounded-lg hover:bg-zinc-50 transition-colors" rel="next">
                        <i data-lucide="chevron-right" class="w-4 h-4"></i>
                    </a>
                </li>
            @else
                <li>
                    <span class="px-3 py-2 text-sm text-zinc-400 bg-white border border-zinc-200 rounded-lg cursor-not-allowed">
                        <i data-lucide="chevron-right" class="w-4 h-4"></i>
                    </span>
                </li>
            @endif
        </ul>
    </nav>
@endif