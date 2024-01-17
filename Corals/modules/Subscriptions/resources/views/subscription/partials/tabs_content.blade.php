<div class="tab-pane @if($active_tab=="subscriptions") active @endif" id="subscriptions_tab">
    @component('components.box')
        <div class="row">
            <div class="col-md-12">
                @if(user()->canUpdatePaymentDetails())
                    {!! CoralsForm::link(url('subscriptions/payment-configuration'), 'Subscriptions::labels.subscription.payment_configuration', ['class'=>'btn btn-info']) !!}
                @endif
                {!! CoralsForm::link(url('subscriptions/select'), 'Subscriptions::labels.subscription.add_new_subscription', ['class'=>'btn btn-success']) !!}
                <div class="m-t-20 mb-2">
                </div>
                @component('components.box')
                    @slot('box_title')
                        <i class="fa fa-toggle-on"></i>
                        &nbsp; @lang('Subscriptions::labels.subscription.current_subscriptions')
                        :
                    @endslot
                    @forelse(user()->activeSubscriptions() as $subscription)
                        <div class="row m-t-10 m-b-10">
                            <div class="col-md-12">
                                <h4 class="text-info"
                                    style="display: inline-block">{{ $subscription->plan->product->name }}
                                    - {{ $subscription->plan->name }}
                                    [{{ $subscription->gateway }}]
                                </h4>
                                <br>
                                <strong>@lang('Subscriptions::attributes.subscription.sub_reference')
                                    : {{ $subscription->subscription_reference }}</strong>
                                <p>
                                    <small>@lang('Subscriptions::attributes.subscription.description'):</small>
                                    <br/> {{ $subscription->plan->description }}</p>


                            </div>
                        </div>
                        <div class="row m-t-10 m-b-10">
                            <div class="col-md-6">
                                @isset($subscription->billing_address)
                                    <h5 class="text-primary">@lang('Subscriptions::labels.subscription.billing_details')</h5>
                                    <p>{{ $subscription->billing_address['address_1'] }}<br>
                                        {{ $subscription->billing_address['address_2'] ?? '-' }}<br>
                                        {{ $subscription->billing_address['city'] }}
                                        , {{ $subscription->billing_address['state'] }}
                                        , {{ $subscription->billing_address['zip'] }}
                                        <br>{{ $subscription->billing_address['country'] }}</p>
                                @endisset

                            </div>
                            <div class="col-md-6">
                                @if($subscription->shipping_address)
                                    <h5 class="text-primary">@lang('Subscriptions::labels.subscription.shipping_details')</h5>
                                    <p>{{ $subscription->shipping_address['address_1'] }}<br>
                                        {{ $subscription->shipping_address['address_2'] ?? '-' }}<br>
                                        {{ $subscription->shipping_address['city'] }}
                                        , {{ $subscription->shipping_address['state'] }}
                                        , {{ $subscription->shipping_address['zip'] }}
                                        <br>{{ $subscription->shipping_address['country'] }}</p>
                                @endif
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                @if(!$subscription->onGracePeriod())
                                    @include('Subscriptions::subscription.partials.render_subscription_actions',[
                                    'actions'=> \Filters::do_filter('subscription_user_actions', [
                                        'update'=> [
                                            'url'=> url('subscriptions/select/'.$subscription->plan->product->hashed_id),
                                            'title'=>trans('Corals::labels.without_icon.update'),
                                            'class'=>'btn btn-sm btn-primary m-r-5',
                                            'icon'=>'fa fa-arrow-circle-up'
                                        ],
                                        'cancel'=>[
                                            'url'=> url('subscriptions/cancel/'.$subscription->plan->hashed_id),
                                            'title'=>trans('Corals::labels.without_icon.cancel'),
                                            'class'=>'btn btn-sm btn-danger',
                                            'icon'=>'fa fa-times-circle'
                                        ]
                                     ],$subscription)
                                    ])

                                    @can('renew',$subscription)
                                        <a href="{{$subscription->getShowURL().'/renew?redirect=1'}}"
                                           class="btn btn-sm btn-success"
                                           data-action="post"
                                           data-page_action="redirectToInvoice"
                                           data-confirmation="@lang('Subscriptions::labels.subscription.renew_subscription_confirmation')">
                                            <i class="fa fa-refresh"></i> @lang('Subscriptions::labels.subscription.renew')
                                        </a>
                                    @endcan
                                @else
                                    <p class="text-warning">
                                        @lang('Subscriptions::labels.subscription.you_have_cancelled_subscription'):
                                        <b>{{ format_date($subscription->ends_at) }}</b>
                                    </p>

                                @endif
                            </div>

                        </div>
                        @if (!$loop->last)
                            <hr/>
                        @endif
                    @empty
                        <div class="row">
                            <div class="col-md-12">
                                <p>@lang('Subscriptions::labels.subscription.start_subscription_now',['pricing_url'=>url('subscriptions/select')])</p>
                            </div>

                        </div>
                    @endforelse
                @endcomponent
                @if(user()->pendingSubscriptions->count())
                    @component('components.box')
                        @slot('box_title')
                            @lang('Subscriptions::labels.subscription.pending_subscription'):
                        @endslot
                        @foreach(user()->pendingSubscriptions() as $subscription)
                            <div class="row m-t-10 m-b-10">
                                <div class="col-md-4">
                                    <h4 class="text-info">{{ $subscription->plan->product->name }}
                                        - {{ $subscription->plan->name }}</h4>

                                    <strong>@lang('Subscriptions::attributes.subscription.sub_reference')
                                        : {{ $subscription->subscription_reference }}</strong>

                                    <p>
                                        <small>Description:</small>
                                        <br/> {{ $subscription->plan->description }}</p>

                                    <p class="text-info">
                                        @lang('Subscriptions::labels.subscription.subscription_not_approved')
                                    </p>
                                    @include('Subscriptions::subscription.partials.render_subscription_actions',[
                                            'actions'=> \Filters::do_filter('subscription_pending_user_actions', [
                                                'cancel'=>[
                                                    'url'=> url('subscriptions/cancel/'.$subscription->plan->hashed_id),
                                                    'title'=>trans('Corals::labels.without_icon.cancel'),
                                                    'class'=>'btn btn-sm btn-danger',
                                                    'icon'=>'fa fa-times-circle'
                                                ]
                                             ],$subscription)
                                            ])

                                </div>
                                <div class="col-md-4">
                                    @isset($subscription->billing_address)
                                        <h5 class="text-primary">@lang('Subscriptions::labels.subscription.billing_details')</h5>
                                        <p>{{ $subscription->billing_address['address_1'] }}<br>
                                            {{ $subscription->billing_address['address_2'] ?? '-' }}<br>
                                            {{ $subscription->billing_address['city'] }}
                                            , {{ $subscription->billing_address['state'] }}
                                            , {{ $subscription->billing_address['zip'] }}
                                            <br>{{ $subscription->billing_address['country'] }}</p>
                                    @endisset

                                </div>
                                <div class="col-md-4">
                                    @if($subscription->shipping_address)
                                        <h5 class="text-primary">@lang('Subscriptions::labels.subscription.shipping_details')</h5>
                                        <p>{{ $subscription->shipping_address['address_1'] }}<br>
                                            {{ $subscription->shipping_address['address_2'] ?? '-' }}<br>
                                            {{ $subscription->shipping_address['city'] }}
                                            , {{ $subscription->shipping_address['state'] }}
                                            , {{ $subscription->shipping_address['zip'] }}
                                            <br>{{ $subscription->shipping_address['country'] }}</p>
                                    @endif
                                </div>

                            </div>
                            @if (!$loop->last)
                                <hr>
                            @endif
                        @endforeach
                    @endcomponent
                @endif
            </div>
        </div>
    @endcomponent
</div>

<div class="tab-pane @if($active_tab=="invoices") active @endif" id="invoices">
    @component('components.box')
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive m-t-10"
                     style="min-height: 235px;padding-bottom: 20px;">
                    {!! $invoicesDataTable->table(['class' => 'color-table info-table table table-hover table-striped table-condensed','style'=>'width:100%;']) !!}
                </div>
            </div>
        </div>
    @endcomponent
</div>


<div class="tab-pane @if($active_tab=="subscription_cycles") active @endif" id="subscription_cycles">
    @component('components.box')
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive m-t-10"
                     style="min-height: 235px;padding-bottom: 20px;">
                    {!! $subscriptionCyclesDataTable->table(['class' => 'color-table info-table table table-hover table-striped table-condensed','style'=>'width:100%;']) !!}
                </div>
            </div>
        </div>
    @endcomponent
</div>

<script>
    function redirectToInvoice(data) {
        console.log(data);

        if (data.invoice_link) {
            location.href = data.invoice_link;
        }
    }
</script>
