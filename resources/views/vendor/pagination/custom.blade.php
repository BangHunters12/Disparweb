@if ($paginator->hasPages())
    <nav class="flex items-center justify-between gap-4">
        <p class="text-sm text-gray-500">
            Menampilkan {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} dari {{ $paginator->total() }} data
        </p>
        <div class="flex items-center gap-1">
            {{-- Previous --}}
            @if ($paginator->onFirstPage())
                <span class="px-3 py-1.5 rounded-lg text-gray-600 text-sm cursor-not-allowed">‹</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="px-3 py-1.5 rounded-lg text-gray-400 hover:text-white hover:bg-dark-700 text-sm transition-colors">‹</a>
            @endif

            {{-- Pages --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="px-3 py-1.5 text-gray-600 text-sm">…</span>
                @endif
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="px-3 py-1.5 rounded-lg bg-amber-500 text-dark-900 font-bold text-sm">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="px-3 py-1.5 rounded-lg text-gray-400 hover:text-white hover:bg-dark-700 text-sm transition-colors">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="px-3 py-1.5 rounded-lg text-gray-400 hover:text-white hover:bg-dark-700 text-sm transition-colors">›</a>
            @else
                <span class="px-3 py-1.5 rounded-lg text-gray-600 text-sm cursor-not-allowed">›</span>
            @endif
        </div>
    </nav>
@endif
