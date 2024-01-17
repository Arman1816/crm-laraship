@if(\Store::getVendorStore())
    <h4>@lang('Marketplace::labels.widget.my_store_statistics')</h4>
    <hr>
    <div class="row m-b-30" id="my_store_widgets">
        <div class="col-lg-4 col-xs-6 mb-1">
            @widget('my_store_orders')
        </div>
        <!-- ./col -->
        <div class="col-lg-4 col-xs-6 mb-1">
            @widget('my_store_products')
        </div>
        <!-- ./col -->
        <div class="col-lg-4 col-xs-6 mb-1">
            <!-- small box -->
            @widget('my_store_sales')

        </div>
        <!-- ./col -->
    </div>
@endif

<h4 class="mt-3">@lang('Marketplace::labels.widget.buyer_statistics')</h4>
<hr>

<div class="row m-b-30" id="buyer_widgets">

    <div class="col-lg-4 col-xs-6 mb-1">
        @widget('my_orders')
    </div>
    <!-- ./col -->
    <div class="col-lg-4 col-xs-6 mb-1">
        @widget('my_wishlist')
    </div>
    <!-- ./col -->
    <div class="col-lg-4 col-xs-6 mb-1">
        <!-- small box -->
        @widget('my_downloads')
    </div>
    <!-- ./col -->
</div>


