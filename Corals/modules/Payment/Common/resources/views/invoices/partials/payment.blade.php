<div class="row">
    <div class="col-md-12">


        @php \Actions::do_action('pre_order_checkout_form',$gateway) @endphp
        <div class="">
            {!! Form::open( ['url' => url($urlPrefix.'checkout/step/select-payment'),'method'=>'POST','files'=>true,'class'=>'ajax-form','id'=>'PaymentForm']) !!}

            <h4>@lang('Payment::labels.select_payment')</h4>
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
    </div>
</div>

@push('partial_js')

    <script src="https://js.stripe.com/v3/"></script>

    <script type="application/javascript">
        $(document).ready(function () {
            var invoiceId = '{{ $invoice->hashed_id }}';

            $('input[name="select_gateway"]').on('change', function () {

                if ($(this).prop('checked')) {
                    var gatewayName = $(this).val();
                    var url = '{{ url('invoice/payments/gateway-payment') }}' + "/" + gatewayName + "/" + invoiceId;
                    $("#gatewayPayment").empty();
                    $("#gatewayPayment").load(url);
                }
            });
        });
    </script>

@endpush
