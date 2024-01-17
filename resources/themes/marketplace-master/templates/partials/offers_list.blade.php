<div class="p-2">
    <h4>@lang('Marketplace::attributes.product.offers.title')</h4>
    <div class="table-responsive">
        <table class="table table-striped">
            @foreach($offers as $offer)
                <tr>
                    <th>
                        @lang('Marketplace::attributes.product.offers.'.data_get($offer,'type').'_description',[
                             'quantity'=>data_get($offer,'quantity'),
                             'value'=> data_get($offer,'type') == 'bundle_price' ?\Payments::currency(data_get($offer,'value')):data_get($offer,'value')
                        ])
                    </th>
                </tr>
            @endforeach
        </table>
    </div>
</div>
