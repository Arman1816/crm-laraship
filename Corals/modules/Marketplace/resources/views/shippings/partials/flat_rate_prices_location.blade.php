<div class="location-rate">
    <div class="row">
        <div class="col-md-3">
            {!! CoralsForm::select("shipping_rate[$index][country]", 'Marketplace::attributes.shipping.country', \Store::getStoreShippableCountries(isset($product) && $product->exists?$product->store:$store??null), true, $rate['country'], [], 'select2') !!}
        </div>
        <div class="col-md-3">
            {!! CoralsForm::select("shipping_rate[$index][shipping_provider]", 'Marketplace::attributes.shipping.provider',
                \ListOfValues::get('marketplace_shipping_providers')+trans('Marketplace::attributes.shipping.other_option'),
                true, $rate['shipping_provider'],
                ['id'=>'shipping_provider_'.$index,'class'=>'shipping_provider'], 'select2') !!}
        </div>
        <div class="col-md-3 other-provider"
             style="{{ data_get($rate,'shipping_provider') == 'other'?'':'display:none' }}">
            {!! CoralsForm::text("shipping_rate[$index][other_shipping_provider]",'Marketplace::attributes.shipping.other_shipping',true, $rate['other_shipping_provider']??'') !!}
        </div>
        <div class="col-md-3">
            {!! CoralsForm::select("shipping_rate[$index][shipping_method]", 'Marketplace::attributes.shipping.shipping_method_location', \Shipping::getShippingMethods(['Shippo']) , true, $rate['shipping_method'], ['class'=>'shipping_method'], 'select2') !!}
        </div>
    </div>
    <div class="row">
        <div class="col-md-3 non-free"
             style="{{ data_get($rate,'shipping_method') != 'Free'?'':'display:none' }}">
            {!! CoralsForm::number("shipping_rate[$index][one_item_price]", 'Marketplace::attributes.shipping.one_item_price',true, $rate['one_item_price'],['step'=>0.01,'min'=>0,'max'=>999999]) !!}
        </div>
        <div class="col-md-3 non-free"
             style="{{ data_get($rate,'shipping_method') != 'Free'?'':'display:none' }}">
            {!! CoralsForm::number("shipping_rate[$index][additional_item_price]", 'Marketplace::attributes.shipping.additional_item_price', false, $rate['additional_item_price'],['step'=>0.01,'min'=>0,'max'=>999999]) !!}
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            {!! \CoralsForm::button('Marketplace::labels.package.remove_location', ['class' => 'btn btn-danger btn-sm flat-rate-remove-location']) !!}
        </div>
    </div>
</div>
