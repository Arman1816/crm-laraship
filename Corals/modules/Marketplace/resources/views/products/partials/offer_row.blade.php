<tr>
    <td>
        {!! CoralsForm::select("offers[$index][type]", '', get_array_key_translation(config('marketplace.models.product.offers.offer_types')), false, data_get($offer,'type'), ['data-allow-clear'=>'false','min'=>0,'data-related_index'=>$index,'class'=>'offer-type'],'select2') !!}
    </td>
    <td>
        {!! CoralsForm::number("offers[$index][quantity]",'', true, data_get($offer,'quantity'), ['min'=>1]) !!}
    </td>
    <td>
        {!! CoralsForm::number("offers[$index][value]",'', true, data_get($offer,'value'),['min'=>0,'step'=>'0.01','help_text'=>  data_get($offer,'type') =='get_free' ? 'Marketplace::attributes.product.offers.free_items' :  'Marketplace::attributes.product.offers.price']) !!}
    </td>

    @if(isset($deletable) && $deletable)
        <td>
            <a href="#" class="remove-offer-row text-danger" style="font-size: 18px">
                <i class="fa fa-trash"></i>
            </a>
        </td>
    @endif
</tr>
