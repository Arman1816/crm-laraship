@php $categories = \Shop::getFeaturedCategories(); @endphp
@if(!$categories->isEmpty())

        <!-- banner -->
        <div class="section spacing-10 group-image-special col-lg-12 col-xs-12">
            @php $i=0;@endphp
            @foreach($categories as $category)
                @if($i==0)
                    <div class="row">
                        @endif
                        <div class="col-lg-6 col-md-6">
                            <div class="effect">
                                <a href="{{  url('shop?category='.$category->slug) }}">
                                    <img class="img-fluid" src="{{ $category->thumbnail }}" alt="{{ $category->name }}" title="{{ $category->name }}">
                                </a>
                            </div>
                        </div>
                        @if (++$i == 2)
                    </div>
                    @php $i = 0; @endphp
                @endif
            @endforeach
            @if($i != 0)</div>@endif
        </div>
@endif