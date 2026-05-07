@if ($paginator->hasPages())
<nav class="flex items-center justify-center gap-1">
    {{-- Previous --}}
    @if ($paginator->onFirstPage())
        <span class="px-3 py-2 rounded-xl text-sm text-gray-600 bg-[#1a1f2e] border border-[#2d3548] cursor-not-allowed">←</span>
    @else
        <a href="{{ $paginator->previousPageUrl() }}" class="px-3 py-2 rounded-xl text-sm text-gray-400 bg-[#1a1f2e] border border-[#2d3548] hover:border-amber-500/50 hover:text-white transition-all">←</a>
    @endif

    {{-- Pages --}}
    @foreach ($elements as $element)
        @if (is_string($element))
            <span class="px-3 py-2 rounded-xl text-sm text-gray-600">{{ $element }}</span>
        @endif
        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                    <span class="px-3 py-2 rounded-xl text-sm font-bold text-[#0f1117] bg-amber-500">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" class="px-3 py-2 rounded-xl text-sm text-gray-400 bg-[#1a1f2e] border border-[#2d3548] hover:border-amber-500/50 hover:text-white transition-all">{{ $page }}</a>
                @endif
            @endforeach
        @endif
    @endforeach

    {{-- Next --}}
    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" class="px-3 py-2 rounded-xl text-sm text-gray-400 bg-[#1a1f2e] border border-[#2d3548] hover:border-amber-500/50 hover:text-white transition-all">→</a>
    @else
        <span class="px-3 py-2 rounded-xl text-sm text-gray-600 bg-[#1a1f2e] border border-[#2d3548] cursor-not-allowed">→</span>
    @endif
</nav>
@endif
