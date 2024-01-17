@extends('layouts.public')

@section('content')

    <div class="container">
        <div class="content">
            <div class="row">
                @include('partials.shop_filter')
                <div class="col-sm-8 col-lg-9 col-md-8 product-container">
                    <h1>@lang('corals-marketplace-pro::labels.template.shop.products')</h1>
                    <div class="text-center">
                        @php \Actions::do_action('pre_display_shop') @endphp
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
                            @if($products->count() > 0 )
                                <div class="row">
                                    @foreach($products as $product)
                                        @include('partials.product_'.$layout.'_item',compact('product'))
                                    @endforeach
                                </div>
                            @else
                                <h5 class="text-center">@lang('corals-marketplace-pro::labels.template.shop.sorry_no_result')</h5>
                            @endif

                        </div>
                    </div>

                    <!-- pagination -->
                    {{ $products->appends(request()->except('page'))->links('partials.paginator') }}
                </div>
                <!-- end col-md-9-1 -->
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