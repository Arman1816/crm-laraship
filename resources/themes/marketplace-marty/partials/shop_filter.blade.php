<form id="filterForm">
    <aside class="sidebar product--sidebar">
        <div class="sidebar-card card--category">
            <a class="card-title" href="#collapse1" role="button" data-toggle="collapse"
               aria-expanded="false" aria-controls="collapse1">
                <h4>Filter shop
                    <span class="lnr lnr-chevron-down"></span>
                </h4>
            </a>
            <div class="collapse show collapsible-content" id="collapse1">
                <div class="field-wrapper mb-3 mt-4">
                    <input class="relative-field" type="text"
                           name="search" placeholder="@lang('Marketplace::labels.shop.search')"
                           value="{{request()->get('search')}}">
                    <input type="hidden" name="sort" id="filterSort" value=""/>
                </div>
                <div class="field-wrapper">

                    <div class="custom-checkbox2">
                        <input name="featured" value="user-interface" type="checkbox"
                               id="featured" {{request()->has('featured') ? 'checked': '' }}>
                        <label for="featured">
                            <span class="circle"></span>
                            Featured Products
                        </label>

                    </div>
                </div>
                <br>
            </div>
        </div>
        <div class="sidebar-card card--filter">
            <a class="card-title" href="#collapse2" role="button" data-toggle="collapse"
               aria-expanded="false" aria-controls="collapse2">
                <h4>@lang('corals-marketplace-marty::labels.template.shop.shop_categories')
                    <span class="lnr lnr-chevron-down"></span>
                </h4>
            </a>
            <div class="collapse show collapsible-content" id="collapse2">
                <ul class="card-content">
                    @foreach(\Shop::getActiveCategories() as $category)
                        @include('partials.category_filter_item',['category'=>$category])
                    @endforeach
                </ul>
            </div>
        </div>
        @if(!($brands = \Shop::getActiveBrands(request()->input('category')))->isEmpty())
            <div class="sidebar-card card--category">
                <a class="card-title" href="#collapse1" role="button" data-toggle="collapse"
                   aria-expanded="false" aria-controls="collapse1">
                    <h4>@lang('corals-marketplace-marty::labels.template.shop.filter_brand')
                        <span class="lnr lnr-chevron-down"></span>
                    </h4>
                </a>
                <div class="collapse show collapsible-content" id="collapse1">
                    @foreach($brands as $brand)
                        <div class="custom-checkbox2">
                            <input name="brand[]" value="{{ $brand->slug }}"
                                   type="checkbox"
                                   id="brand_{{ $brand->id }}"
                                    {{ \Shop::checkActiveKey($brand->slug,'brand')?'checked':'' }}>
                            <label for="brand_{{ $brand->id }}">
                                <span class="circle"></span>
                                {{$brand->name}}
                                ({{ $brand->products_count }})
                            </label>
                        </div>
                    @endforeach
                    <br>
                </div>
            </div>
        @endif
        <div class="sidebar-card card--category">
            {!! \Shop::getAttributesForFilters(request()->input('category')) !!}
        </div>
        @php
            $min = \Shop::getSKUMinPrice()??0;
            $max= \Shop::getSKUMaxPrice()??9999999;
        @endphp
        @if($min != $max)
            <div class="sidebar-card card--slider">
                <a class="card-title" href="#collapse3" role="button" data-toggle="collapse"
                   aria-expanded="false" aria-controls="collapse3">
                    <h4>@lang('corals-marketplace-marty::labels.template.shop.price_range')
                        <span class="lnr lnr-chevron-down"></span>
                    </h4>
                </a>
                <div class="collapse show collapsible-content" id="collapse3">
                    <div class="card-content">
                        <div class="range-slider price-range"></div>
                        <div class="price-ranges">
                            <span class="from rounded">
                                         <span id="min"></span>
                                        <input name="price[min]" type="hidden"
                                               value="{{request()->input('price.min')??$min}}">
                                    </span>
                            <span class="to rounded">
                                <span id="max"></span>

                                       <input name="price[max]" type="hidden"
                                              value="{{ request()->input('price.max')??$max }}">
                                    </span>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <div class="sidebar-card card--category">
            <button class="btn author-area__seller-btn inline btn-block btn-sm"
                    type="submit">@lang('corals-marketplace-marty::labels.template.shop.filter')</button>
        </div>
    </aside>
</form>

@section('js')
    @parent

    <script type="text/javascript">
        $(document).ready(function () {
            var data_min = {{$min}};
            var data_max = {{$max}};
            var $priceFrom = $('.price-ranges .from input'),
                $priceTo = $('.price-ranges .to input');
            $(".price-range").slider({
                range: true,
                min: data_min,
                max: data_max,
                values: [{{request()->input('price.min')??$min}}, {{request()->input('price.max')??$max}}],
                create: function () {
                    $('#min').appendTo($('#slider a').get(0));
                    $('#max').appendTo($('#slider a').get(1));
                },
                slide: function (event, ui) {
                    $priceFrom.val(ui.values[0]);
                    $priceTo.val(ui.values[1]);

                    var delay = function () {
                        var handleIndex = $(ui.handle).index();
                        console.log(handleIndex);
                        var label = handleIndex == 1 ? '#min' : '#max';
                        $(label).html('$' + ui.value).position({
                            my: 'center top',
                            at: 'center bottom',
                            of: ui.handle,
                            offset: "0, 10"
                        });
                    };

                    // wait for the ui.handle to set its position
                    setTimeout(delay, 5);
                },

            });


            $('#min').html('$' + $('.price-range').slider('values', 0)).position({
                my: 'center top',
                at: 'center bottom',
                of: $('.price-range a:eq(0)'),
                offset: "0, 10"
            });

            $('#max').html('$' + $('.price-range').slider('values', 1)).position({
                my: 'center top',
                at: 'center bottom',
                of: $('.price-range a:eq(1)'),
                offset: "0, 10"
            });
        });
    </script>
@endsection