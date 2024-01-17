@extends('layouts.crud.show')

@section('content_header')
    @component('components.content_header')
        @slot('page_title')
            {{ $title_singular }}
        @endslot

        @slot('breadcrumb')
            {{ Breadcrumbs::render('marketplace_orders') }}
        @endslot
    @endcomponent
@endsection



@section('content')
    <div class="row">
        <div class="col-md-8">
            @component('components.box')
                @slot('box_title')
                    @lang('Marketplace::labels.order.order_detail')
                @endslot
                <div class="table-responsive">
                    <table class="table color-table info-table table table-hover table-striped table-condensed">
                        <thead>
                        <tr>
                            <th>@lang('Marketplace::attributes.order.id')</th>
                            <th>@lang('Marketplace::attributes.order.amount')</th>
                            <th>@lang('Corals::attributes.status')</th>
                            <th>@lang('Marketplace::attributes.order.user_id')</th>
                            <th>@lang('Marketplace::attributes.order.store')</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->present('amount') }}</td>
                            <td>{!! $order->present('status') !!}</td>
                            <td>{!! $order->present('user_id') !!}</td>
                            <td>{!! $order->present('store') !!}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <h3>Items</h3>
                <div class="table-responsive">
                    <table class="table color-table info-table table table-hover table-striped table-condensed">
                        <thead>
                        <tr>
                            <th>@lang('Marketplace::attributes.order.subtotal')</th>
                            <th>@lang('Marketplace::attributes.order.quantity')</th>
                            <th>@lang('Marketplace::attributes.order.description')</th>
                            <th>@lang('Marketplace::attributes.order.sku_code')</th>
                            <th>@lang('Marketplace::attributes.order.type')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td>{{ \Payments::currency($item->subtotal, $order->currency) }}</td>
                                <td>{{ $item->quantity??'-' }}</td>
                                <td>{!!  $item->present('description') !!} <br>
                                    {!! isset($item->item_options['product_options']) ? formatArrayAsLabels(\OrderManager::mapSelectedAttributes($item->item_options['product_options']), 'success',null,true) : ''    !!}
                                </td>
                                <td>{{ $item->sku_code??'-' }}</td>
                                <td>{{ $item->type }}</td>
                            </tr>

                        @endforeach
                        </tbody>
                    </table>
                </div>

                @if($downloads = \OrderManager::getOrderDownloadable($order))
                    <h3>@lang('Marketplace::labels.order.download_able')</h3>
                    <div class="table-responsive">
                        <table id="downloads-table" width="100%"
                               class="table color-table info-table table table-hover table-striped table-condensed">
                            <thead>
                            <tr>
                                <th width="30%">@lang('Marketplace::attributes.order.file')</th>
                                <th width="70%">@lang('Marketplace::attributes.order.description')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($downloads as $download)
                                <tr id="tr_{{ $loop->index }}" data-index="{{ $loop->index }}">
                                    <td>
                                        <a href="{{ url('marketplace/orders/'.$order->hashed_id.'/download/'.$download['hashed_id']) }}"
                                           target="_blank">{{ $download['name'] }}</a>
                                    </td>
                                    <td>
                                        {{ $download['description'] }}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
                @if(count($order->posts))
                    <h3>@lang('Marketplace::labels.order.private_access')</h3>
                    <ul>
                        @foreach($order->posts as $post)
                            <li>
                                {!! CoralsForm::link(url($post->slug),trans('Marketplace::labels.order.magic',['title' => $post->title]),['class'=>'','target'=>'_blank']) !!}
                            </li>
                        @endforeach
                    </ul>
                @endif

                {!! \Actions::do_action('marketplace_order_post_details', $order) !!}
            @endcomponent
            @component('components.box')
                @slot('box_title')
                    @lang('Marketplace::labels.order.transactions.title')
                @endslot
                @if($order->transactions)
                    <div class="table-responsive">
                        <table class="table color-table info-table table table-hover table-striped table-condensed">
                            <thead>
                            <tr>
                                <th>@lang('Marketplace::labels.order.transactions.type')</th>
                                <th>@lang('Marketplace::labels.order.transactions.amount')</th>
                                <th>@lang('Marketplace::labels.order.transactions.paid_amount')</th>
                                <th>@lang('Corals::attributes.status')</th>
                                <th>@lang('Marketplace::labels.order.transactions.reference')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($order->transactions as $transaction)
                                <tr>
                                    <td>{{ $transaction->present('type')}}</td>
                                    <td>{{ $transaction->present('amount') }}</td>
                                    <td>{{ $transaction->present('paid_amount') }}</td>
                                    <td>{!! $transaction->present('status') !!}</td>
                                    <td>{{ $transaction->present('reference') }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            @endcomponent

        </div>
        <div class="col-md-4">
            <div class="row">
                <div class="col-md-12">
                    @component('components.box')
                        @slot('box_title')
                            @lang('Marketplace::labels.order.billing_add')
                        @endslot
                        <div class="table-responsive">
                            <table class="table color-table info-table table table-hover table-striped table-condensed">
                                <tbody>
                                @isset($order->billing['billing_address'])
                                    <tr>
                                        <th>@lang('Marketplace::attributes.order.first_name')</th>
                                        <td>{{ $order->billing['billing_address']['first_name'] ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>@lang('Marketplace::attributes.order.last_name')</th>
                                        <td>{{ $order->billing['billing_address']['last_name']  ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>@lang('Marketplace::attributes.order.email')</th>
                                        <td>{{ $order->billing['billing_address']['email']  ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>@lang('Marketplace::attributes.order.phone_number')</th>
                                        <td>{{ $order->billing['billing_address']['phone_number']  ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>@lang('Marketplace::attributes.order.address_one')</th>
                                        <td>{{ $order->billing['billing_address']['address_1'] }}</td>
                                    </tr>
                                    <tr>
                                        <th>@lang('Marketplace::attributes.order.address_two')</th>
                                        <td>{{ $order->billing['billing_address']['address_2'] ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>@lang('Marketplace::attributes.order.city')</th>
                                        <td>{{ $order->billing['billing_address']['city'] }}</td>
                                    </tr>
                                    <tr>
                                        <th>@lang('Marketplace::attributes.order.state')</th>
                                        <td>{{ $order->billing['billing_address']['state'] }}</td>
                                    </tr>
                                    <tr>
                                        <th>@lang('Marketplace::attributes.order.zip')</th>
                                        <td>{{ $order->billing['billing_address']['zip'] }}</td>
                                    </tr>
                                    <tr>
                                        <th>@lang('Marketplace::attributes.order.country')</th>
                                        <td>{{ $order->billing['billing_address']['country'] }}</td>
                                    </tr>
                                @endisset
                                </tbody>
                            </table>
                        </div>
                        @if (user()->hasPermissionTo('Marketplace::order.view_payment_details'))

                            <h4>
                                @lang('Marketplace::labels.order.payment_details')
                            </h4>
                            <div class="table-responsive">
                                <table class="table color-table info-table table table-hover table-striped table-condensed">
                                    <tbody>
                                    <tr>
                                        <th>@lang('Marketplace::attributes.order.method')</th>
                                        <td>{{ $order->billing['gateway'] ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>@lang('Marketplace::attributes.order.payment_status')</th>
                                        <td>{!!  $order->present('payment_status') !!} </td>
                                    </tr>
                                    <tr style="word-break: break-all;">
                                        <th>@lang('Marketplace::attributes.order.payment_reference')</th>
                                        <td>{{ $order->billing['payment_reference'] ?? '-' }}</td>
                                    </tr>
                                    @if(!empty($order->billing['payout_reference']))
                                        <tr style="word-break: break-all;">
                                            <th colspan="2">@lang('Marketplace::attributes.order.payout_reference')</th>
                                        </tr>
                                        <tr style="word-break: break-all;">
                                            @if(is_array($order->billing['payout_reference']))
                                                <td colspan="2">{!! formatProperties($order->billing['payout_reference']) !!}</td>
                                            @else
                                                <td colspan="2">{!! $order->billing['payout_reference'] !!}</td>
                                            @endif
                                        </tr>
                                    @endif
                                    @if(!empty($order->billing['payout_exception']))
                                        <tr style="word-break: break-all;">
                                            <th>@lang('Marketplace::attributes.order.payout_exception')</th>
                                            <td>{{ $order->billing['payout_exception'] }}</td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        @endif

                    @endcomponent
                </div>
            </div>
            @if($order->shipping)
                <div class="row mt-2">
                    <div class="col-md-12">
                        @component('components.box')
                            @slot('box_title')
                                @lang('Marketplace::labels.order.shipping_details')
                            @endslot
                            <div class="table-responsive">
                                <table class="table color-table info-table table table-hover table-striped table-condensed">

                                    <tbody>
                                    @isset($order->shipping['shipping_address'])
                                        <tr>
                                            <th>@lang('Marketplace::attributes.order.first_name')</th>
                                            <td>{{ $order->shipping['shipping_address']['first_name'] ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>@lang('Marketplace::attributes.order.last_name')</th>
                                            <td>{{ $order->shipping['shipping_address']['last_name']  ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>@lang('Marketplace::attributes.order.phone_number')</th>
                                            <td>{{ $order->billing['billing_address']['phone_number']  ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>@lang('Marketplace::attributes.order.company')</th>
                                            <td>{{ $order->billing['billing_address']['company']  ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>@lang('Marketplace::attributes.order.address_one')</th>
                                            <td>{{ $order->shipping['shipping_address']['address_1'] }}</td>
                                        </tr>
                                        <tr>
                                            <th>@lang('Marketplace::attributes.order.address_two')</th>
                                            <td>{{ $order->shipping['shipping_address']['address_2'] ?? '-'  }}</td>
                                        </tr>
                                        <tr>
                                            <th>@lang('Marketplace::attributes.order.city')</th>
                                            <td>{{ $order->shipping['shipping_address']['city'] }}</td>
                                        </tr>
                                        <tr>
                                            <th>@lang('Marketplace::attributes.order.state')</th>
                                            <td>{{ $order->shipping['shipping_address']['state'] }}</td>
                                        </tr>
                                        <tr>
                                            <th>@lang('Marketplace::attributes.order.zip')</th>
                                            <td>{{ $order->shipping['shipping_address']['zip'] }}</td>
                                        </tr>
                                        <tr>
                                            <th>@lang('Marketplace::attributes.order.country')</th>
                                            <td>{{ $order->shipping['shipping_address']['country'] }}</td>
                                        </tr>
                                    @endisset
                                    @foreach($order->shipping['shipping_transactions']??[] as $shipping_transaction)
                                        @if(isset($shipping_transaction['tracking_number']) && !empty($shipping_transaction['tracking_number']))
                                            <tr>
                                                <th>@lang('Marketplace::labels.order.tracking_num')
                                                    @if(!empty($shipping_transaction['product_name']))
                                                        <br/>
                                                        [{{ $shipping_transaction['product_name'] }}]
                                                    @endif
                                                </th>
                                                <td>
                                                    <a href="{{url($resource_url.'/'.$order->hashed_id.'/track/'.$shipping_transaction['tracking_number']) }}"
                                                       class="btn btn-xs btn-primary m-r-5 m-l-5 modal-load"
                                                       data-title="Tracking History">{{ $shipping_transaction['tracking_number'] }}</a>
                                                </td>
                                            </tr>
                                        @endif
                                        @if(user()->hasPermissionTo('Marketplace::store_orders.access')
                                            && isset($shipping_transaction['label_url'])
                                            && !empty($shipping_transaction['label_url']))
                                            <tr>
                                                <th>@lang('Marketplace::labels.order.tracking_label')
                                                    @if(!empty($shipping_transaction['product_name']))
                                                        <br/>
                                                        [{{ $shipping_transaction['product_name'] }}]
                                                    @endif
                                                </th>
                                                <td>
                                                    <a target="_blank"
                                                       href="{{ $shipping_transaction['label_url'] }}">
                                                        @lang('Marketplace::labels.order.click_here')
                                                    </a>
                                                </td>
                                            </tr>
                                        @endif
                                        @if(user()->hasPermissionTo('Marketplace::store_orders.access')
                                            && !empty($shipping_transaction['error_message']))
                                            <tr>
                                                <td colspan="2">
                                                    {!! formatProperties(json_decode(strip_tags($shipping_transaction['error_message']), true)) !!}
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endcomponent
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
