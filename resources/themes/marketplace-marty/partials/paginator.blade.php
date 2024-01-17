@if ($paginator->hasPages())
    <nav class="navigation pagination" role="navigation">
        <div class="nav-links">
            {{-- Previous Page Link --}}
            @if (!$paginator->onFirstPage())
                <a class="prev page-numbers" href="{{ $paginator->previousPageUrl() }}">
                    <span class="lnr lnr-arrow-left"></span>
                </a>
            @endif
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <a class="page-numbers current" href="#">{{ $element }}</a>
                @endif
                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <a class="page-numbers current" href="#">{{$page}}</a>
                        @else
                            <a class="page-numbers" href="#">{{$page}}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach
            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a class="next page-numbers" href="{{ $paginator->nextPageUrl() }}">
                    <span class="lnr lnr-arrow-right"></span>
                </a>
            @endif
        </div>
    </nav>
@endif
