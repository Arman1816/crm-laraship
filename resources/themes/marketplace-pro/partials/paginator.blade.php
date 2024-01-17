@if ($paginator->hasPages())
    <div class="pagination">
        <div class="js-product-list-top ">
            <div class="d-flex justify-content-around row">
                <div class="showing col col-xs-12">
                </div>
                <div class="page-list col col-xs-12">
                    <ul>
                        <li>
                            @if (!$paginator->onFirstPage())
                                <a rel="prev" href="{{ $paginator->previousPageUrl() }}"
                                   class="previous disabled js-search-link">
                                    @lang('corals-marketplace-pro::labels.partial.previous')
                                </a>
                            @endif
                        </li>

                        @foreach($elements as $element)
                            @if (is_string($element))
                                <li>{{ $element }}</li>
                            @endif
                            @if (is_array($element))
                                @foreach ($element as $page => $url)
                                    @if ($page == $paginator->currentPage())
                                        <li class="current active"><a href="#" class="d-block">{{ $page }}</a></li>
                                    @else
                                        <li><a href="{{ $url }}" class="d-block">{{ $page }}</a></li>
                                    @endif
                                @endforeach
                            @endif
                        @endforeach
                        <li>
                            @if ($paginator->hasMorePages())
                                <a rel="next" href="{{ $paginator->nextPageUrl() }}"
                                   class="next disabled js-search-link d-block">
                                    @lang('corals-marketplace-pro::labels.partial.next')
                                </a>
                            @endif
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endif