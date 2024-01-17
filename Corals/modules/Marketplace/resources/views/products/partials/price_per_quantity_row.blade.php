<tr>
    <td style="width: 13%;">
        {!! CoralsForm::number("price_per_quantity[$index][min_quantity]",'', true, data_get($pricePerQuantity,'min_quantity'), ['min'=>1,'help_text'=>trans('Marketplace::attributes.product.price_per_quantity.min_qty')]) !!}
    </td>
    <td style="width: 13%;">
        {!! CoralsForm::number("price_per_quantity[$index][max_quantity]",'', true, data_get($pricePerQuantity,'max_quantity'), ['min'=>1,'help_text'=>trans('Marketplace::attributes.product.price_per_quantity.max_qty')]) !!}
    </td>
    <td style="width: 14%;">
        {!! CoralsForm::number("price_per_quantity[$index][price]",'', true, data_get($pricePerQuantity,'price'),['min'=>0,'step'=>'0.01']) !!}
    </td>
    <td style="width: 15%;">
        {!! CoralsForm::select("price_per_quantity[$index][type]", '', get_array_key_translation(config('marketplace.models.product.price_per_quantity.price_per_quantity_types')), false, data_get($pricePerQuantity,'type'), ['data-allow-clear'=>'false','min'=>0],'select2') !!}
    </td>
    <td style="width: 40%;">
        {!! CoralsForm::text("price_per_quantity[$index][description]",'', true, data_get($pricePerQuantity,'description')) !!}
    </td>

    @if(isset($deletable) && $deletable)
        <td>
            <a href="#" class="remove-price-per-quantity-row text-danger" style="font-size: 18px">
                <i class="fa fa-trash"></i>
            </a>
        </td>
    @endif
</tr>
