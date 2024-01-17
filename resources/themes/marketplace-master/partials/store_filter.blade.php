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