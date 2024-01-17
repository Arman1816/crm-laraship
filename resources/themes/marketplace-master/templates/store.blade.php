@extends('layouts.public')

@section('css')
    {!! Theme::css('css/category_sidebar.css') !!}
@endsection

@section('before_content')
    <!-- Shop Filters Modal-->
    <div class="modal fade" id="modalShopFilters" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">@lang('corals-marketplace-master::labels.template.shop.shop_filter')</h4>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    @include('partials.shop_filter',['store'=>true])
                </div>
            </div>
        </div>
    </div>
@endsection

@section('editable_content')
    <!-- Page Content-->
    <style>
        .container {
            margin-top: 0px !important;
        }

        .image-section {
            padding: 0px;
        }

        .image-section img {
            width: 100%;
            height: 250px;
            position: relative;
        }

        .store-image {
            position: absolute;
            margin-top: -60px;
        }

        .store-image img {
            width: 100px;
            height: auto;
        }

        .user-profile-part {
            padding-bottom: 30px;
            background-color: #FAFAFA;
        }

        .user-detail-row {
            margin: 0px;
        }

        .profile-right-section {
            padding: 20px 0px 10px 15px;
            background-color: #FFFFFF;
        }

        .profile-right-section-row {
            margin: 0px;
        }

        .image-right-detail p {
            font-size: 12px;
        }

        .image-right-detail a:hover {
            text-decoration: none;
        }

        .image-right img {
            width: 100%;
        }

        .image-right-detail-section2 p {
            color: #38ACDF;
            margin: 0px;
        }

        .image-right-detail-section2 span {
            color: #7F7F7F;
        }
    </style>
    <div class="container padding-bottom-3x mb-1 mt-5">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12 image-section">
                <img src="{{ $store->cover_photo }}">
            </div>
        </div>
        <div class="row user-left-part">
            <div class="col-md-3 col-sm-3 col-xs-12 user-profile-part pull-left">
                <div class="row ">
                    <div class="col-md-12 col-md-12-sm-12 col-xs-12 store-image text-center">
                        <img id="store-logo" src="{{ $store->thumbnail }}">
                    </div>
                </div>
                <div class="row p-t-20">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        @if($store->return_policy)
                            <div class="text-center">
                                <a href="{{ request()->url().'/return-policy' }}" class="modal-load"
                                   data-title="@lang('Marketplace::attributes.store.return_policy')">
                                    <small>@lang('Marketplace::attributes.store.return_policy')</small>
                                </a>
                            </div>
                        @endif
                        <button class="sidebar-toggle position-left" data-toggle="modal"
                                data-target="#modalShopFilters"><i
                                    class="icon-layout"></i></button>
                        <aside class="sidebar sidebar-offcanvas">
                            @include('partials.shop_filter',['store'=>true])
                        </aside>
                    </div>

                </div>
            </div>
            <div class="col-md-9 col-sm-9 col-xs-12 pull-right profile-right-section">
                <div class="row profile-right-section-row">
                    <div class="col-md-12 profile-header">
                        <div class="row">
                            <div class="col-md-10 profile-header-section1 pull-left">
                                <h1>{{ $store->name }}</h1>
                                <p>{!!    $store->short_description !!}</p>
                            </div>
                            <div class="col-md-2 ">
                                <a class="btn btn-outline-secondary btn-primary pull-right"
                                   data-toggle="tooltip"
                                   title="@lang('corals-marketplace-master::labels.template.store.contact',['store_name'=>$store->name])"
                                   href="{{ url('messaging/discussions/create?user='.$store->user->hashed_id) }}"
                                   data-original-title="@lang('corals-marketplace-master::labels.template.store.contact',['store_name'=>$store->name])"><i
                                            class="fa fa-envelope-o"></i>&nbsp; @lang('corals-marketplace-master::labels.template.store.contact',['store_name'=>$store->name])
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">


                        <div class="row m-t-20">

                            <!-- Products-->
                            <div class="col-xl-12 col-lg-12 m-b-20">
                                <div class="text-center">
                                    @php \Actions::do_action('pre_display_shop') @endphp
                                    {!!   \Shortcode::compile( 'zone','store-header' ) ; !!}
                                </div>
                                <!-- Shop Toolbar-->
                                <div class="shop-toolbar padding-bottom-1x mb-2">
                                    <div class="column">
                                        <div class="shop-sorting">
                                            <label for="sorting">@lang('corals-marketplace-master::labels.template.shop.sort')</label>

                                            <select class="form-control" id="shop_sort">
                                                <option disabled="disabled"
                                                        selected>@lang('corals-marketplace-master::labels.template.shop.select_option')</option>
                                                @foreach($sortOptions as $value => $text)
                                                    <option value="{{ $value }}" {{ request()->get('sort') == $value?'selected':'' }}>
                                                        {{ $text }}
                                                    </option>
                                                @endforeach
                                            </select>

                                            <span class="text-muted">@lang('corals-marketplace-master::labels.template.shop.show')
                                                &nbsp;</span>
                                            <span>{{trans('corals-marketplace-master::labels.template.shop.page',['current'=>$products->currentPage(),'total' => $products->lastPage()])}}</span>
                                        </div>
                                    </div>
                                    <div class="column">
                                        <div class="shop-view">
                                            <a class="grid-view {{ $layout=='grid'?'active':'' }}"
                                               href="{{ request()->fullUrlWithQuery(['layout'=>'grid']) }}">
                                                <span></span><span></span><span></span>
                                            </a>
                                            <a class="list-view {{ $layout=='list'?'active':'' }}"
                                               href="{{ request()->fullUrlWithQuery(['layout'=>'list']) }}">
                                                <span></span><span></span><span></span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @isset($shopText)
                                    <div class="row mb-3">
                                        <div class="col-md-12">
                                            {{ $shopText }}
                                        </div>
                                    </div>
                                @endisset


                                <div id="shop-items">
                                    <div class="{{ $layout == 'grid'?'isotope-grid':'' }} cols-3 mb-2">
                                        <div class="gutter-sizer"></div>
                                        <div class="grid-sizer"></div>
                                        <!-- Product-->
                                        @forelse($products as $product)
                                            @include('partials.product_'.$layout.'_item',compact('product'))
                                        @empty
                                            <h4>@lang('corals-marketplace-master::labels.template.shop.sorry_no_result')</h4>
                                        @endforelse
                                    </div>

                                    <!-- Pagination-->
                                    {{ $products->appends(request()->except('page'))->links('partials.paginator') }}
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-center">
                        {!!   \Shortcode::compile( 'zone','store-footer' ) ; !!}

                    </div>

                </div>
            </div>
        </div>
    </div>

@stop

@section('js')
    @parent
    {!! Theme::js('js/category_sidebar.js') !!}
    <script type="text/javascript">
        $(document).ready(function () {
            $("#shop_sort").change(function () {
                $("#filterSort").val($(this).val());

                $("#filterForm").submit();
            })
        });
    </script>
@endsection
