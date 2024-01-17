<div class="p-2">
    <h4>@lang('Marketplace::attributes.product.price_per_quantity.title')</h4>
    <div class="table-responsive">
        <table class="table table-striped">
            @foreach($pricePerQuantity as $pricePerQ)
                @continue(empty(data_get($pricePerQ,'description')))
                <tr>
                    <th>{{ data_get($pricePerQ,'description') }}</th>
                </tr>
            @endforeach
        </table>
    </div>
</div>
