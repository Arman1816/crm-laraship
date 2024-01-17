@extends('layouts.public')

@section('content')
    <section class="search-wrapper">
        <div class="search-area2 bgimage">
            <div class="bg_image_holder">
                <img src="{{\Theme::url('images/search.jpg')}}" alt="">
            </div>
            <div class="container content_above">
                <div class="row">
                    <div class="col-md-8 offset-md-2">
                        <div class="search">
                            <div class="search__field">
                                <form id="filterForm">
                                    <div class="field-wrapper">
                                        <input class="relative-field rounded" type="text"
                                               name="search" placeholder="@lang('Marketplace::labels.shop.search')">
                                        <button class="btn btn--round" type="submit">
                                            @lang('Marketplace::labels.shop.search')
                                        </button>
                                        <input type="hidden" name="sort" id="filterSort" value=""/>
                                    </div>
                                </form>
                            </div>
                            <br>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="filter-area">
        <div class="container">
            @php \Actions::do_action('pre_display_shop') @endphp
            <div class="row">
                <div class="col-md-12">
                    <div class="filter-bar filter--bar2">
                        <div class="pull-right">
                            <div class="filter__option filter--select">
                                <div class="select-wrap">
                                    <select name="price" id="shop_sort">
                                        @foreach($sortOptions as $value => $text)
                                            <option value="{{ $value }}" {{ request()->get('sort') == $value?'selected':'' }}>
                                                {{ $text }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="lnr lnr-chevron-down"></span>
                                </div>
                            </div>
                            <div class="filter__option filter--layout">
                                <a href="{{ request()->fullUrlWithQuery(['layout'=>'grid']) }}">
                                    <div class="svg-icon">
                                        <img class="svg" src="{{\Theme::url('images/svg/grid.svg')}}"
                                             alt="it's just a layout control folks!">
                                    </div>
                                </a>
                                <a href="{{ request()->fullUrlWithQuery(['layout'=>'list']) }}">
                                    <div class="svg-icon">
                                        <img class="svg" src="{{\Theme::url('images/svg/list.svg')}}"
                                             alt="it's just a layout control folks!">
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <section class="products section--padding2">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    @include('partials.shop_filter')
                </div>
                <div class="col-lg-9">
                    <div class="row">
                        @forelse($products as $product)
                            @include('partials.product_'.$layout.'_item',compact('product'))
                        @empty
                            <h4>@lang('corals-marketplace-marty::labels.template.shop.sorry_no_result')</h4>
                        @endforelse

                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    {{ $products->appends(request()->except('page'))->links('partials.paginator') }}
                </div>
            </div>
        </div>
    </section>
    <section class="call-to-action bgimage">
        {!!   \Shortcode::compile( 'block','call-to-action' ) ; !!}

    </section>
@endsection
@section('js')
    @parent
    <script type="text/javascript">
        $(document).ready(function () {
            $("#shop_sort").change(function () {
                $("#filterSort").val($(this).val());

                $("#filterForm").submit();
            })
        });
    </script>
@endsection