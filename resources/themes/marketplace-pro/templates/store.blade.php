@extends('layouts.public')

@section('content')

    <div class="container">
        <div class="content store">
            <div class="row">
                <div class="col-12">
                    <div class="image-section" style="position: relative"><img class="img-fluid"
                                                                               src="{{ $store->cover_photo }}">
                    </div>
                    <div class="img-fluid2">
                        <img id="store-logo" src="{{ $store->thumbnail }}">
                    </div>
                </div>
            </div>
            <div class="row">
                @include('partials.shop_filter',['store_page'=>true])
                <div class="col-sm-8 col-lg-9 col-md-8 product-container">
                    <div class="text-center">
                        @php \Actions::do_action('pre_display_shop') @endphp
                        <div class="store-banner">
                            {!!   \Shortcode::compile( 'zone','store-header' )  !!}
                        </div>
                    </div>
                    <div class="col-md-12 profile-header mb-3">
                        <div class="row">
                            <div class="col-md-10 profile-header-section1  mt-2">
                                <h1 class="mb-2">{{ $store->name }}</h1>
                                <p>{!!    $store->short_description !!}</p>
                            </div>
                            <div class="col-md-2 ">
                                <a class="btn btn-outline-secondary btn-primary pull-right"
                                   data-toggle="tooltip"
                                   title="@lang('corals-marketplace-pro::labels.template.store.contact',['store_name'=>$store->name])"
                                   href="{{ url('messaging/discussions/create?user='.$store->user->hashed_id) }}"
                                   data-original-title="@lang('corals-marketplace-pro::labels.template.store.contact',['store_name'=>$store->name])"><i
                                            class="fa fa-envelope-o"></i>&nbsp; @lang('corals-marketplace-pro::labels.template.store.contact',['store_name'=>$store->name])
                                </a>
                                @if($store->return_policy)
                                    <div class="text-center">
                                        <a href="{{ request()->url().'/return-policy' }}" class="modal-load"
                                           data-title="@lang('Marketplace::attributes.store.return_policy')">
                                            <small>@lang('Marketplace::attributes.store.return_policy')</small>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>


                    <div class="js-product-list-top firt nav-top">
                        <div class="d-flex justify-content-around row">
                            <div class="col col-xs-12">

                                <ul class="nav nav-tabs">
                                    <li>
                                        <a class="fa fa-th-large {{ $layout=='grid'?'active show ':'' }}"
                                           href="{{ request()->fullUrlWithQuery(['layout'=>'grid']) }}">
                                        </a>
                                    </li>
                                    <li>
                                        <a class="fa fa-list-ul {{ $layout=='list'?'active show ':'' }}"
                                           href="{{ request()->fullUrlWithQuery(['layout'=>'list']) }}">
                                        </a>
                                    </li>
                                </ul>
                                <div class="hidden-sm-down total-products">
                                    <p>
                                        <span>{{trans('corals-marketplace-pro::labels.template.shop.page',['current'=>$products->currentPage(),'total' => $products->lastPage()])}}</span>
                                    </p>
                                </div>
                            </div>
                            @isset($shopText)
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        {{ $shopText }}
                                    </div>
                                </div>
                            @endisset
                            <div class="col col-xs-12">
                                <div class="d-flex sort-by-row justify-content-lg-end justify-content-md-end">

                                    <div class="products-sort-order dropdown">
                                        <select class="select-title" id="shop_sort">
                                            <option disabled="disabled"
                                                    selected>@lang('corals-marketplace-pro::labels.template.shop.select_option')</option>
                                            @foreach($sortOptions as $value => $text)
                                                <option value="{{ $value }}" {{ request()->get('sort') == $value?'selected':'' }}>
                                                    {{ $text }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-content product-items">
                        <div id="grid"
                             class="related tab-pane fade in active show">
                            <div class="row">
                                @forelse($products as $product)
                                    @include('partials.product_'.$layout.'_item',compact('product'))
                                @empty
                                    <h5 class="text-center">@lang('corals-marketplace-pro::labels.template.shop.sorry_no_result')</h5>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- pagination -->
                    {{ $products->appends(request()->except('page'))->links('partials.paginator') }}
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 text-center">
                    <div class="store-banner">
                        {!!   \Shortcode::compile( 'zone','store-footer' )  !!}
                    </div>

                </div>

            </div>
        </div>
    </div>

@stop
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
