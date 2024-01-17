<form id="filterForm">
    <!-- Widget Search-->
    <section class="widget pt-1">
        <div class="input-group form-group">
            <div class="input-icon">
        <span class="input-group-btn">
                    <button type="submit" data-color="blue"><i class="icon-search"></i></button></span>
                <input class="form-control" type="text" name="search"
                       placeholder="@lang('Marketplace::labels.shop.search')"
                       value="{{request()->get('search')}}">
                <input type="hidden" name="sort" id="filterSort" value=""/>
            </div>
        </div>
    </section>

    <!-- Widget Categories-->
    <section class="widget widget-categories">
        <h3 class="widget-title">@lang('corals-marketplace-master::labels.template.shop.shop_categories')</h3>
        @if(\Settings::get('marketplace_category_filter_checkboxes', true))
            <ul>
                @foreach(\Shop::getActiveCategories() as $category)
                    @include('partials.category_filter_item',['category'=>$category])
                @endforeach
            </ul>
        @else
            <input name="category" value="{{ request()->get('category') }}"
                   type="hidden">
            <div class="category-sidebar">
                <ul>
                    @foreach(\Shop::getCategoriesHierarchy() as $category)
                        <li>
                            <a href="{{ url()->current().'?category='.$category['slug'] }}"
                               class="{{ $hasChildren = count($category['children'])?'show-mega':'' }}">
                                {!! \Shop::checkActiveKey($category['slug'],'category')?'<i class="fa fa-check text-success"></i>':'' !!}
                                {{ $category['name'] }}
                                {{--                                ({{ $category['products_count'] }})--}}
                                @if($hasChildren)
                                    <i class="fa fa-fw fa-angle-right"></i>
                                @endif
                            </a>
                            @if($hasChildren)
                                @include('partials.category_mega_item',['category'=>$category])
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>

        @endif
    </section>
@php
    $min = \Shop::getSKUMinPrice()??0;
    $max= \Shop::getSKUMaxPrice()??9999999;
@endphp
@if($min !== $max )
    <!-- Widget Price Range-->
        <section class="widget widget-categories">
            <h3 class="widget-title">@lang('corals-marketplace-master::labels.template.shop.price_range')</h3>
            <div class="price-range-slider"
                 data-min="{{ $min }}"
                 data-max="{{ $max }}"
                 data-start-min="{{ request()->input('price.min', $min) }}"
                 data-start-max="{{ request()->input('price.max', $max) }}"
                 data-step="1">
                <div class="ui-range-slider"></div>
                <footer class="ui-range-slider-footer">
                    <div class="column">
                        <div class="ui-range-values">
                            <div class="ui-range-value-min">{{ \Payments::currency_symbol() }}<span></span>
                                <input name="price[min]" type="hidden">
                            </div>&nbsp;-&nbsp;
                            <div class="ui-range-value-max">{{ \Payments::currency_symbol() }}<span></span>
                                <input name="price[max]" type="hidden">
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </section>
    @endif
    @if(!($brands = \Shop::getActiveBrands(request()->input('category')))->isEmpty())
        <section class="widget">
            <h3 class="widget-title">@lang('corals-marketplace-master::labels.template.shop.filter_brand')</h3>
            @foreach($brands as $brand)
                <div class="">
                    <input class=""
                           name="brand[]" value="{{ $brand->slug }}"
                           type="checkbox" id="brand_{{ $brand->id }}"
                            {{ \Shop::checkActiveKey($brand->slug,'brand')?'checked':'' }}/>
                    <label class="" for="brand_{{ $brand->id }}">{{ $brand->name }}
                        &nbsp;<span class="text-muted">
                                            ({{ $brand->products_count }})
                                        </span>
                    </label>
                </div>
            @endforeach
        </section>
    @endif
    <section class="widget">
        <div class="column">
            {!! \Shop::getAttributesForFilters(request()->input('category')) !!}
        </div>
    </section>
    <section class="widget">
        <div class="column">
            <h3 class="widget-title">@lang('corals-marketplace-master::labels.template.shop.filter_store')</h3>
            <div>
                {!!     CoralsForm::select('store', 'Marketplace::attributes.product.store', [], false, '', [
                    'class' => 'select2-ajax',
                    'data' => [
                        'model' => \Corals\Modules\Marketplace\Models\Store::class,
                        'columns' => json_encode(['name']),
                        'key_column'=>'slug',
                        'selected' => json_encode(request()->has('store') ? [ request()->input('store')] : []),
                         'where' =>json_encode( [['field' => 'marketplace_stores.status', 'operation' => '=', 'value' => 'active']]),
                         'is_public'=>1

                                             ]
                ], 'select2') !!}
            </div>

        </div>
    </section>

    <section class="widget">
        <div class="column">
            <button class="btn btn-outline-primary btn-block btn-sm"
                    type="submit">@lang('corals-marketplace-master::labels.template.shop.filter')</button>
        </div>
    </section>
</form>
@php \Actions::do_action('post_display_marketplace_filter') @endphp

@isset($store)
    {!!   \Shortcode::compile( 'zone','store-sidebar' ) ; !!}
@else
    {!!   \Shortcode::compile( 'zone','shop-sidebar' ) ; !!}
@endisset