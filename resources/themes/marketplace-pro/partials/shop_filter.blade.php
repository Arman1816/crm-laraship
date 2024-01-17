<div class="sidebar-3 sidebar-collection col-lg-3 col-md-4 col-sm-4" style="box-shadow: rgba(0, 0, 0, 0.12) 0px 1px 3px, rgba(0, 0, 0, 0.24) 0px 1px 2px;">
    @empty($store)
        <div class="sidebar-block">
            <div class="title-block">@lang('corals-marketplace-pro::labels.partial.categories')</div>
            <div class="block-content">
                @foreach(\Shop::getActiveCategories() as $category)
                    <div style="border-bottom: 1px dotted #091E08" class="{{ $hasChildren = $category->hasChildren()?'has-children':'' }} parent-category cateTitle hasSubCategory open level1">
                        @if($hasChildren)
                            <span class="arrow collapsed collapse-icons" data-toggle="collapse"
                                  data-target="#{{ $category->id }}" aria-expanded="false" role="status">
                                      <i class="zmdi zmdi-minus"></i>
                                      <i class="zmdi zmdi-plus"></i>
                                                    </span>
                        @endif
                        <a  href="{{ url('shop?category='.$category->slug) }}">
                            {{ $category->name }}
                            <span>({{
                                            \Shop::getCategoryAvailableProducts($category->id, true)
                                            }})</span>
                        </a>
                        @if($hasChildren)
                            <div class="subCategory collapse" id="{{ $category->id }}" aria-expanded="true"
                                 role="status">
                                @foreach($category->children as $child)
                                    <div class="cateTitle">
                                        <a href="{{ url('shop?category='.$child->slug) }}" class="cateItem">
                                            {{ $child->name }}
                                            ({{
                                                                \Shop::getCategoryAvailableProducts($child->id, true)
                                                                }})
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endempty
    <form id="filterForm">
        <input type="hidden" name="sort" id="filterSort" value=""/>
        <div class="sidebar-block">
            <div class="new-item-content">
                <h3 class="title-product" style="border-bottom: 1px dotted #091E08; color:black">@lang('corals-marketplace-pro::labels.partial.categories')</h3>
                <ul class="scroll-product">
                    @foreach(\Shop::getActiveCategories() as $category)
                        @include('partials.category_filter_item',['category' => $category, 'level'=>0])
                    @endforeach
                </ul>
            </div>
            @if(!($brands = \Shop::getActiveBrands(request()->input('category')))->isEmpty())
                <div class="new-item-content">
                    <h3 class="title-product">@lang('corals-marketplace-pro::labels.template.shop.filter_brand')</h3>
                    <ul class="scroll-product">
                        @foreach($brands as $brand)
                            <li>
                                <label class="check">
                                    <input class=""
                                           name="brand[]" value="{{ $brand->slug }}"
                                           type="checkbox" id="brand_{{ $brand->id }}"
                                            {{ \Shop::checkActiveKey($brand->slug,'brand')?'checked':'' }}/>
                                    <span class="checkmark"></span>
                                </label>
                                <a href="#" for="brand_{{ $brand->id }}">
                                    <b>{{ $brand->name }}</b>
                                    <span class="quantity">({{ $brand->products_count }})</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @php
                $min = \Shop::getSKUMinPrice()??0;
                $max = \Shop::getSKUMaxPrice()??9999999;
            @endphp
            @if($min !== $max )
                <div class="tiva-filter-price new-item-content sidebar-block">
                    <h3 class="title-product">@lang('corals-marketplace-pro::labels.template.shop.price_range')</h3>
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
                                        <input id="price-filter" name="price[min]" type="hidden">
                                    </div>
                                    &nbsp;-&nbsp;
                                    <div class="ui-range-value-max">{{ \Payments::currency_symbol() }}<span></span>
                                        <input id="price-filter" name="price[max]" type="hidden">
                                    </div>
                                </div>
                            </div>
                        </footer>
                    </div>
                </div>
            @endif
            <div class="sidebar-block by-color">
                {!! \Shop::getAttributesForFilters(request()->input('category')) !!}
            </div>
        </div>
        <section class="widget">
            <div class="column">
                <button class="btn btn-outline-primary btn-block btn-sm"
                        type="submit">@lang('corals-marketplace-pro::labels.template.shop.filter')</button>
            </div>
        </section>
    </form>
    @isset($store)
        {!!   \Shortcode::compile( 'zone','store-sidebar' ) ; !!}
    @else
        {!!   \Shortcode::compile( 'zone','shop-sidebar' ) ; !!}
    @endisset
</div>
@php \Actions::do_action('post_display_marketplace_filter') @endphp


