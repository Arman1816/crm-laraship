<div class="row">
    <div class="col-md-12">
        {!! Form::open( ['url' => url($urlPrefix.'checkout'),'method'=>'POST','class'=>'ajax-form']) !!}
        @foreach($orders as $order)
            <input type="hidden" name="order_ids[]" value="{{ $order->id  }}"/>

            <div class="card panel panel-default m-b-10">
                <div class="card-header panel-heading">
                    <label class="m-r-10"> @lang('Marketplace::attributes.order.order_number'):
                        <strong>{{ $order->order_number }}</strong></label>
                    <label class="m-r-10"> @lang('Marketplace::attributes.order.amount'):
                        <strong>{{ $order->present('amount') }}</strong></label>
                    <label class="m-r-10"> @lang('Marketplace::attributes.order.store'):
                        <strong>{!! $order->present('store')  !!} </strong> </label>
                </div>
                <div class="card-body panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table color-table info-table table table-hover table-striped table-condensed">
                                    <thead>
                                    <tr>
                                        <th>
                                            @lang('Marketplace::attributes.order.subtotal')
                                            @if(Settings::get('marketplace_tax_tax_included_in_price'))
                                                <small style="font-size: 9px">@lang('Marketplace::attributes.product.tax_included') </small>
                                            @endif
                                        </th>
                                        <th>@lang('Marketplace::attributes.order.quantity')</th>
                                        <th>@lang('Marketplace::attributes.order.description')</th>
                                        <th>@lang('Marketplace::attributes.order.sku_code')</th>
                                        <th>@lang('Marketplace::attributes.order.type')</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($order->items as $item)
                                        @continue($item->getProperty('hidden') == 1)
                                        <tr>
                                            <td>{{ \Payments::currency($item->subtotal, $order->currency) }}</td>
                                            <td>{{ $item->quantity??'-' }}</td>
                                            <td>{!!   $item->description??'-' !!}</td>
                                            <td>{{ $item->sku_code??'-' }}</td>
                                            <td>{{ $item->type }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



        @endforeach
        <div class="table-responsive">
            <table class="table color-table info-table table table-hover table-striped table-condensed">
                <tbody>

                <tr>
                    <td class=""></td>
                    <td></td>
                    <td></td>
                    <td class="small-caps table-bg"
                        style="text-align: right">@lang('Marketplace::labels.checkout.sub_total')</td>
                    <td id="sub_total">
                        {{ ShoppingCart::subTotalAllInstances($format = true, $withDiscount = true, $withoutTaxIncluded = true) }}

                        @if(Settings::get('marketplace_tax_tax_included_in_price'))
                            <small style="font-size: 9px">@lang('Marketplace::attributes.product.without_taxes') </small>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class=""></td>
                    <td></td>
                    <td></td>
                    <td class="small-caps table-bg"
                        style="text-align: right">@lang('Marketplace::labels.checkout.tax')</td>
                    <td id="tax_total">{{ \ShoppingCart::taxTotalAllInstances() }}</td>
                </tr>

                <tr>
                    <td class=""></td>
                    <td></td>
                    <td></td>
                    <td class="small-caps table-bg"
                        style="text-align: right">@lang('Marketplace::labels.checkout.discount')</td>
                    <td id="total_discount">{{ ShoppingCart::totalDiscountAllInstances() }}</td>
                </tr>

                <tr class="border-bottom">
                    <td class=""></td>
                    <td></td>
                    <td></td>
                    <td class="small-caps table-bg"
                        style="text-align: right">@lang('Marketplace::labels.checkout.total')</td>
                    <td id="total"><strong> {{ \ShoppingCart::totalAllInstances() }} </strong></td>
                </tr>
                </tbody>
            </table>
        </div>
        {!! CoralsForm::formButtons(trans('Marketplace::labels.checkout.complete_order'), [], []) !!}
        {!! Form::close() !!}
    </div>
</div>
