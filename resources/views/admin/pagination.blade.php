@if ($paginator->hasPages())
    <nav class="pagination" role="navigation" aria-label="Pagination Navigation">
        @if ($paginator->onFirstPage())
            <span class="disabled" aria-disabled="true" aria-label="Previous">&lsaquo;</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Previous">&lsaquo;</a>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="disabled" aria-disabled="true">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="active" aria-current="page">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Next">&rsaquo;</a>
        @else
            <span class="disabled" aria-disabled="true" aria-label="Next">&rsaquo;</span>
        @endif
    </nav>
@endif

