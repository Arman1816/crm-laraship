@if(!isset($home) || !$home)
    <!-- Page Title-->
    <nav class="breadcrumb-bg">
        <div class="container">
            <div class="container no-index">
                <div class="breadcrumb">
                    <ol>
                        @if(isset($featured_image))
                            <img src="{{ $featured_image }}" alt="{{ $item->title }}" width="100%"
                                 style="max-height: 400px;"/>
                        @elseif(isset($content))
                            <li><a href="#">{!! $content !!}</a></li>
                        @else
                            <li><a href="#">{!! optional($item)->title  !!}</a> </li>
                        @endif

                    </ol>
                </div>
            </div>
        </div>
    </nav>
@endif