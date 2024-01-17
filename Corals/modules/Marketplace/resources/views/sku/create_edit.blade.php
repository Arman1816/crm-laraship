@extends('layouts.crud.create_edit')

@section('content_header')
    @component('components.content_header')
        @slot('page_title')
            {{ $title_singular }}
        @endslot

        @slot('breadcrumb')
            {{ Breadcrumbs::render('marketplace_sku_create_edit',$product) }}
        @endslot
    @endcomponent
@endsection
@section('css')
    <style>
        .dimensions-hint {
            padding: 3px 5px;
            margin-bottom: 10px;
            display: inline-block;
        }
    </style>
@endsection
@section('content')
    @parent
    <div class="row">
        <div class="col-md-12">
            @component('components.box')
                {!! CoralsForm::openForm($sku, ['url' => url($resource_url.'/'.$sku->hashed_id), 'files' => true]) !!}
                <div class="row">
                    <div class="col-md-4">
                        {!! CoralsForm::number('regular_price','Marketplace::attributes.sku.regular_price',true,$sku->exists?$sku->regular_price:null,['step'=>0.01,'min'=>0,'max'=>999999,'left_addon'=>'<i class="'.$sku->currency_icon.'"></i>']) !!}
                        {!! CoralsForm::number('sale_price','Marketplace::attributes.sku.sale_price',false,$sku->exists?$sku->sale_price:null,['step'=>0.01,'min'=>0,'max'=>999999,'left_addon'=>'<i class="'.$sku->currency_icon.'"></i>']) !!}
                        {!! CoralsForm::number('allowed_quantity','Marketplace::attributes.sku.allowed_quantity', false,$sku->exists?$sku->allowed_quantity:0,
                            ['step'=>1,'min'=>0,'max'=>999999, 'help_text'=>'Marketplace::attributes.sku.help']) !!}

                        {!! CoralsForm::text('code','Marketplace::attributes.sku.code_sku',true, $sku->code,[]) !!}
                        {!! CoralsForm::radio('status','Corals::attributes.status', true, trans('Corals::attributes.status_options')) !!}

                        {!! CoralsForm::select('inventory','Marketplace::attributes.sku.inventory', get_array_key_translation(config('marketplace.models.sku.inventory_options')),true,null,[]) !!}

                        <div id="inventory_value_wrapper"></div>

                    </div>
                    <div class="col-md-4">
                        {!! $product->renderProductOptions('variation_options',$sku->exists ? $sku : null  )  !!}

                        @if($product->shipping['enabled'])
                            <small class="alert alert-warning dimensions-hint">
                                @lang('Marketplace::labels.shipping.dimensions_needed')
                            </small>
                            <div class="row" id="shipping">
                                <div class="col-md-3">
                                    {!! CoralsForm::number('shipping[width]','Marketplace::attributes.sku.width',false,$sku->shipping['width'] ?? data_get($product,'shipping.width')  ,['help_text'=>\Settings::get('marketplace_shipping_dimensions_unit','inch'),'min'=>0]) !!}
                                </div>
                                <div class="col-md-3">
                                    {!! CoralsForm::number('shipping[height]','Marketplace::attributes.sku.height',false,$sku->shipping['height']?? data_get($product,'shipping.height') ,['help_text'=>\Settings::get('marketplace_shipping_dimensions_unit','inch'),'min'=>0]) !!}
                                </div>
                                <div class="col-md-3">
                                    {!! CoralsForm::number('shipping[length]','Marketplace::attributes.sku.length',false,$sku->shipping['length']?? data_get($product,'shipping.length'),['help_text'=>\Settings::get('marketplace_shipping_dimensions_unit','inch'),'min'=>0]) !!}
                                </div>
                                <div class="col-md-3">
                                    {!! CoralsForm::number('shipping[weight]','Marketplace::attributes.sku.weight',false,$sku->shipping['weight']?? data_get($product,'shipping.weight'),['help_text'=>\Settings::get('marketplace_shipping_weight_unit','ounce'),'min'=>0]) !!}
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-4">
                        {!! CoralsForm::file('image', 'Marketplace::attributes.sku.image') !!}

                        <img src="{{ $sku->image }}" class="img-responsive img-fluid" width="150"
                             alt="SKU Image"/>
                        @if($sku->exists && $sku->getFirstMedia('marketplace-sku-image'))

                            {!! CoralsForm::checkbox('clear', 'Marketplace::attributes.sku.clear',0) !!}

                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8">
                        {!! CoralsForm::checkbox('downloads_enabled', 'Marketplace::attributes.sku.downloads_enabled', count($sku->downloads), 1,['onchange'=>"toggleDownloadable();"]) !!}
                        @include('Marketplace::products.partials.downloadable', ['model' => $sku])
                    </div>
                </div>
                {!! CoralsForm::customFields($sku) !!}
                <div class="row">
                    <div class="col-md-6 col-md-offset-6">
                        {!! CoralsForm::formButtons() !!}
                    </div>
                </div>
                {!! CoralsForm::closeForm($sku) !!}
            @endcomponent
        </div>
    </div>
@endsection

@section('js')
    <script type="text/javascript">
        function setInventoryValue(value) {
            var input = '';

            if (value === 'bucket') {
                input = '{{ CoralsForm::select('inventory_value','Marketplace::attributes.sku.inventory_value', get_array_key_translation(config('marketplace.models.sku.bucket')),false,$sku->inventory_value?$sku->inventory_value:null )  }}';
            } else if (value === 'finite') {
                input = '{{ CoralsForm::number('inventory_value','Marketplace::attributes.sku.inventory_value',false,$sku->inventory_value?$sku->inventory_value:null,
                                    ['help_text'=>'',
                                    'step'=>1,'min'=>0,'max'=>999999])  }}';
            } else {
                input = '';
            }

            $("#inventory_value_wrapper").html(input);

            if (input != '') {
                $("#inventory_value_wrapper").show();
            } else {
                $("#inventory_value_wrapper").hide();
            }
        }

        $(document).ready(function () {

            setInventoryValue('{{ old('inventory', $sku->inventory) }}');

            $('#inventory').change(function (event) {
                var value = $(this).val();
                setInventoryValue(value);
            });
        });
    </script>
@endsection
