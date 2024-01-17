{!! Form::open( ['url' => url($urlPrefix.'checkout/step/billing-shipping-address'),'method'=>'POST', 'class'=>'ajax-form','id'=>'checkoutForm']) !!}
<div class="row">
    <div class="col-md-12">
        <h4>@lang('Marketplace::labels.checkout.title')</h4>
        <hr>
        <div class="row">
            <div class="col-md-3">
                {!! CoralsForm::text('billing_address[first_name]','Corals::labels.address_label.first_name',true, $billing_address['first_name'] ?? '') !!}
            </div>
            <div class="col-md-3">
                {!! CoralsForm::text('billing_address[last_name]','Corals::labels.address_label.last_name',true, $billing_address['last_name'] ?? '') !!}
            </div>
            <div class="col-md-3">
                {!! CoralsForm::text('billing_address[email]','Corals::labels.address_label.email',true, $billing_address['email'] ?? '') !!}
            </div>
            <div class="col-md-3">
                {!! CoralsForm::text('billing_address[phone_number]','Corals::labels.address_label.phone_number',true, $billing_address['phone_number'] ?? '') !!}
            </div>
        </div>
        @include('components.address',['key'=>'billing_address', 'object'=> $billing_address,'type'=>'billing','container'=>'col-md-12'])
        @auth
            {!! CoralsForm::checkbox('save_billing', 'Marketplace::labels.checkout.save_shipping',true) !!}
        @endauth
    </div>
</div>


@if($enable_shipping)
    <div class="row">
        <div class="col-md-12">
            <h4>@lang('Marketplace::labels.checkout.shipping_title')</h4>
            <hr>
            {!! CoralsForm::checkbox('copy_billing', 'Marketplace::labels.checkout.copy_billing') !!}
            <div class="row">
                <div class="col-md-3">
                    {!! CoralsForm::text('shipping_address[first_name]','Corals::labels.address_label.first_name',true, $shipping_address['first_name'] ?? '') !!}
                </div>
                <div class="col-md-3">
                    {!! CoralsForm::text('shipping_address[last_name]','Corals::labels.address_label.last_name',true, $shipping_address['last_name'] ?? '') !!}
                </div>
                <div class="col-md-3">
                    {!! CoralsForm::text('shipping_address[phone_number]','Corals::labels.address_label.phone_number',true, $shipping_address['phone_number'] ?? '') !!}
                </div>
                <div class="col-md-3">
                    {!! CoralsForm::text('shipping_address[company]','Corals::labels.address_label.company',false, $shipping_address['company'] ?? '') !!}

                </div>

            </div>
            @include('components.address',['key'=>'shipping_address', 'object'=> $shipping_address,'type'=>'shipping','container'=>'col-md-12'])
            @auth
                {!! CoralsForm::checkbox('save_shipping', 'Marketplace::labels.checkout.save_shipping',true) !!}
            @endauth
        </div>
    </div>
@endif

@php \Actions::do_action('checkout-address-form') @endphp
{!! Form::close() !!}

<script>

    $(document).ready(function () {
        $('#copy_billing').change(function (event) {
            if ($(this).prop('checked')) {
                $('input[name="shipping_address[first_name]"]').val($('input[name="billing_address[first_name]"]').val());
                $('input[name="shipping_address[last_name]"]').val($('input[name="billing_address[last_name]"]').val());
                $('input[name="shipping_address[phone_number]"]').val($('input[name="billing_address[phone_number]"]').val());
                $('input[name="shipping_address[address_1]"]').val($('input[name="billing_address[address_1]"]').val());
                $('input[name="shipping_address[address_2]"]').val($('input[name="billing_address[address_2]"]').val());
                $('input[name="shipping_address[city]"]').val($('input[name="billing_address[city]"]').val());
                $('input[name="shipping_address[state]"]').val($('input[name="billing_address[state]"]').val());
                $('input[name="shipping_address[zip]"]').val($('input[name="billing_address[zip]"]').val());
                $('select[name="shipping_address[country]"]').val($('select[name="billing_address[country]"]').val());
                $('select[name="shipping_address[country]"]').trigger('change');
            }
        });
    });
</script>
