@extends('layouts.crud.create_edit')



@section('content_header')
    @component('components.content_header')
        @slot('page_title')
            {{ $title_singular }}
        @endslot

        @slot('breadcrumb')
            {{ Breadcrumbs::render('marketplace_shipping_create_edit') }}
        @endslot
    @endcomponent
@endsection

@section('content')
    @parent
    <div class="row">
        <div class="col-md-8">
            @component('components.box')
                {!! CoralsForm::openForm($shipping) !!}
                <div class="row">
                    <div class="col-md-6">
                        {!! CoralsForm::text('name','Marketplace::attributes.shipping.name',true,null,['help_text'=>'Marketplace::attributes.shipping.help_shipping_name']) !!}
                        {!! CoralsForm::select('country', 'Marketplace::attributes.shipping.country', \Settings::getCountriesList(),false , null,['placeholder'=>'Marketplace::labels.shipping.place_holder']) !!}
                        {!! CoralsForm::select('shipping_method', 'Marketplace::attributes.shipping.shipping_method', \Shipping::getShippingMethods() , true) !!}

                        <div id="cart_level">
                            {!! CoralsForm::checkbox('properties[cart_level]',
                                'Marketplace::attributes.shipping.is_cart_level',
                                $shipping->getProperty('cart_level', 0) == 1, 1,[]) !!}
                        </div>

                        {!! CoralsForm::number('min_order_total','Marketplace::attributes.shipping.min_order_total',false,$shipping->min_order_total ?? 0.0,
                        array_merge(['help_text'=>'Marketplace::attributes.shipping.help','right_addon'=>'<i class="fa fa-fw fa-'.strtolower(  \Payments::admin_currency_code()).'"></i>',
                        'step'=>0.01,'min'=>0,'max'=>999999])) !!}
                        {!! CoralsForm::checkbox('exclusive', 'Marketplace::attributes.shipping.exclusive', $shipping->exclusive,1, ['help_text'=>'Marketplace::attributes.shipping.help_exclusive'] ) !!}

                        {!! CoralsForm::number('min_total_quantity','Marketplace::attributes.shipping.min_total_quantity',false,$shipping->min_total_quantity ?? 0,array_merge(['help_text'=>'','step'=>1,'min'=>0,'max'=>999999])) !!}
                        {!! CoralsForm::number('max_total_weight','Marketplace::attributes.shipping.max_total_weight',false,$shipping->max_total_weight ?? '',array_merge(['help_text'=>'','step'=>0.01,'min'=>0.01,'max'=>999999])) !!}
                    </div>
                    <div class="col-md-6">
                        {!! CoralsForm::number('priority','Marketplace::attributes.shipping.priority',true,null,['step'=>1,'min'=>0,'max'=>999999,'help_text'=>'Marketplace::attributes.shipping.help_num_higher']) !!}

                        {!! CoralsForm::number('rate','Marketplace::attributes.shipping.rate',false,$shipping->rate ?? 0.0,
array_merge(['help_text'=>'Marketplace::attributes.shipping.help','right_addon'=>'<i class="fa fa-fw fa-'.strtolower(  \Payments::admin_currency_code()).'"></i>',
'step'=>0.01,'min'=>0,'max'=>999999])) !!}
                        {!! CoralsForm::select('tax_classes[]','Marketplace::attributes.product.tax_classes', \Payments::getTaxClassesList(), false, null,['multiple'=>true], 'select2') !!}
                        {!! CoralsForm::textarea('description','Marketplace::attributes.shipping.description',false,null,['rows'=>3]) !!}
                        {!! \Store::getStoreFields($shipping) !!}

                    </div>
                </div>
                {!! CoralsForm::customFields($shipping, 'col-md-6') !!}

                <div class="row">
                    <div class="col-md-12">
                        {!! CoralsForm::formButtons() !!}
                    </div>
                </div>
                {!! CoralsForm::closeForm($shipping) !!}
            @endcomponent
        </div>
    </div>
@endsection

@section('js')
    <script>
        $("#shipping_method").on('change', function () {
            console.log($(this).val().includes('FlatRate'));
            if ($(this).val().includes('FlatRate')) {
                $("#cart_level").fadeIn();
            } else {
                $("#cart_level").hide();
            }
        })

        $(document).ready(() => {
            $("#shipping_method").trigger('change');
        })
    </script>
@endsection
