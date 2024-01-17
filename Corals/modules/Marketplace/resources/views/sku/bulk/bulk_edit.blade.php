@extends('layouts.crud.create_edit')

@section('content_header')
    @component('components.content_header')
        @slot('page_title')
            {{ $title }}
        @endslot

        @slot('breadcrumb')
            {{ Breadcrumbs::render('marketplace_sku_create_edit',$product) }}
        @endslot
    @endcomponent
@endsection

@section('css')
    <style>
        .sku-block .form-group {
            margin-bottom: 5px;
        }

        .d-block {
            display: block;
        }

        .sku-block:nth-child(odd) {
            background-color: #F7F7F7;
        }

        .sku-block {
            margin-bottom: 0;
        }

        .sku-single-block {
            margin-top: 15px;
        }

        .dimensions-hint {
            padding: 3px 5px;
            margin-bottom: 10px;
            display: inline-block;
        }
    </style>
@endsection
@section('content')
    @parent
    {!! CoralsForm::openForm(null, ['url'=>url($resource_url.'/bulk-update'),'method'=>'PUT','files'=>true]) !!}

    {!! Form::hidden('generate_option', $generate_option) !!}

    <div>
        <div class="row">
            <div class="col-md-12">
                {!! CoralsForm::formButtons('Corals::labels.submit') !!}
            </div>
        </div>
        @foreach($skus as $sku)
            @component('components.box',['box_class'=>'sku-block'])
                <div class="row">
                    <div class="col-md-1">
                        {!! $sku->present('image') !!}
                    </div>
                    <div class="col-md-6">
                        <h4>{{ $sku->code }}</h4>
                        {!! $sku->present('options') !!}
                    </div>
                    @if(user()->can('destroy', $sku))
                        <div class="col-md-5 text-right text-danger">
                            {!! CoralsForm::checkbox('sku['.$sku->id.'][delete]','Corals::labels.without_icon.delete') !!}
                        </div>
                    @endif
                </div>
                <div class="row">
                    <div class="col-md-3">
                        {!! CoralsForm::text('sku['.$sku->id.'][code]','Marketplace::attributes.sku.code_sku', true, $sku->code) !!}
                    </div>
                    @if($generate_option === 'apply_unique')
                        <div class="col-md-3">
                            {!! CoralsForm::number('sku['.$sku->id.'][regular_price]','Marketplace::attributes.sku.regular_price', true, $sku->exists?$sku->regular_price:null,['step'=>0.01,'min'=>0,'max'=>999999,'left_addon'=>'<i class="'.$sku->currency_icon.'"></i>']) !!}
                        </div>
                        <div class="col-md-3">
                            {!! CoralsForm::number('sku['.$sku->id.'][sale_price]','Marketplace::attributes.sku.sale_price', false,$sku->exists?$sku->sale_price:null,['step'=>0.01,'min'=>0,'max'=>999999,'left_addon'=>'<i class="'.$sku->currency_icon.'"></i>']) !!}
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>
                                    @lang('Marketplace::attributes.sku.image')
                                </label>
                                {!! Form::file('sku['.$sku->id.'][image]') !!}
                            </div>
                        </div>
                        <div class="col-md-1">
                            @if($sku->getFirstMedia('marketplace-sku-image'))
                                <div class="form-group">
                                    <label class="d-block">
                                        &nbsp;
                                    </label>
                                    {!! CoralsForm::checkbox('sku['.$sku->id.'][clear]', 'Marketplace::attributes.sku.clear_image',0) !!}
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
                @if($generate_option === 'apply_unique')
                    <div class="row inventory-wrapper">
                        <div class="col-md-3">
                            {!! CoralsForm::radio('sku['.$sku->id.'][status]','Corals::attributes.status', true, trans('Corals::attributes.status_options'), $isFromBulk?'active':$sku->status) !!}
                        </div>
                        <div class="col-md-3">
                            {!! CoralsForm::select('sku['.$sku->id.'][inventory]', 'Marketplace::attributes.sku.inventory', get_array_key_translation(config('marketplace.models.sku.inventory_options')),true, $sku->inventory, ['class'=>'inventory-select','data-sku_id'=>$sku->id,'data-inventory_value'=>$sku->inventory_value]) !!}
                        </div>
                        <div class="col-md-3 inventory-value">
                        </div>
                    </div>
                    @if($product->shipping['enabled']??false)
                        <small class="alert alert-warning dimensions-hint">
                            @lang('Marketplace::labels.shipping.dimensions_needed')
                        </small>
                        <div class="row" id="shipping">
                            <div class="col-md-3">
                                {!! CoralsForm::number('sku['.$sku->id.'][shipping][width]','Marketplace::attributes.sku.width',false,$sku->shipping['width'] ?? data_get($product,'shipping.width'),['help_text'=>\Settings::get('marketplace_shipping_dimensions_unit','inch'),'min'=>0]) !!}
                            </div>
                            <div class="col-md-3">
                                {!! CoralsForm::number('sku['.$sku->id.'][shipping][height]','Marketplace::attributes.sku.height',false,$sku->shipping['height']?? data_get($product,'shipping.height') ,['help_text'=>\Settings::get('marketplace_shipping_dimensions_unit','inch'),'min'=>0]) !!}
                            </div>
                            <div class="col-md-3">
                                {!! CoralsForm::number('sku['.$sku->id.'][shipping][length]','Marketplace::attributes.sku.length',false,$sku->shipping['length']?? data_get($product,'shipping.length') ,['help_text'=>\Settings::get('marketplace_shipping_dimensions_unit','inch'),'min'=>0]) !!}
                            </div>
                            <div class="col-md-3">
                                {!! CoralsForm::number('sku['.$sku->id.'][shipping][weight]','Marketplace::attributes.sku.weight',false,$sku->shipping['weight']?? data_get($product,'shipping.weight') ,['help_text'=>\Settings::get('marketplace_shipping_weight_unit','ounce'),'min'=>0]) !!}
                            </div>
                        </div>
                    @endif
                @endif
            @endcomponent
        @endforeach
        @if($generate_option === 'apply_single' && isset($sku))
            @component('components.box',['box_class'=>'sku-single-block'])
                <div class="row">
                    <div class="col-md-3">
                        {!! CoralsForm::number('regular_price','Marketplace::attributes.sku.regular_price',true, null,['step'=>0.01,'min'=>0,'max'=>999999,'left_addon'=>'<i class="'.$sku->currency_icon.'"></i>']) !!}
                    </div>
                    <div class="col-md-3">
                        {!! CoralsForm::number('sale_price','Marketplace::attributes.sku.sale_price',false, null,['step'=>0.01,'min'=>0,'max'=>999999,'left_addon'=>'<i class="'.$sku->currency_icon.'"></i>']) !!}
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>
                                @lang('Marketplace::attributes.sku.image')
                            </label>
                            {!! Form::file('image') !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        {!! CoralsForm::radio('status','Corals::attributes.status', true, trans('Corals::attributes.status_options'), $isFromBulk?'active':$sku->status) !!}
                    </div>
                </div>
                <div class="row inventory-wrapper">
                    <div class="col-md-3">
                        {!! CoralsForm::select('inventory', 'Marketplace::attributes.sku.inventory', get_array_key_translation(config('marketplace.models.sku.inventory_options')),true, 'finite', ['class'=>'inventory-select']) !!}
                    </div>
                    <div class="col-md-3 inventory-value">
                    </div>
                </div>
            @endcomponent
        @endif
        <div class="row mt-3 m-t-10">
            <div class="col-md-12">
                {!! CoralsForm::formButtons('Corals::labels.submit') !!}
            </div>
        </div>
    </div>
    {!! CoralsForm::closeForm() !!}
@endsection

@section('js')
    <script type="text/javascript">
        function setInventoryValue(element) {
            let selectedValue = element.val();
            let sku_id = element.data('sku_id');
            let inventoryValue = element.data('inventory_value');
            let valueWrapper = element.closest('.inventory-wrapper').find('.inventory-value');

            let input = '';

            if (selectedValue === 'bucket') {
                input = '{{ CoralsForm::select('inventory_value_name','Marketplace::attributes.sku.inventory_value', get_array_key_translation(config('marketplace.models.sku.bucket')), false, null,['id'=>'inventory_value-sku_id']) }}';
            } else if (selectedValue === 'finite') {
                input = '{{ CoralsForm::number('inventory_value_name','Marketplace::attributes.sku.inventory_value',false,null,['step'=>1,'min'=>0,'max'=>999999,'id'=>'inventory_value-sku_id']) }}';
            } else {
                input = '';
            }
            if (sku_id) {
                input = input.replaceAll('inventory_value_name', 'sku[sku_id][inventory_value]');
                input = input.replaceAll('sku_id', sku_id);
            } else {
                input = input.replaceAll('inventory_value_name', 'inventory_value');
            }

            valueWrapper.html(input);

            if (sku_id) {
                $(`#inventory_value-${sku_id}`).val(inventoryValue);
            }
        }

        $(document).ready(function () {
            let inventorySelect = $('.inventory-select');

            inventorySelect.change(function (event) {
                let el = $(this);

                setInventoryValue(el);
            });

            inventorySelect.trigger('change');
        });
    </script>
@endsection
