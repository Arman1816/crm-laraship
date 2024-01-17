<div class="row">
    <div class="col-md-10 col-md-offset-1 offset-md-1">
        @if(\Settings::get('marketplace_checkout_points_redeem_enable',true))

            @component('components.box',['box_class'=>'box-success'])
                <h4>@lang('Marketplace::labels.checkout.pay_using_point')</h4>
                <hr>
                @if($can_redeem)
                    <h6 class="my-2">@lang('Marketplace::labels.cart.have_points_to_redeem',['points_needed'=>$points_needed,'available_points_blanace'=>$available_points_blanace])
                    </h6>
                    <form action="{{ url('checkout/step/select-payment') }}"
                          method="post" id="point-redeem-form" class="ajax-form">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                        <input type="hidden" name="checkoutToken" value="REDEEM-{{$amount}}-POINTS"/>
                        <input type="hidden" name="gateway" value="RedeemPoints"/>


                        {!! CoralsForm::checkbox('use_points','Marketplace::labels.checkout.pay_using_point'  ) !!}

                        <br/>
                    </form>

                @else
                    @lang('Marketplace::labels.cart.have_no_points')

                @endif
            @endcomponent
        @endif
        <div id="payment-section" class="mt-2">

            @component('components.box',['box_class'=>'box-success'])

                @if($gateway)
                    <h4>@lang('Marketplace::labels.checkout.enter_payment')</h4>
                    <hr>
                    @include($gateway->getPaymentViewName('marketplace'),['action'=>$urlPrefix.'checkout/step/select-payment','amount'=>$amount])
                @else
                    @php \Actions::do_action('pre_marketplace_checkout_form',$gateway) @endphp
                    <div class="">
                        {!! Form::open( ['url' => url($urlPrefix.'checkout/step/select-payment'),'method'=>'POST','files'=>true,'class'=>'ajax-form','id'=>'PaymentForm']) !!}
                        <h4>@lang('Marketplace::labels.checkout.select_payment')</h4>
                        <hr>
                        <br>
                        {!! CoralsForm::radio('select_gateway','',true,  $available_gateways ) !!}
                        <div class="form-group">
                            <span data-name="checkoutToken"></span>
                        </div>
                    </div>
                    {!! Form::close() !!}
                    <div id="gatewayPayment">

                    </div>
                @endif
            @endcomponent
        </div>


    </div>
</div>

<script type="application/javascript">
    $(document).ready(function () {
        $('input[name="select_gateway"]').on('change', function () {

            if ($(this).prop('checked')) {
                var gatewayName = $(this).val();
                var url = '{{ url('checkout/gateway-payment') }}' + "/" + gatewayName;
                $("#gatewayPayment").empty();
                $("#gatewayPayment").load(url);
            }
        });


        $('input[name="use_points"]').on('change', function () {
            if ($(this).prop('checked')) {
                $('#payment-section').hide();
            } else {
                $('#payment-section').show();
            }
        });


    });
</script>
