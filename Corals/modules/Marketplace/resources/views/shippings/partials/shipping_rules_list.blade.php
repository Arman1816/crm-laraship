<div class="table-responsive">
    <table class="table table-striped">
        <thead>
        <tr>
            <th></th>
            <th>@lang('Marketplace::attributes.shipping.name')</th>
            <th>@lang('Marketplace::attributes.shipping.shipping_method')</th>
            <th>@lang('Marketplace::attributes.shipping.country')</th>
            <th>@lang('Marketplace::attributes.shipping.rate')</th>
            <th>@lang('Marketplace::attributes.shipping.min_order_total')</th>
        </tr>
        </thead>
        <tbody>
        @forelse(\Shipping::getShippingRules(isset($product) && $product->exists?$product->store:$store??null) as $shippingRule)
            <tr>
                <td>
                    {{ CoralsForm::checkbox('product_shipping_rules[]','',
                     !in_array($shippingRule->id, $product->getProperty('excluded_shipping_rules', [],null,'shipping')),
                     $shippingRule->id) }}
                </td>
                <td>{!! $shippingRule->present('name') !!} [{!!  $shippingRule->present('store') !!}]</td>
                <td>{!! $shippingRule->present('shipping_method') !!}</td>
                <td>{!! $shippingRule->present('country') !!}</td>
                <td>{!! $shippingRule->present('rate') !!}</td>
                <td>{!! $shippingRule->present('min_order_total') !!}</td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center">
                    @lang('Corals::labels.no_data_found')
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
