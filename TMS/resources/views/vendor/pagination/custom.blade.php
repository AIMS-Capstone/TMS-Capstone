<div class="flex justify-between items-center mt-4">
    {{-- Left Side: Pagination --}}
    <ul class="flex space-x-1">
        {{-- Previous Page Link --}}
        <li class="{{ $paginator->onFirstPage() ? 'opacity-50 cursor-not-allowed' : '' }}">
            @if ($paginator->onFirstPage())
                <span class="inline-flex items-center text-xs justify-center w-6 h-6 rounded-full text-gray-600">&lt;</span>
            @else
                <a class="inline-flex items-center justify-center w-6 h-6 text-gray-800 font-bold rounded-full hover:bg-gray-300 text-xs" href="{{ $paginator->previousPageUrl() }}" rel="prev">&lt;</a>
            @endif
        </li>

        {{-- Pagination Elements --}}
        @php
            $currentPage = $paginator->currentPage();
            $lastPage = $paginator->lastPage();
            $maxPagesToShow = 4;
        @endphp

        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <li>
                    <span class="inline-flex items-center justify-center w-6 h-6 text-gray-600 text-xs">...</span>
                </li>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $currentPage)
                        {{-- Current Page --}}
                        <li>
                            <span class="inline-flex items-center text-xs justify-center w-6 h-6 bg-blue-900 text-white rounded-full">{{ $page }}</span>
                        </li>
                    @elseif (
                        $page == 1 || 
                        $page == $lastPage || 
                        ($page >= $currentPage - 1 && $page <= $currentPage + 1) || 
                        $page <= $maxPagesToShow
                    )
                        {{-- Display the first few pages, last page, or pages close to the current page --}}
                        <li>
                            <a class="inline-flex items-center justify-center w-6 h-6 text-gray-800 rounded-full hover:bg-gray-300 text-xs" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @elseif ($page == $maxPagesToShow + 1 && $currentPage < $lastPage - $maxPagesToShow)
                        {{-- Show Ellipsis after the first few pages if not close to the last page --}}
                        <li>
                            <span class="inline-flex items-center justify-center w-6 h-6 text-gray-600 text-xs">...</span>
                        </li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        <li class="{{ $paginator->hasMorePages() ? '' : 'opacity-50 cursor-not-allowed' }}">
            @if ($paginator->hasMorePages())
                <a class="inline-flex items-center justify-center w-6 h-6 text-gray-800 rounded-full font-bold hover:bg-gray-300 text-xs" href="{{ $paginator->nextPageUrl() }}" rel="next">&gt;</a>
            @else
                <span class="inline-flex items-center justify-center w-6 h-6 text-gray-600 rounded-full text-xs">&gt;</span>
            @endif
        </li>
    </ul>

    {{-- Right Side: Showing Entries --}}
    <div class="text-gray-600 text-sm">
        Showing {{ $paginator->firstItem() }}-{{ $paginator->lastItem() }} of {{ $paginator->total() }} Entries
    </div>
</div>
